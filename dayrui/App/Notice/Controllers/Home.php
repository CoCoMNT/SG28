<?php namespace Phpcmf\Controllers;

class Home extends \Phpcmf\App
{

    public function index() {

        if (!$this->uid) {
            $this->_json(0, '未登录');
        } elseif (\Phpcmf\Service::M()->table('member_notice')->where('uid', $this->uid)->where('isnew', 1)->counts()) {
            $this->_json(1, '有消息');
        } else {
            $this->_json(0, '没有消息');
        }
    }

}
