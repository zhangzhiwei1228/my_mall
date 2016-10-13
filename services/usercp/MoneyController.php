<?php

class Usercp_MoneyController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$view = $this->_initView();
		$view->logs = M('User_Money')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page)
			->fetchRows();
		$view->render('views/moneybalance.php');
	}

	public function doRecharge()
	{
		if ($this->_request->isPost()) {
			$view = $this->_initView();
			$view->payments = M('Payment')->select()
				->where('is_enabled = 1')
				->order('rank ASC, id ASC')
				->fetchRows();
			$view->render('views/payway.php');
			die;
		}

		$view = $this->_initView();
		$view->render('views/webrecharge.php');
	}

	public function doWithdraw()
	{
		if ($this->_request->isPostOnce()) {
			$setting = M('Setting');
			if (!$_POST['bank_id']) {
				throw new App_Exception('请选择提现银行卡');
			}

			if (!$this->user->checkPayPass($_POST['password'])) {
				throw new App_Exception('支付密码不正确！');
			}

			if ($setting['withdraw_limit_min'] > 0 && $setting['withdraw_limit_min'] > $amount) {
				throw new App_Exception('申请失败，系统限制最少提现金额为'.$setting['withdraw_limit_min'].'元');
			}

			if ($setting['withdraw_limit_max'] > 0 && $setting['withdraw_limit_max'] < $amount) {
				throw new App_Exception('申请失败，系统限制最大提现金额为'.$setting['withdraw_limit_max'].'元');
			}

			$amount = $_POST['amount'];
			$fee = $amount * ($setting['withdraw_rate']/100) + $setting['withdraw_fee'];



			$bank = M('User_Bank')->getById((int)$_POST['bank_id']);
			$withdraw = $this->user->withdraw($amount, $fee, '', 
				'用户申请提现', $bank['account_name'], $bank->toArray());

			return $this->_notice(array(
				'title' => '申请已提交',
				'message' => '我们将在1~3个工作日内对您的申请进行处理',
				'links' => array(
					array('返回用户中心', 'controller=index')
				),
				'autoback' => array('自动返回上一页', 'controller=index'),
			), 'success');
		}

		$view = $this->_initView();
		$view->banks = M('User_Bank')->select()
			->where('user_id = ?', $this->user['id'])
			->fetchRows();
		$view->render('usercp/money/withdraw.php');
	}

	public function doPay()
	{
		if ($this->_request->isPost()) {
			switch ($_POST['type']) {
				case 'credit':
					$prefix = 'RCA-';
					break;
				case 'credit_happy':
					$prefix = 'RCB-';
					break;
				case 'credit_coin':
					$prefix = 'RCC-';
					break;
				case 'vip0_active':
					$prefix = 'VIP-';
					break;
				case 'vip1_active':
					$prefix = 'VIP1-';
					break;
				case 'vip2_active':
					$prefix = 'VIP2-';
					break;
				case 'vip3_active':
					$prefix = 'VIP3-';
					break;
				case 'vip4_active':
					$prefix = 'VIP4-';
					break;
			}

			$_POST['return_url'] = isset($_SESSION['awaiting_payment']) ? (string)new Suco_Helper_Url('module=usercp&controller=order&action=list').'?t=awaiting_payment' :$_POST['return_url'];
			if($_POST['payment'] == 'wxpay') {
				$this->redirect('action=payjsapi&amount='.$_POST['amount'].'&params='.base64_encode(http_build_query(array('user_id' => $this->user->id, 'trade_no' => $prefix.$this->user->id.'-'.time(), 'subject' => '帐户充值','return_url'=>$_POST['return_url']))));
				return false;
			}
			$payment = M('Payment')->factory($_POST['payment']);
			$payment->pay($_POST['amount'], http_build_query(array(
				'user_id' => $this->user->id,
				'trade_no' => $prefix.$this->user->id.'-'.time(),
				'subject' => '帐户充值',
			)), $_POST['return_url'],$_POST['type']);
			die;
		}
	}
	public function doPayjsapi()
	{
		$amount = $this->_request->amount;
		$params = base64_decode($this->_request->params);

		$view = $this->_initView();
		$view->amount = $amount;
		$view->params = $params;
		$view->render('views/shopping/jsapi.php');
	}
	public function doCredit()
	{
		$select = M('User_Credit')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->t ) {
			if( $this->_request->t != 'worth_gold' ) {
				$select->where('type = ?', $this->_request->t);
			} else {
				$select = M('Worthglod')->select()
					->where('uid = ?', $this->user['id'])
					->order('create_time DESC')
					->paginator(20, $this->_request->page);
			}
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->type = $this->_request->t;
		$view->render('views/freerecord.php');
	}
	//购买抵用金
	public function doPurchase() {
		$service_charge = M('Coltypes')->getById(15);
		$proportions = M('Proportion')->select()->where('type=16')->fetchRows()->toArray();
		foreach($proportions as &$data) {
			$left_name = M('Coltypes')->select('name')->where('id='.$data['left_id'])->fetchRow()->toArray();
			$right_name = M('Coltypes')->select('name')->where('id='.$data['right_id'])->fetchRow()->toArray();
			$type_name = M('Coltypes')->select('name')->where('id='.$data['type'])->fetchRow()->toArray();
			$data['left_name'] = $left_name['name'];
			$data['right_name'] = $right_name['name'];
			$data['type_name'] = $type_name['name'];
		}
		if ($this->_request->isPost()) {
			$post = $this->_request->getPosts();
			$consume = $post['consume'];//消费金额
			if(!$consume) {
				throw new App_Exception('请填写消费金额');
			}
			$discount = $post['discount']/100;//折扣
			if(!$discount) {
				throw new App_Exception('请填写折扣金额');
			}
			$price_type = $post['price_type'];//支付方式
			if(!$price_type) {
				throw new App_Exception('请选择支付方式');
			}
			$privilege = round($consume - $consume*$discount,2);//优惠
			$service = round($privilege * $service_charge['price'],2);//服务费
			$proportion = M('Proportion')->select()->where('id='.(int)$price_type)->fetchRow()->toArray();
			$payment = round(($consume - $consume*$discount)*($proportion['l_digital']/$proportion['r_digital']));//支付的金额
			$pay_name = M('Coltypes')->select('name,english')->where('id='.$proportion['left_id'])->fetchRow()->toArray();
			$right = M('Coltypes')->select('name')->where('id='.$proportion['right_id'])->fetchRow()->toArray();
			if(!$privilege || !$service || !$payment) {
				throw new App_Exception('计算错误，请重新计算提交');
			}

			try {
				if ($pay_name['english'] == 'credit' && $this->user['credit'] < $payment) {
					throw new App_Exception("支付失败，您的免费积分不足", 101);
				}
				if ($pay_name['english'] == 'credit_happy' && $this->user['credit_happy'] < $payment) {
					throw new App_Exception("支付失败，您的快乐积分不足", 102);
				}
				if ($pay_name['english'] == 'credit_coin' && $this->user['credit_coin'] < $payment) {
					throw new App_Exception("支付失败，您的积分币不足", 103);
				}
			} catch(App_Exception $e) {
				$_SESSION['awaiting_payment'] = 'awaiting_payment';
				$view = $this->_initView();
				$view->message = $e->getMessage();
				$view->code = $e->getCode();
				$view->render('views/shopping/no_enough.php');
				return;
			}


			$extra['uid'] = $this->user->id;
			$extra['privilege'] = $privilege;
			$extra['service_charge'] = $service;
			$extra['discount'] = $discount;
			$extra['order_no'] = $this->doOrderNo();
			$extra['code'] = $this->doRandStr();
			$pay_json['payment'] = $payment;
			$pay_json['pay_name'] = $pay_name['name'];
			$pay_json['pay_desc'] = $proportion['l_digital'].$pay_name['name'].'='.$proportion['r_digital'].$right['name'];
			$extra['pay_json'] = json_encode($pay_json);
			$glod_id = M('Worthglod')->insert(array_merge($post,$extra));
			$pay_data['type'] = $pay_name['english'];
			$pay_data['amount'] = $payment;
			$pay_data['return_url'] = '/usercp/';
			$pay_data['glod_id'] = $glod_id;
			$pay_data['pay_name'] = $pay_name['name'];
			$pay_data['privilege'] = $privilege;
			$this->doPayPurchase($pay_data);
			//$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=default');
		}


		$view = $this->_initView();
		$view->data = $proportions;
		$view->service_charge = $service_charge;
		$view->render('views/new_text/purchase.php');
	}
	public function doPayPurchase($data) {
		$worthglod = M('Worthglod')->getById((int)$data['glod_id']);
		switch ($data['type']) {
			case 'credit':
				$this->user->credit($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
				break;
			case 'credit_happy':
				$this->user->creditHappy($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
				break;
			case 'credit_coin':
				$this->user->creditCoin($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
				break;
			case 'vouchers'://抵用券
				$this->user->vouchers($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
				break;
			case 'cash'://现金
				$this->user->cash($data['amount']*-1, '购买抵用金【TS-'.$data['glod_id'].'】');
				break;
		}
		$worthglod->status = 2;
		$worthglod->pay_time = time();
		$worthglod->save();

		$this->user->worthGold($data['privilege'],'使用【'.$data['amount'].$data['pay_name'].'】购买【'.$data['privilege'].'抵用金】');
		$this->redirect('action=success&id='.$data['glod_id']);
		/*$view = $this->_initView();
		$view->payments = M('Payment')->select()
			->where('is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();
		$view->data = $data;
		$view->render('views/payway.php');*/
	}
	//购买抵用金成功
	public function doSuccess() {
		$worthglod = M('Worthglod')->select('code')->where('id='.(int)$this->_request->id.' and uid='.(int)$this->user->id)->fetchRow()->toArray();
		if(!$worthglod) {
			throw new App_Exception("查询错误", 102);
		}
		$view = $this->_initView();
		$view->code = $worthglod['code'];
		$view->render('views/new_text/success.php');
	}
	// 生成订单号 $str 前缀
	function doOrderNo($str=''){
		$order_no=date('Ymdhis',time()).str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		return $str.$order_no;
	}
	function doRandStr($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
		$chars_length = (strlen($chars) - 1);
		$string = $chars{rand(0, $chars_length)};
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			$r = $chars{rand(0, $chars_length)};
			if ($r != $string{$i - 1}) $string .=  $r;
		}
		return $string;
	}
}