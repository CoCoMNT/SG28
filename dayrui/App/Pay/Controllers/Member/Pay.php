<?php namespace Phpcmf\Controllers\Member;
/**
 * {{www.xunruicms.com}}
 * {{迅睿内容管理框架系统}}
 * 本文件是框架系统文件，二次开发时不可以修改本文件
 **/

class Pay extends \Phpcmf\Common
{

    /**
     * 提交支付账单
     */
    public function index() {

        if (IS_POST) {
            $pay = \Phpcmf\Service::L('input')->post('pay');
            $pay['uid'] = (int)$this->member['uid'];
            $pay['username'] = (string)$this->member['username'];
            $money = floatval($pay['money']);
            if (!$money) {
                $this->_msg(0, dr_lang('金额(%s)不正确', $money));
                exit;
            }
            $rt = \Phpcmf\Service::M('Pay')->post($pay);
            if (!$rt['code']) {
                $this->_msg(0, $rt['msg'], $rt['data']);
            }
            $url = PAY_URL.'index.php?s=pay&id='.$rt['code'];
            if (IS_API_HTTP || (\Phpcmf\Service::L('input')->get('is_ajax') || IS_API_HTTP || IS_AJAX)) {
                // 回调页面
                $this->_json($rt['code'], $url, $rt['data']);
            } else {
                // 跳转到支付页面，必须跳转到统一的主域名中付款
                dr_redirect($url, 'auto');
            }
            exit;
        } else {
            $this->_msg(0, dr_lang('POST请求错误'));
        }
    }

}
