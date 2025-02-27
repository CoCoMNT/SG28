<?php namespace Phpcmf\Controllers;

class Home extends \Phpcmf\App
{
    // 邀请注册
    public function index() {

        // 存储邀请参数
        $uid = (int)\Phpcmf\Service::L('input')->get('uid');
        $rt = \Phpcmf\Service::M('yq', 'yaoqing')->yaoqing_rule($uid);
        if (!$rt['code']) {
            $this->_msg(0, $rt['msg']);
        }

        // 存储邀请状态
        $this->session()->set('app_yaoqing_uid', $uid);

        dr_redirect(dr_member_url('register/index'));exit;
    }

}
