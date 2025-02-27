<?php
namespace Phpcmf\Controllers;
use Phpcmf\Common;
use Config\Services;
use DateTime;
use DateTimeZone;
use Exception;
use Phpcmf\Service;
class Repairjnd extends Common
{
    public function index(): void
    {
        $data = $this->request->getGet('date');
        echo $data;
        if (empty($data)) {
            echo "缺少参数 date";
            return;
        }
        $client = Services::curlrequest();
        try {
            $response = $client->request('GET', "http://54.39.24.253/api2.php", [
                'query'   => ['date' => $data],
                'timeout' => 10,
            ]);
            $body = $response->getBody();
        } catch (Exception $e) {
            log_message('error', '接口请求失败: ' . $e->getMessage());
            echo "接口请求失败";
            return;
        }

        // 解析接口返回数据
        $codeArray = json_decode($body, true);
        if (empty($codeArray) || !is_array($codeArray)) {
            echo "接口返回数据不合法";
            return;
        }

        // 循环处理每条记录
        foreach ($codeArray as $record) {
            if (
                !isset($record['drawNbr'], $record['drawDate'], $record['drawTime'], $record['drawNbrs']) ||
                !is_array($record['drawNbrs']) ||
                count($record['drawNbrs']) < 18
            ) {
                log_message('error', '记录数据不完整或格式错误');
                continue;
            }

            $issue = $record['drawNbr'];
            $timeStr = $record['drawDate'] . $record['drawTime'];

            // 转换时区 从 America/Vancouver 到 Asia/Shanghai
            try {
                $date = new DateTime($timeStr, new DateTimeZone('America/Vancouver'));
                $date->setTimezone(new DateTimeZone('Asia/Shanghai'));
                $bjTime = $date->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                log_message('error', '日期转换错误: ' . $e->getMessage());
                continue;
            }

            // 对数字进行排序，确保按升序排列
            $drawNbrs = $record['drawNbrs'];
            sort($drawNbrs, SORT_NUMERIC);

            // 将 drawNbrs 分为三个组：
            $group1 = [1, 4, 7, 10, 13, 16];
            $group2 = [2, 5, 8, 11, 14, 17];
            $group3 = [3, 6, 9, 12, 15, 18];

            $sum1 = $sum2 = $sum3 = 0;
            foreach ($group1 as $index) {
                $sum1 += (int)$drawNbrs[$index];
            }
            foreach ($group2 as $index) {
                $sum2 += (int)$drawNbrs[$index];
            }
            foreach ($group3 as $index) {
                $sum3 += (int)$drawNbrs[$index];
            }

            // 取个位数（使用取余更直观）
            $n1 = (string)($sum1 % 10);
            $n2 = (string)($sum2 % 10);
            $n3 = (string)($sum3 % 10);

            // 计算 n4
            $nSum = (int)$n1 + (int)$n2 + (int)$n3;
            $n4 = ($nSum < 10) ? "0$nSum" : (string)$nSum;

            // 使用参数绑定执行 REPLACE INTO 语句
            $sql = "REPLACE INTO dr_canada_data(`issue`, `time`, `n1`, `n2`, `n3`, `n4`,`jndnumber`)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            try {
                Service::M()->db->query($sql, [$issue, $bjTime, $n1, $n2, $n3, $n4, implode(',', $drawNbrs)]);
            } catch (Exception $e) {
                log_message('error', '数据库操作出错: ' . $e->getMessage());
            }
        }

        echo "数据处理完毕";
    }

}
