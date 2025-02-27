<?php namespace Phpcmf\Controllers;

class Api extends \Phpcmf\Common
{

    public function my() {

        $uid = 0;
        $list = [];

        // 我自己的信息
        $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);
        if ($my['uid']) {
            $list = \Phpcmf\Service::M()->table('app_sms_gx')
                ->where('from_uid', $this->uid)
                ->order_by('inputtime desc')->getAll(30);
            if (!$list) {
                $this->_json(0, '没有好用关系');
            }
            foreach ($list as $i => $t) {
                $row = \Phpcmf\Service::M()->table('app_sms_content')
                    ->where('to_uid', $this->uid)
                    ->where('from_uid', $t['to_uid'])
                    ->order_by('inputtime desc')->getRow();
                if ($row) {
                    $t['is_read'] = $row['is_read'];
                    $t['content'] = $row['content'];
                } else {
                    $t['is_read'] = '1';
                    $t['content'] = '无消息';
                }
                $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($t['to_uid']);
                $t['name'] = $user['name'];
                $t['avatar'] = $user['avatar'];
                $t['from_uid'] = $user['id'];
                $list[$i] = $t;
            }
        } else {
            // 若有对方
            $uid = dr_safe_filename(\Phpcmf\Service::L('input')->get('uid'));
            if ($uid) {
                $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($uid);
                $t = [
                    'from_uid' => $uid,
                    'to_uid' => $user['id'],
                    'name' => $user['name'],
                    'is_read' => 1,
                    'content' => '无消息',
                    'avatar' => $user['avatar'],
                    'inputtime' => 0,
                ];
                $row = \Phpcmf\Service::M()->table('app_sms_content')
                    ->where('to_uid', $my['id'])
                    ->where('from_uid', $user['id'])
                    ->order_by('inputtime desc')->getRow();
                if ($row) {
                    $t['is_read'] = $row['is_read'];
                    $t['content'] = $row['content'];
                    $t['inputtime'] = $row['inputtime'];
                } else {
                    $t['is_read'] = '1';
                    $t['content'] = '无消息';
                }

                $list = [
                    $t
                ];
            }
        }

        ob_start();
        \Phpcmf\Service::V()->assign([
            'uid' => $uid,
            'list' => $list,
        ]);
        $file = 'im/my.html';
        if (!is_file(\Phpcmf\Service::V()->get_dir().$file)) {
            \Phpcmf\Service::V()->admin();
        }
        \Phpcmf\Service::V()->display($file);
        $html = ob_get_contents();
        ob_clean();

        $this->_json(1, $html);
    }

    // 聊天对话框
    public function show() {

        $uid = dr_safe_replace($_GET['uid']);
        $to = \Phpcmf\Service::M('sms', 'sms')->myinfo($uid);

        ob_start();
        \Phpcmf\Service::V()->assign([
            'to' => $to,
        ]);
        if ($uid) {
            $file = 'im/chat_show.html';
        } else {
            $file = 'im/chat_null.html';
        }
        if (!is_file(\Phpcmf\Service::V()->get_dir().$file)) {
            \Phpcmf\Service::V()->admin();
        }
        \Phpcmf\Service::V()->display($file);
        $html = ob_get_contents();
        ob_clean();

        $this->_json(1, $html);
    }

    // 聊天对话框信息列表
    public function msg() {

        $uid = dr_safe_replace($_GET['uid']);
        $list = [];

        if ($uid) {
            // 我自己的信息
            $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);
            $data = \Phpcmf\Service::M()->table('app_sms_content')
                ->where('((to_uid = "'.$my['id'].'" and from_uid="'.$uid.'") or to_uid = "'.$uid.'" and from_uid="'.$my['id'].'")')
                ->order_by('inputtime desc')->getAll(30);
            if ($data) {
                $data = array_reverse($data);
                foreach ($data as $t) {
                    $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($t['from_uid']);
                    $now = [
                        'me' => $user['id'] == $my['id'],
                        'id' => $t['id'],
                        'uid' => $user['id'],
                        'name' => $user['name'],
                        'avatar' => $user['avatar'],
                        'content' => $t['content'],
                        'inputtime' => $t['inputtime'],
                    ];
                    ob_start();
                    \Phpcmf\Service::V()->assign([
                        't' => $now,
                    ]);
                    $file = 'im/chat_msg.html';
                    if (!is_file(\Phpcmf\Service::V()->get_dir().$file)) {
                        \Phpcmf\Service::V()->admin();
                    }
                    \Phpcmf\Service::V()->display($file);
                    $html = ob_get_contents();
                    ob_clean();
                    $list[] = [
                        'id' => $t['id'],
                        'msg' => $html,
                        'play' => !$now['me'] && !$t['is_read'],
                    ];
                }
                \Phpcmf\Service::M()->db->table('app_sms_content')
                    ->where('to_uid = "'.$my['id'].'" and from_uid="'.$uid.'"')->update(['is_read' => 1]);
            }

            $nums = \Phpcmf\Service::M()->table('app_sms_content')->where('to_uid', $my['id'])->where('from_uid<>"'.$uid.'"')->where('is_read', 0)->counts();
            $this->_json(1, $nums, $list);
        } else {
            $this->_json(0, '');
        }
    }


    public function send() {

        $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);
        $uid = dr_safe_replace($_GET['uid']);
        if ($my['id'] == $uid) {
            $this->_json(0, '不能对自己发送');
        }

        $img = \Phpcmf\Service::L('input')->post('img');
        $content = \Phpcmf\Service::L('input')->post('content');
        if (strpos($content, '[img]') !== false) {
            if ($img && preg_match('/^(data:\s*image\/(\w+);base64,)/i', $img, $result)) {
                $src = base64_decode(str_replace($result[1], '', $img));
                if (strlen($src) > 30000000) {
                    $this->_json(0, dr_lang('图片太大了'));
                }
                $url = 'sms/'.date('ym').'/'.substr(md5(SYS_TIME.($img).uniqid()), rand(0, 20), 15).'.jpg';
                $rt = \Phpcmf\Service::L('upload')->base64_image([
                    'content' => $src,
                    'ext' => 'jpg',
                    'save_name' => 'sms',
                    'save_file' => SYS_UPLOAD_PATH.$url,
                ]);
                if (!$rt['code']) {
                    $this->_json(0, $rt['msg']);
                }
                $content = str_replace('[img]', '<img src="'.SYS_UPLOAD_URL.$url.'">', $content);
            } else {
                $content = str_replace('[img]', '', $content);
            }
        }
        $rt = \Phpcmf\Service::M('sms', 'sms')->send($my['id'], $uid, $content);
        if (!$rt['code']) {
            $this->_json(0, $rt['msg']);
        }

        $this->_json(1, '发送成功');
    }

    public function get_content() {

        $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);
        $rt = \Phpcmf\Service::M('sms', 'sms')->new_msg($my['id']);
        if ($rt) {
            $this->_json(1, 'ok', $rt);
        }

        $this->_json(0, 'null');
    }


    // 未读数量
    public function nums() {

        $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);
        $nums = \Phpcmf\Service::M('sms', 'sms')->get_nums($my['id']);
        if (!$nums) {
            $this->_json(0, '');
        }

        $this->_json(1, $nums);
    }


}
