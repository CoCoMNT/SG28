<?php namespace Phpcmf\Controllers;


// 交易下单
class Buy extends \Phpcmf\Common {

    public function index() {

        $id = (int)\Phpcmf\Service::L('input')->get('id');
		if (!$id) {
			$this->_msg(0, dr_lang('商品id参数不能为空'));
		}
		
        $fid = (int)\Phpcmf\Service::L('input')->get('fid');
        if (!$fid) {
			$this->_msg(0, dr_lang('支付字段fid参数不能为空'));
		}

        $num = max(1, (int)\Phpcmf\Service::L('input')->get('num'));
        $sku = dr_safe_replace(\Phpcmf\Service::L('input')->get('sku'), 'undefined');

        $field = $this->get_cache('table-field', $fid);
        if (!$field) {
			$this->_msg(0, dr_lang('支付字段[%s]不存在', $fid));
		}

        // 获取付款价格
        $rt = \Phpcmf\Service::M('pay', 'pay')->get_pay_info($id, $field, $num, $sku);
        if (isset($rt['code']) && !$rt['code']) {
			$this->_msg(0, $rt['msg']);	
		}

        // 挂钩点 购买商品之前
        \Phpcmf\Hooks::trigger('member_buy_before', $rt);

        \Phpcmf\Service::V()->assign($rt);
        \Phpcmf\Service::V()->assign([
            'num' => $rt['num'],
            'price' => $rt['price'],
            'total' => $rt['total'],
            'total_price' => $rt['total'],
            'payform' => dr_payform($rt['mid'], $rt['total'], $rt['title'].$rt['sku_string'], $rt['url']),
            'meta_title' => dr_lang('在线付款').SITE_SEOJOIN.SITE_NAME,
            'meta_keywords' => $this->get_cache('site', SITE_ID, 'config', 'SITE_KEYWORDS'),
            'meta_description' => $this->get_cache('site', SITE_ID, 'config', 'SITE_DESCRIPTION')
        ]);
        \Phpcmf\Service::V()->module('pay');
        if (!is_file(\Phpcmf\Service::V()->get_dir().'buy.html')) {
            \Phpcmf\Service::V()->module('api');
        }
        \Phpcmf\Service::V()->display('buy.html');
    }
}
