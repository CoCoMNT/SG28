<?php
/* *
 * 功能：支付宝页面跳转同步通知页面
 * 版本：2.0
 * 修改日期：2017-05-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 */

$alipay = array (	
	//应用ID,您的APPID。
	'app_id' => $config['id'],

	//商户私钥
	'merchant_private_key' => $config['key'],
	
	//异步通知地址
	'notify_url' => ROOT_URL."api/pay/".$data['type']."/notify_url.php",
	
	//同步跳转
	'return_url' => ROOT_URL."api/pay/".$data['type']."/return_url.php",

	//编码格式
	'charset' => "UTF-8",

	//签名方式
	'sign_type'=>"RSA2",

	//支付宝网关
	'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

	//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
	'alipay_public_key' => $config['key2'],
);

if (\Phpcmf\service::C()->_is_mobile() && $config['wap']) {
	// 手机

	require_once 'wappay/service/AlipayTradeService.php';


	$arr=$_GET;
	$alipaySevice = new AlipayTradeService($alipay); 
	$result = $alipaySevice->check($arr);

	/* 实际验证过程建议商户添加以下校验。
	1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
	2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
	3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
	4、验证app_id是否为该商户本身。
	*/
	if($result) {//验证成功
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代码
		
		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

		//商户订单号

		$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

		//支付宝交易号

		$trade_no = htmlspecialchars($_GET['trade_no']);
			
		$rt =  \Phpcmf\Service::M('Pay')->paysuccess($out_trade_no, $trade_no);
		 if (!$rt['code']) {
            $this->_msg(0, $rt['msg']);exit;
        }
	    // 跳转
	    dr_redirect(ROOT_URL.'index.php?s=api&c=pay&m=call&id='.$rt['code']);
	    exit;

		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else {
	    //验证失败
		$this->_msg(0, "wap验证失败");exit;
	}
} else {
	// pc

	require_once 'pagepay/service/AlipayTradeService.php';


	$arr=$_GET;
	$alipaySevice = new AlipayTradeService($alipay); 
	$result = $alipaySevice->check($arr);

	/* 实际验证过程建议商户添加以下校验。
	1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
	2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
	3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
	4、验证app_id是否为该商户本身。
	*/
	if($result) {//验证成功
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代码
		
		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

		//商户订单号
		$out_trade_no = htmlspecialchars($_GET['out_trade_no']);

		//支付宝交易号
		$trade_no = htmlspecialchars($_GET['trade_no']);

		$rt =  \Phpcmf\Service::M('Pay')->paysuccess($out_trade_no, $trade_no);
	    // 跳转
	    dr_redirect(ROOT_URL.'index.php?s=api&c=pay&m=call&id='.\Phpcmf\Service::M('Pay')->get_pay_id($out_trade_no));
	    exit;
			

		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else {
	    //验证失败
		$this->_msg(0, "验证失败");exit;
	}
}
?>