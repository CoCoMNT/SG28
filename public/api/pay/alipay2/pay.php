<?php

/**
 * 发起接口
 */

$alipy = array (	
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

//商户订单号，商户网站订单系统中唯一订单号，必填
$out_trade_no = $sn;

//订单名称，必填
$subject = $data['title'];

//付款金额，必填
$total_amount = abs($data['value']);

//商品描述，可空
$body = "";
 
if (\Phpcmf\Service::IS_MOBILE_USER() && $config['wap']) {
	require_once dirname(__FILE__).'/wappay/service/AlipayTradeService.php';
	require_once dirname(__FILE__).'/aop/request/AlipayTradeWapPayRequest.php';
	require_once dirname(__FILE__).'/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
	
	
	 //超时时间
    $timeout_express="1m";

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new AlipayTradeService($alipy);

    $result=$payResponse->wapPay($payRequestBuilder,$alipy['return_url'],$alipy['notify_url']);
	

    return ;
} else {
	require_once dirname(__FILE__).'/pagepay/service/AlipayTradeService.php';
	require_once dirname(__FILE__).'/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

	//构造参数
	$payRequestBuilder = new AlipayTradePagePayContentBuilder();
	$payRequestBuilder->setBody($body);
	$payRequestBuilder->setSubject($subject);
	$payRequestBuilder->setTotalAmount($total_amount);
	$payRequestBuilder->setOutTradeNo($out_trade_no);

	$aop = new AlipayTradeService($alipy);

	/**
	 * pagePay 电脑网站支付请求
	 * @param $builder 业务参数，使用buildmodel中的对象生成。
	 * @param $return_url 同步跳转地址，公网可以访问
	 * @param $notify_url 异步通知地址，公网可以访问
	 * @return $response 支付宝返回的信息
		*/
	$response = $aop->pagePay($payRequestBuilder, $alipy['return_url'],$alipy['notify_url']);

	//输出表单
    $return = dr_return_data(1, 'url', $response);
}

