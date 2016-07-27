<?php
require_once "Sdks/wxpay/lib/WxPay.Api.php";
require_once "Sdks/wxpay/lib/WxPay.JsApiPay.php";
require_once 'Sdks/wxpay/lib/log.php';
$notify_url = (string)new Suco_Helper_Url('module=default&controller=cart&action=wxnotify').'/';
$win_url = (string)new Suco_Helper_Url('module=usercp&controller=order&action=list').'/?t=shiped';
parse_str($this->params);
$amount = $this->amount*100;
$tools = new JsApiPay();
$openId = $tools->GetOpenid();
$input = new WxPayUnifiedOrder();

$input->SetBody($subject.'【'.M('Setting')->sitename.'】');
$input->SetAttach($subject.'【'.M('Setting')->sitename.'】');
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($amount);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($subject.'【'.M('Setting')->sitename.'】');
$input->SetNotify_url('http://zzw.hzboc.com/cart/wxnotify/');
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);

$jsApiParameters = $tools->GetJsApiParameters($order);

$editAddress = $tools->GetEditAddressParameters();
?>
<html>

<head>
	<?php include_once VIEWS.'inc/head.php'; ?>
	<script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok" ) {
						window.location='<?php echo $win_url?>';
					}
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', jsApiCall);
					document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				}
			}else{
				jsApiCall();
			}
		}
		//获取共享地址
		function editAddress() {
		}
		window.onload = function(){
			if (typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', editAddress, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', editAddress);
					document.attachEvent('onWeixinJSBridgeReady', editAddress);
				}
			}else{
				editAddress();
			}
		};
	</script>
</head>
<body>
<br/>
<font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $amount/100?></span>元</b></font><br/><br/>
<div align="center">
	<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
</div>
</body>

</html>