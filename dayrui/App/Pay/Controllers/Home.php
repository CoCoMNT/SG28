<?php namespace Phpcmf\Controllers;

class Home extends \Phpcmf\Common
{

    // 付款
    public function index() {

        $id = (int)\Phpcmf\Service::L('input')->get('id');
        $data = \Phpcmf\Service::M()->table('member_paylog')->get($id);
        if (!$data) {
            $this->_msg(0, dr_lang('该账单不存在'));
        } elseif ($data['status'] == 1) {
            $this->_msg(0, dr_lang('该账单已被支付'));
        }

        // 付钱之前再次验证
        if ($data['mid'] && strpos($data['mid'], 'my-') !== false) {
            list($rname, $rid, $fid, $num, $sku) = explode('-', $data['mid']);
            if ($rname == 'my') {
                list($app, $class) = explode('_', $rid);
                $classFile = dr_get_app_dir($app).'Models/'.ucfirst($class).'.php';
                if (is_file($classFile)) {
                    $obj = \Phpcmf\Service::M($class, $app);
                    if (method_exists($obj, 'paylog_before')) {
                        $error = $obj->paylog_before($fid, $num, $sku, $data);
                        if ($error) {
                            $this->_msg(0, $error);
                        }
                    }
                }
            }
        }

        // 0元支付
        if (empty($data['value'])) {
            $rt = \Phpcmf\Service::M('Pay','pay')->paysuccess(
                \Phpcmf\Service::M('Pay','pay')->get_pay_sn($data),
                0
            );
            if (!$rt['code']) {
                $this->_msg(0, $rt['msg'], $rt['data']['url']);
            }
            dr_redirect(ROOT_URL.'index.php?s=api&c=pay&m=call&id='.$rt['code']);exit;
        }

        // 支付接口文件
        $apifile = ROOTPATH.'api/pay/'.$data['type'].'/pay.php';
        if (!is_file($apifile)) {
            $this->_msg(0, dr_lang('支付接口文件（%s）不存在', $data['type']));
        } elseif ($data['type'] != 'finecms' && (!isset($this->member_cache['payapi'][$data['type']]) || !$this->member_cache['payapi'][$data['type']])) {
            $this->_msg(0, dr_lang('支付接口（%s）未启用', $data['type']));
        }

        // 发起支付
        $rt = \Phpcmf\Service::M('pay', 'pay')->dopay($apifile, $data);
        if (!$rt['code']) {
            $this->_msg(0, $rt['msg'], $rt['data']['url']);
        } elseif (isset($rt['data']['rturl']) && strlen($rt['data']['rturl']) > 10) {
            $this->_msg(1, $rt['msg'], $rt['data']['rturl']);
        }

        // 请求模式下返回结果
        if (IS_API_HTTP || (\Phpcmf\Service::L('input')->get('is_ajax') || IS_API_HTTP || IS_AJAX)) {
            $this->_json(1, $rt['msg'], $rt['data']);
        }

        // 运行模式直接输出代码
        switch ($rt['msg']) {
            case 'html':
                exit($rt['data']);

            case 'url':
                dr_redirect($rt['data']);
                break;
        }

        $data['html'] = $rt['data'];
        if (SITE_IS_MOBILE && \Phpcmf\Service::IS_MOBILE_TPL()) {
            // 开启了移动端时，支付判断模板是否是移动端的
            \Phpcmf\Service::V()->init('mobile');
        }

        \Phpcmf\Service::V()->assign([
            'pay' => $data,
            'pay_name' => dr_pay_type_html($data['type']),
            'meta_title' => $data['title']
        ]);

        \Phpcmf\Service::V()->module('pay');
        if (!is_file(\Phpcmf\Service::V()->get_dir().'pay.html')) {
            \Phpcmf\Service::V()->module('api');
        }
        \Phpcmf\Service::V()->display('pay.html');exit;
    }

    /**
     * 支付接口js-ajax回调
     */
    public function ajax() {

        $id = (int)\Phpcmf\Service::L('input')->get('id');
        $data = \Phpcmf\Service::M()->table('member_paylog')->get($id);
        if (!$data) {
            $this->_jsonp(0, dr_lang('支付记录不存在'));
        } elseif ($data['status']) {
            $this->_jsonp(1, dr_lang('已经支付完成'));
        }

        // 调用接口
        $apifile = ROOTPATH.'api/pay/'.$data['type'].'/notify_js.php';
        if (!is_file($apifile)) {
            $this->_jsonp(0, dr_lang('支付接口文件不存在'));
        }

        $return = [];
        $result = dr_string2array($data['result']);

        // 接口配置参数
        $config = $this->member_cache['payapi'][$data['type']];

        require $apifile;

        $this->_jsonp($return['code'], $return['msg']);
        exit;
    }

    /**
     * 支付接口返回
     */
    public function call() {

        $id = (int)\Phpcmf\Service::L('input')->get('id');
        if (!$id) {
            $this->_msg(0, dr_lang('支付ID号不存在'));
        }

        $data = \Phpcmf\Service::M()->table('member_paylog')->get($id);
        if (!$data) {
            $this->_msg(0, dr_lang('支付记录[%s]不存在', $id));
        } elseif (!$data['status']) {
            $this->_msg(0, dr_lang('支付记录[%s]未完成支付', $id));
        }

        // 支付回调钩子
        \Phpcmf\Hooks::trigger('pay_call', $data);

        if (SITE_IS_MOBILE && \Phpcmf\Service::IS_MOBILE_TPL()) {
            // 开启了移动端时，支付判断模板是否是移动端的
            \Phpcmf\Service::V()->init('mobile');
        }

        // 获取支付回调地址
        $url = \Phpcmf\Service::M('pay')->paycall_url($data);

        $this->_msg(1, dr_lang('支付成功'), $url);
    }

}
