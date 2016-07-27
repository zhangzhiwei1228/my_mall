<?php

class Payment_Alipay extends Suco_Model implements Payment_Interface
{
	protected $_pid;
	protected $_config;
	protected $_sellerAccount;

	public function init()
	{
		$payment = M('Payment')->select()
			->where('code = ?', 'alipay')
			->fetchRow();
		
		parse_str($payment['setting']);
		$this->_pid = $payment['id'];
		$this->_sellerAccount = $seller_email;
		$this->_config = array(
			#合作身份者id，以2088开头的16位纯数字
			'partner' => $partner,

			#安全检验码，以数字和字母组成的32位字符
			'key' => $key,

			#签名方式 不需修改
			'sign_type' => strtoupper('MD5'),			
			
			#字符编码格式 目前支持 gbk 或 utf-8
			'input_charset' => strtolower('utf-8'),		
			
			#ca证书路径地址，用于curl中ssl校验
			#请保证cacert.pem文件在当前文件夹目录中
			'cacert' => LIB_DIR.'Sdks/alipay/cacert.pem',

			#访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
			'transport' => 'http'
		);
	}

	public function pay($amount, $params)
	{
		$this->init();
		require_once("Sdks/alipay/lib/alipay_submit.class.php");

		parse_str($params);
		//$show_url = (string)new Suco_Helper_Url('module=usercp&controller=order');
		$notifyUrl = (string)new Suco_Helper_Url('module=default&controller=callback&action=payment&t=alipay').'/';
		$returnUrl = $_SERVER['HTTP_REFERER'];
		//$returnUrl = $notifyUrl;

		$parameter = array(
			"service" => "create_direct_pay_by_user",
			"partner" => trim($this->_config['partner']),

			#支付类型(必填，不能修改)
			"payment_type"	=> 1,

			#服务器异步通知页面路径。
			#需http://格式的完整路径，不能加?id=123这类自定义参数
			"notify_url"	=> $notifyUrl,

			#页面跳转同步通知页面路径。
			#需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
			"return_url"	=> $returnUrl,

			#卖家支付宝帐户(必填)
			"seller_email"	=> $this->_sellerAccount,

			#商户订单号。商户网站订单系统中唯一订单号(必填)
			"out_trade_no"	=> $trade_no,
			//"out_trade_no"	=> time(),

			#订单名称(必填)
			"subject"	=> $subject.'【'.M('Setting')->sitename.'】',

			#付款金额(必填)
			"total_fee"	=> $amount,

			#订单描述
			"body"	=> $body,

			#商品展示地址
			#需以http://开头的完整路径，例如：http://www.xxx.com/myorder.html
			"show_url"	=> $show_url,

			#防钓鱼时间戳。若要使用请调用类文件submit中的query_timestamp函数
			"anti_phishing_key"	=> '',

			#客户端的IP地址
					#非局域网的外网IP地址，如：221.0.0.1
			"exter_invoke_ip"	=> '',
			"_input_charset"	=> trim(strtolower($this->_config['input_charset']))
		);

		if ($bankcode) {
			//构造要请求的参数数组，无需改动
			$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($this->_config['partner']),
				"seller_email" =>  $this->_sellerAccount,
				"payment_type"	=> 1,
				"notify_url"	=> $notifyUrl,
				"return_url"	=> $returnUrl,
				"out_trade_no"	=> $trade_no,
				"subject"	=> $subject.'【'.M('Setting')->sitename.'】',
				"total_fee"	=> $amount,
				"body"	=> $body,
				"paymethod"	=> 'bankPay',
				"defaultbank"	=> $bankcode,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower($this->_config['input_charset']))
			);
		}

		#建立请求
		$alipaySubmit = new AlipaySubmit($this->_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo '<style>#alipaysubmit { display:none; }</style>';
		echo '正在跳转到支付宝，请稍后......';
		echo $html_text;
	}

	public function callback()
	{
		$this->init();
		require_once("Sdks/alipay/lib/alipay_notify.class.php");

		#计算得出通知验证结果
		$q = $data = array_merge($_POST, $_GET);
		$alipayNotify = new AlipayNotify($this->_config);
		//$verify_result = $alipayNotify->verifyReturn();
		$verify_result = $alipayNotify->verifyNotify();
		$setting = M('Setting');
		
		//$verify_result = true;
		if($verify_result) { #验证成功
			//充值
			try {
				list($type, $code) = explode('-', trim($q['out_trade_no']));
				$voucher = 'ALI-'.$q['trade_no'];

				//滤重
				$recharge = M('User_Recharge')->select()
					->where('voucher = ? AND payment_id = ?', array($voucher, $this->_pid))
					->fetchRow();

				if ($recharge->exists()) {
					die('fail');
				}

				switch($type) {
					case 'TS': //支付订单
						if (!$code) {
							die('fail');
						}

						$order = M('Order')->getByCode($code);
						if ($order->exists() && $order->status == 1) {
							$order->buyer->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$order->pay();
							die('success');
						}
						break;
					case 'RC': //帐户充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							die('success');
						}
						break;
					case 'RCA': //免费积分充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '购买免费积分#'.$voucher
							)->commit();

							$point = $setting['credit_rate']*$q['total_fee'];
							$user->credit($point, '购买免费积分');
							die('success');
						}
						break;
					case 'RCB': //快乐积分充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '购买快乐积分#'.$voucher
							)->commit();

							$point = $setting['credit_happy_rate']*$q['total_fee'];
							$user->creditHappy($point, '购买快乐积分');
							die('success');
						}
						break;
					case 'RCC': //积分币充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '购买积分币#'.$voucher
							)->commit();

							$point = $setting['credit_coin_rate']*$q['total_fee'];
							$user->creditCoin($point, '购买积分币');
							die('success');
						}
						break;
					case 'VIP': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, 'VIP激活'
							)->commit();
							$user->is_vip = 1;
							$user->save();
							die('success');
						}
						break;
					case 'VIP1': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '升级一星分销商'
							)->commit();
							$user->is_vip = 2;
							$user->save();

							//赠送500免费积分
							$user->credit(500, '升级一星分销商，赠送免费积分');
							die('success');
						}
						break;
					case 'VIP2': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '升级二星分销商'
							)->commit();
							$user->is_vip = 3;
							$user->save();

							//赠送500免费积分
							$user->credit(500, '升级二星分销商，赠送免费积分');
							die('success');
						}
						break;
					case 'VIP3': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '升级三星分销商'
							)->commit();
							$user->is_vip = 4;
							$user->save();

							//赠送500免费积分
							$user->credit(500, '升级三星分销商，赠送免费积分');
							die('success');
						}
						break;
					case 'VIP4': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$q['total_fee'], 0, $voucher, '支付宝充值', $this->_pid
							)->commit();
							$user->expend(
								'pay', $q['total_fee'], $voucher, '升级四星分销商'
							)->commit();
							$user->is_vip = 5;
							$user->save();

							//赠送500免费积分
							$user->credit(500, '升级四星分销商，赠送免费积分');
							die('success');
						}
						break;
				}
			} catch(Suco_Exception $e) {
				//echo $e->getMessage();
				//echo Suco_Db::dump();
				die('fail');
			}
		}
		else {
			die('fail');
		}
	}
}