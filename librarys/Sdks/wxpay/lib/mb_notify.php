<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once "WxPay.Api.php";
require_once "WxPay.Notify.php";
require_once 'log.php';
//初始化日志
$logHandler= new CLogFileHandler(LOG_DIR.date('Y-m-d-H-i-s').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$q = $data = array_merge($_POST, $_GET);
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")

		{
			$setting = M('Setting');
			try {
				list($type, $code ,$trade_no) = explode('-', trim($result['out_trade_no']));
				$voucher = $trade_no ? 'ALI-'.$trade_no : 'ALI-'.$code;
                if(isset($_SESSION['awaiting_payment'])) {
                    unset($_SESSION['awaiting_payment']);
                }
				//滤重
				$recharge = M('User_Recharge')->select()
					->where('voucher = ? AND payment_id = ?', array($voucher, 2))
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
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$order->pay();
							$order->adduserarea();
							die('success');
						}
						break;
					case 'RC': //帐户充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							die('success');
						}
						break;
					case 'RCA': //免费积分充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '购买免费积分#'.$voucher
							)->commit();
							$point = ($user['role'] == 'seller') ? $setting['credit_rate_agent']*($result['cash_fee']/100) : $setting['credit_rate']*($result['cash_fee']/100);
							/*$point = $setting['credit_rate']*($result['cash_fee']/100);*/
							$user->credit($point, '购买免费积分');
							die('success');
						}
						break;
					case 'RCB': //快乐积分充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '购买快乐积分#'.$voucher
							)->commit();

							$point = $setting['credit_happy_rate']*($result['cash_fee']/100);
							$user->creditHappy($point, '购买快乐积分');
							die('success');
						}
						break;
					case 'RCC': //积分币充值
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '购买积分币#'.$voucher
							)->commit();

							$point = $setting['credit_coin_rate']*($result['cash_fee']/100);
							$user->creditCoin($point, '购买积分币');
							die('success');
						}
						break;
					case 'VIP': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, 'VIP激活'
							)->commit();
							$user->is_vip = 1;
							$user->save();
							M('User')->activateAddCredit((int)$code);
							die('success');
						}
						break;
					case 'VIP1': //VIP激活
						$user = M('User')->getById($code);

						if ($user->exists()) {
							$user->recharge(
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '升级一星分销商'
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
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '升级二星分销商'
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
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '升级三星分销商'
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
								$result['cash_fee']/100, 0, $voucher, '微信充值', 2
							)->commit();
							$user->expend(
								'pay', $result['cash_fee']/100, $voucher, '升级四星分销商'
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
				Log::DEBUG("e:" . $e);
				die('fail');
			}
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("NotifyProcess[call back]:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);

