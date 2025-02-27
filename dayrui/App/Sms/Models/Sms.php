<?php namespace Phpcmf\Model\Sms;

class Sms extends \Phpcmf\Model
{
    private $rows = [];

    // 当前用户信息
    public function myinfo($uid = 0) {

        if (isset($this->rows[$uid]) && $this->rows[$uid]) {
            return $this->rows[$uid];
        }

        $member = dr_member_info($uid);
        if ($member) {
            $my = [
                'id' => $member['id'],
                'uid' => $member['id'],
                'name' => $member['name'] ? $member['name'] : $member['username'],
                'uname' => $member['username'],
                'avatar' => $member['avatar'],
            ];
        } else {
            $my = [
                'id' => strlen($uid) > 30 ? $uid : (defined('USER_HTTP_CODE') ? USER_HTTP_CODE : \Phpcmf\Service::L('input')->ip_address()), // 大于30表示接受者是访客的随机id
                'uid' => 0,
                'name' => '访客',
                'avatar' => dr_avatar(0),
            ];
        }

        $this->rows[$uid] = $my;

        return $my;
    }

    // 我的好友
    public function myfriend($uid) {
        // 我的好友
        $user = $this->myinfo(1);
        $myfriend = [
            1 => [
                'groupname' => '好友',
                'id' => '1',
                'online' => '1',
                'list' => [
                    [
                        'id' => $user['id'],
                        'uid' => $user['uid'],
                        'name' => $user['name'],
                        'sign' => '',
                        'avatar' => $user['avatar'],
                    ]
                ],
            ],
            2 => [
                'groupname' => '游客',
                'id' => '2',
                'online' => '0',
                'list' => [],
            ],
        ];

        if ($uid) {
            $gx = $this->table('app_sms_gx')->where('from_uid', $uid)->order_by('inputtime desc')->getAll();
            if ($gx) {
                foreach ($gx as $t) {
                    $user = $this->myinfo($t['to_uid']);
                    $myfriend[$t['gid']]['list'][] = [
                        'id' => $user['id'],
                        'uid' => $user['uid'],
                        'name' => $user['name'],
                        'sign' => '',
                        'avatar' => $user['avatar'],
                    ];
                }
            }
        }

        return $myfriend;
    }

    // 添加好友
    public function add_myfriend($fuid, $tuid) {

        if ($fuid == $tuid) {
            return;
        } elseif (!$fuid) {
            return;
        } elseif (!$tuid) {
            return;
        } elseif (strlen($fuid) > 12) {
            return; // 自己是游客时不进入好友关系
        }

        $gx = $this->table('app_sms_gx')->where('from_uid', $fuid)->where('to_uid', $tuid)->where('from_uid', $fuid)->getRow();
        if ($gx) {
            // 已经是关系了
            $this->table('app_sms_gx')->update($gx['id'], [
                'inputtime' => SYS_TIME
            ]);
        } else {
            // 添加新增
            $this->table('app_sms_gx')->insert([
                'from_uid' => $fuid,
                'to_uid' => $tuid,
                'inputtime' => SYS_TIME
            ]);
        }
    }

    // 发送消息
    public function send($fuid, $tuid, $content) {

        if ($fuid == $tuid) {
            return dr_return_data(0, '不能对自己发送');
        } elseif (!$fuid || !$tuid) {
            return dr_return_data(0, '参数不对');
        } elseif (!strlen(trim($content))) {
            return dr_return_data(0, '内容不能为空');
        }

        $member = dr_member_info($fuid);
        $app = \Phpcmf\Service::M('app')->get_config('sms');
        if (!$app['guest']) {
            if (!$this->uid) {
                return dr_return_data(0, '游客不能发送消息');
            } elseif (!$member) {
                return dr_return_data(0, '游客不能发送消息');
            }
        }

        if ($member) {
            // 发件人信息
            if ($app['fasong']) {
                $value = [];
                foreach ($app['fasong'] as $i => $t) {
                    if (dr_in_array($i, $member['groupid'])) {
                        $value[] = $t;
                    }
                }
                if ($value && is_array($value)) {
                    $fs = max($value);
                    if ($fs) {
                        if ($app['dw'] == 1) {
                            // 周
                            $time1 = mktime(0,0,0,date('m'),date('d')-date('N')+1,date('y'));
                            $time2 = mktime(23,59,59,date('m'),date('d')-date('N')+7,date('Y'));
                            $name = '本周';
                        } elseif ($app['dw'] == 2) {
                            // 月
                            $time1 = mktime(0,0,0,date('m'),1,date('Y'));
                            $time2 = mktime(23,59,59,date('m'),date('t'),date('Y'));
                            $name = '本月';
                        } else {
                            // 天
                            $time1 = strtotime(date('Y-m-d 00:00:00',time()));
                            $time2 = strtotime(date('Y-m-d 23:59:59',time()));
                            $name = '今日';
                        }
                        $ct = \Phpcmf\Service::M()->table('app_sms_content')->where('from_uid', (int)$fuid)->where('inputtime between '.$time1.' and '.$time2)->counts();
                        if ($ct >= $fs) {
                            return dr_return_data(0, $name.'发送数量已达到上限（'.$fs.'）');
                        }
                    }
                }
            }
        }

        // 添加新增
        $this->table('app_sms_content')->insert([
            'from_uid' => $fuid,
            'to_uid' => $tuid,
            'content' => $content,
            'is_read' => 0,
            'inputip' => \Phpcmf\Service::L('input')->ip_address(),
            'inputtime' => SYS_TIME,
        ]);

        $this->add_myfriend($tuid, $fuid);
        $this->add_myfriend($fuid, $tuid);

        if (strlen($tuid) < 12) {
            $new = $this->table('app_sms_new')->where('uid', (int)$tuid)->getRow();
            $nums = $this->table('app_sms_content')->where('to_uid', (int)$tuid)->where('is_read', 0)->counts();;
            if ($new) {
                $this->table('app_sms_new')->update($new['id'], [
                    'nums' => $nums,
                ]);
            } else {
                $this->table('app_sms_new')->insert([
                    'uid' => $tuid,
                    'nums' => $nums,
                    'sendtime' => 0,
                    'inputtime' => SYS_TIME,
                ]);
            }
        }

        return dr_return_data(1, '发送成功');
    }

    // 新消息
    public function new_msg($uid) {

        if (!$uid) {
            return [];
        }

        $new = $this->table('app_sms_content')->where('to_uid', $uid)->where('is_read', 0)->order_by('inputtime desc')->getAll();
        if (!$new) {
            return [];
        }

        $rt = $ids = [];
        foreach ($new as $t) {
            $user = $this->myinfo($t['from_uid']);
            $rt[] = [
                'id' => $user['id'],
                'uid' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'content' => $t['content'],
                'inputtime' => $t['inputtime'],
            ];
            $ids[] = $t['id'];
        }

        $this->db->table('app_sms_content')->whereIn('id', $ids)->update([
            'is_read' => 1,
        ]);

        $this->db->table('app_sms_new')->where('uid', $uid)->update([
            'nums' => 0,
            'sendtime' => 0,
        ]);

        return $rt;
    }

    // 历史记录
    public function log($from_uid, $to_uid, $max = 0) {

        if (!$from_uid || !$to_uid) {
            return [];
        }

        $log = $this->table('app_sms_content')
            ->where('((to_uid = "'.$to_uid.'" and from_uid="'.$from_uid.'") or to_uid = "'.$from_uid.'" and from_uid="'.$to_uid.'")')
            ->order_by('inputtime desc')
            ->getAll(50);
        if (!$log) {
            return [];
        }

        $rt = [];
        foreach ($log as $t) {
            $user = $this->myinfo($t['from_uid']);
            $rt[] = [
                'me' => $user['id'] == $from_uid,
                'id' => $user['id'],
                'uid' => $user['id'],
                'name' => $user['name'],
                'avatar' => $user['avatar'],
                'content' => $t['content'],
                'inputtime' => $t['inputtime'],
            ];
        }

        return $rt;
    }

    public function get_nums($uid) {
        return $this->table('app_sms_content')->where('to_uid', $uid)->where('is_read', 0)->counts();
    }

    // 执行任务
    public function run_cron() {

        return;
    }
    
}