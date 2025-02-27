<?php namespace Phpcmf\Controllers\Member;

// 邀请注册功能
class Home extends \Phpcmf\Table {


    public function index() {

        if (!dr_is_app('yaoqing')) {
            $this->_msg(0, dr_lang('邀请注册插件未安装'));
        }

        $this->_init([
            'table' => 'app_yaoqing_user',
            'order_by' => 'inputtime desc',
            'date_field' => 'inputtime',
            'where_list' => 'puid='.$this->uid,
        ]);
        $this->_List();

        $rt = \Phpcmf\Service::M('yq', 'yaoqing')->yaoqing_rule($this->uid);
        if (!$rt['code']) {
            $this->_msg(0, $rt['msg']);
        }

        \Phpcmf\Service::V()->assign([
            'tid' => 'index',
            'yaoqing' => $rt['data'],
            'money_total' => \Phpcmf\Service::M('yq', 'yaoqing')->money($this->uid),
        ]);
        \Phpcmf\Service::V()->display('yaoqing_user.html');
    }

    public function log() {

        if (!dr_is_app('yaoqing')) {
            $this->_msg(0, dr_lang('邀请注册插件未安装'));
        }

        $this->_init([
            'table' => 'app_yaoqing_log',
            'order_by' => 'inputtime desc',
            'date_field' => 'inputtime',
            'where_list' => 'puid='.$this->uid,
        ]);
        $this->_List();

        $rt = \Phpcmf\Service::M('yq', 'yaoqing')->yaoqing_rule($this->uid);
        if (!$rt['code']) {
            $this->_msg(0, $rt['msg']);
        }



        \Phpcmf\Service::V()->assign([
            'tid' => 'log',
            'yaoqing' => $rt['data'],
            'money_total' => \Phpcmf\Service::M('yq', 'yaoqing')->money($this->uid),
        ]);
        \Phpcmf\Service::V()->display('yaoqing_log.html');
    }

}