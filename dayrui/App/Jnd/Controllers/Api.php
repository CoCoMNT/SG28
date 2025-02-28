<?php namespace Phpcmf\Controllers;

use Phpcmf\Common;
use Phpcmf\Service;
use Throwable;

class Api extends Common
{

    public function index(): void
    {
        $data=Service::L('cache')->get_data("canada_data");
        if(empty($data)){
            $this->SearchCanada();
            $data=Service::L('cache')->get_data("canada_data");
        }
        $issue = array();
        $time = array();
        $N1 = array();
        $N2 = array();
        $N3 = array();
        $N4 = array();
        for ($i = 0; $i < 5; $i++) {
            $issue[] = $data[$i]['issue'];
            $time[] = $data[$i]['time'];
            $N1[] = $data[$i]['n1'];
            $N2[] = $data[$i]['n2'];
            $N3[] = $data[$i]['n3'];
            $N4[] = $data[$i]['n4'];
        }
        var_dump($data);
        echo $this->calculateText("N1+N2+N3+N4+取首(N4)+取尾(N4)", $N1, $N2, $N3, $N4, $issue);
    }

    private function calculateText(string $text, array $N1, array $N2, array $N3, array $N4, array $issue): string
    {
        $resultText = $text;

        // 创建数字到数组索引的映射
        $arrayMap = [];
        for ($i = 0; $i < 48; $i++) {
            $arrayIndex = floor($i / 4);  // 确定在哪个索引位置
            $arrayNum = ($i % 4) + 1;     // 确定是哪个数组(N1-N4)
            
            $value = match($arrayNum) {
                1 => $N1[$arrayIndex] ?? 0,
                2 => $N2[$arrayIndex] ?? 0,
                3 => $N3[$arrayIndex] ?? 0,
                4 => $N4[$arrayIndex] ?? 0,
                default => 0
            };
            
            $arrayMap['N' . ($i + 1)] = $value;
        }

        // 处理取首函数
        $resultText = preg_replace_callback('/取首\((N\d+)\)/', function($matches) use ($arrayMap) {
            $value = $arrayMap[$matches[1]] ?? 0;
            return '$this->getFirstChar("' . $value . '")';
        }, $resultText);

        // 处理取尾函数
        $resultText = preg_replace_callback('/取尾\((N\d+)\)/', function($matches) use ($arrayMap) {
            $value = $arrayMap[$matches[1]] ?? 0;
            return '$this->getLastChar("' . $value . '")';
        }, $resultText);

        // 替换所有N数字为实际值
        $resultText = preg_replace_callback('/N(\d+)/', function($matches) use ($arrayMap) {
            return $arrayMap['N' . $matches[1]] ?? 0;
        }, $resultText);

        // 执行计算
        try {
            return eval('return ' . $resultText . ';');
        } catch (Throwable $e) {
            return 'Error in calculation: ' . $e->getMessage();
        }
    }
    private function SearchCanada(): void
    {
        $sql='SELECT * FROM `dr_canada_data` ORDER BY `issue` DESC LIMIT 0,3000';
        $canada = Service::M()->db->query($sql)->getResultArray();
        Service::L('cache')->set_data("canada_data", $canada,86400);
    }
    private function getFirstChar($text) {
        return mb_substr($text, 0, 1);
    }
    private function getLastChar($text) {
        return mb_substr($text, -1);
    }
}
