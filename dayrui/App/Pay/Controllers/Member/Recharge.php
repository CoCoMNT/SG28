<?php namespace Phpcmf\Controllers\Member;

class Recharge extends \Phpcmf\Common {

    /**
     * 在线充值
     */
    public function index() {
        define('FC_PAY', 1);
        $value = max(floatval(\Phpcmf\Service::L('input')->get('value')), floatval($this->member_cache['pay']['min']));
        \Phpcmf\Service::V()->assign([
            'payfield' => dr_payform('recharge', $value ? $value : '', '', '', 1),
        ]);
        \Phpcmf\Service::V()->display('recharge_index.html');
    }

}
