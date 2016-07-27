<?php

class Payment_Wxpay extends Suco_Model implements Payment_Interface
{
	protected $_config;

	public function init()
	{
		$payment = M('Payment')->select()
			->where('code = ?', 'wxpay')
			->fetchRow();
		
		// parse_str($payment['setting']);
		// $this->_sellerAccount = $seller_email;
		// $this->_config = array(
		// 	#合作身份者id，以2088开头的16位纯数字
		// 	'partner' => $partner,

		// 	#安全检验码，以数字和字母组成的32位字符
		// 	'key' => $key,

		// 	#签名方式 不需修改
		// 	'sign_type' => strtoupper('MD5'),			
			
		// 	#字符编码格式 目前支持 gbk 或 utf-8
		// 	'input_charset' => strtolower('utf-8'),		
			
		// 	#ca证书路径地址，用于curl中ssl校验
		// 	#请保证cacert.pem文件在当前文件夹目录中
		// 	'cacert' => getcwd().'\\cacert.pem',

		// 	#访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		// 	'transport' => 'http'
		// );
	}

	public function pay($amount, $params,$return_url=false,$type=false)
	{
		require_once "Sdks/wxpay/lib/WxPay.Api.php";
		require_once "Sdks/wxpay/lib/WxPay.NativePay.php";
		require_once 'Sdks/wxpay/lib/log.php';

		$notify = new NativePay();
		$url1 = $notify->GetPrePayUrl("123456789");

		parse_str($params);

		//echo number_format($amount,2,'');
		$amount = ltrim(number_format($amount,2,"",""),'0');

		//var_dump($amount); die;

		$input = new WxPayUnifiedOrder();
		$input->SetBody($subject);
		//$input->SetAttach("test");
		$input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetTotal_fee($amount);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($trade_no);
		$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($trade_no);
		$result = $notify->GetPayUrl($input);
		$url2 = $result["code_url"];

		//echo $url2; die;
		//http://paysdk.weixin.qq.com/example
		echo '<img alt="模式一扫码支付" src="http://paysdk.weixin.qq.com/example/qrcode.php?data='.urlencode($url2).'" style="width:150px;height:150px;"/>';
	}

	public function callback()
	{
		
	}
}