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
			if($_POST['payment'] == 'wxpay') {
				$this->redirect('/cart/payjsapi/?amount='.$_POST['amount'].'&params='.base64_encode(
						http_build_query(
							array(
								'user_id' => $this->user->id,
								'trade_no' => $prefix.$this->user->id,
								'subject' => '帐户充值',
								)
						)
					).'&return_url='.$_POST['return_url'].'&type='.$_POST['type']
				);
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

	public function doCredit()
	{
		$select = M('User_Credit')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->t) {
			$select->where('type = ?', $this->_request->t);
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('views/freerecord.php');
	}
}