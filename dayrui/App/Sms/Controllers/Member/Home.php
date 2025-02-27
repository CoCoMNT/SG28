<?php namespace Phpcmf\Controllers\Member;

class Home extends \Phpcmf\Table
{

    public function index() {

        // 初始化数据表
        $this->_init([
            'table' => 'app_sms_gx',
            'order_by' => 'inputtime desc',
            'date_field' => 'inputtime',
            //'where_list' => 'from_uid in (select from_uid from `'.\Phpcmf\Service::M()->dbprefix('app_sms_content').'` where to_uid='.$this->uid.') and from_uid='.$this->uid,
            'where_list' => 'from_uid='.$this->uid,
        ]);


        list($tpl, $data) = $this->_List();
        $list = [];
        if ($data['list']) {
            foreach ($data['list'] as $t) {
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
                $user = dr_member_info($t['to_uid']);
                if ($user) {
                    $t['name'] = $user['name'] ? $user['name'] : $user['username'];
                    $t['avatar'] = dr_avatar($user['id']);
                } else {
                    $t['name'] = '游客';
                    $t['avatar'] = dr_avatar(0);
                }
                $list[] = $t;
            }
        }

        \Phpcmf\Service::V()->assign([
            'list' => $list,
        ]);
        \Phpcmf\Service::V()->display('sms_list.html');
    }


    // 聊天详情
    public function show() {

        $uid = \Phpcmf\Service::L('input')->get('uid');
        $to = \Phpcmf\Service::M('sms', 'sms')->myinfo($uid);

        if (IS_POST) {
            $content = \Phpcmf\Service::L('input')->post('content');
            if (!$content) {
                $this->_json(0, '内容不能为空');
            }
            $rt = \Phpcmf\Service::M('sms', 'sms')->send($this->uid, $to['id'], $content);
            if (!$rt['code']) {
                $this->_json(0, $rt['msg']);
            }
            $this->_json(1, '发送成功', [
                'url' => dr_member_url(APP_DIR.'/home/show', ['uid' => $uid])
            ]);
            exit;
        }

        $this->_init([
            'table' => 'app_sms_content',
            'order_by' => 'inputtime desc',
            'where_list' => '((to_uid = '.$this->uid.' and from_uid="'.$uid.'") or to_uid = "'.$uid.'" and from_uid='.$this->uid.')',
        ]);

        list($tpl, $data) = $this->_List();
        $list = [];
        if ($data['list']) {
            foreach ($data['list'] as $t) {
                $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($t['from_uid']);
                $list[] = [
                    'me' => $user['id'] == $this->uid,
                    'id' => $user['id'],
                    'uid' => $user['id'],
                    'name' => $user['name'],
                    'avatar' => $user['avatar'],
                    'content' => $t['content'],
                    'inputtime' => $t['inputtime'],
                ];
            }
        }

        \Phpcmf\Service::M()->db->table('app_sms_content')
            ->where('to_uid = "'.$this->uid.'" and from_uid="'.$uid.'"')->update(['is_read' => 1]);

        \Phpcmf\Service::V()->assign([
            'to' => $to,
            'list' => $list ? array_reverse($list) : [],
            'reply_url' => dr_member_url(APP_DIR.'/home/index')
        ]);
        \Phpcmf\Service::V()->display('sms_show.html');
    }

}
