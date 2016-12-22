<?php
require_once "alipay.config.php";
require_once "lib/alipay_notify.class.php";

/*logfile("'阿里支付 in pay_notify:", 'alipay');
logfile(print_r($_POST, 1), 'alipay');*/
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();
//logfile(print_r($alipayNotify, 1), 'alipay');
print_r('verify_result==');

print_r($verify_result);

if ($verify_result) {
	//验证成功
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];
	//支付宝交易号
	$trade_no = $_POST['trade_no'];
	//交易状态
	$trade_status = $_POST['trade_status'];
	if ($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
		$CI =& get_instance();
		$res = $CI->do_pay_ali($_POST);
	}

  // 支付更新结果
  if (isset($res) && $res) {
    //logfile("'阿里支付 in pay_notify of alipayNotify success.", 'alipay');
  	echo "success"; //请不要修改或删除
  } else {
  	//验证失败
  	echo "fail1";
  }

} else {
	//验证失败
	echo "fail2";
}
?>
