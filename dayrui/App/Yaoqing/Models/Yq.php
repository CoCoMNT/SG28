<?php namespace Phpcmf\Model;

class Yq extends \Phpcmf\Model
{

    // 注册邀请关系
    public function register($puid, $data) {

        $rt = $this->yaoqing_rule($puid);
        if ($rt['code']) {
            // 绑定关系
            $puser = dr_member_info($puid);
            $this->table('app_yaoqing_user')->insert([
                'uid' => $data['id'],
                'username' => $data['username'],
                'puid' => $puser['id'],
                'pusername' => $puser['username'],
                'money' => 0,
                'czfx' => (int)$rt['data']['czfx'],
                'inputtime' => SYS_TIME,
            ]);
           
            if ($rt['data']['score']) {
                // 赠送金币
                \Phpcmf\Service::M('member')->add_score($puid, $rt['data']['score'], '邀请用户【'.$data['username'].'】注册赠送');
            }
            if ($rt['data']['exp']) {
                // 赠送经验值
                \Phpcmf\Service::M('member')->add_experience($puid, $rt['data']['exp'], '邀请用户【'.$data['username'].'】注册赠送');
            }
        } else {
            log_message('error', '邀请注册插件：【uid#'.$puid.'】'.$rt['msg']);
        }

    }

    // 邀请注册规则
    public function yaoqing_rule($uid) {

        $app = \Phpcmf\Service::M('app')->get_config('yaoqing');
        $member = dr_member_info($uid);
        if (!$member['groupid']) {
            return dr_return_data(0, '用户uid['.$uid.']没有加入任何用户组');
        }

        if ($app['groupid']) {
            $czfx = $score = $exp = [];
            foreach ($app['groupid'] as $gid) {
                if (in_array($gid, $member['groupid'])) {
                    $czfx[] = $app['czfx'][$gid];
                    $exp[] = $app['exp'][$gid];
                    $score[] = $app['score'][$gid];
                }
            }

            return dr_return_data(1, '', [
                'exp' => $exp ? max($exp) : 0,
                'score' => $score ? max($score) : 0,
                'czfx' => $czfx ? max($czfx) : 0,
            ]);
        } else {
            return dr_return_data(0, '没有设置比例和返现');
        }
    }

    // 记录日志
    public function add_log($yq, $value, $msg) {
        $this->table('app_yaoqing_log')->insert([
            'uid' => $yq['uid'],
            'username' => $yq['username'],
            'puid' => $yq['puid'],
            'pusername' => $yq['pusername'],
            'money' => $value,
            'content' => $msg,
            'status' => 1,
            'inputtime' => SYS_TIME,
        ]);
        $this->table('app_yaoqing_user')->update($yq['id'], [
          'money'=> $yq['money']+$value,
        ]);
    }

    // 用户充值触发返现
    public function czfx($pay) {

        if ($pay['value'] > 0 ) {

            $yq = $this->table('app_yaoqing_user')->where('uid', $pay['uid'])->getRow();
            if (!$yq) {
                return;
            } elseif (!$yq['czfx']) {
                return;
            }
            // 返现金额
            $fan = $pay['value'] * ((int)$yq['czfx']/100);
            if (!$fan) {
                return;
            }

            $msg = "邀请用户[".$pay['username']."]充值返现：".$fan;
            $this->add_log($yq, $fan, $msg);

            \Phpcmf\Service::M('member')->add_money($yq['puid'], $fan);
            \Phpcmf\Service::M('Pay')->add_paylog([
                'uid' => $yq['puid'],
                'username' => $yq['pusername'],
                'touid' => 0,
                'tousername' => '',
                'mid' => 'system',
                'title' => $msg,
                'value' => $fan,
                'type' => 'finecms',
                'status' => 1,
                'result' => '',
                'paytime' => SYS_TIME,
                'inputtime' => SYS_TIME,
            ]);

        }

    }

    // 保留
    public function insert_group($uid, $gid, $price) {

    }


    // 总计
    public function money($uid) {

        $sum = $this->db->table('app_yaoqing_user')->selectSum('money')->where('puid', $uid)->get()->getRowArray();
        if (!$sum) {
            return 0;
        }

        return $sum['money'];
    }

}