<?php

class Agent_CreditController extends Agent_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}
	
	public function doDefault()
	{
		$view = $this->_initView();
		$view->render('views/jifensteps.php');
	}

	public function doQueryUser()
	{
		echo M('User')->select('id, username, nickname, credit, credit_happy, credit_coin, balance')
			->where('username = ?', $this->_request->q)
			->limit(10)
			->fetchRows()
			->toJson();
	}

	public function doConfirm()
	{
		$account = M('User')->getById((int)$this->_request->uid);
		if (!$account->exists()) {
			throw new App_Exception('帐号不存在');
		}

		if ($this->_request->isPost()) {
			if (!$this->_checkCredit($_POST['credit'], $this->user['credit'])) {
				return false;
			}

			$view = $this->_initView();
			$view->account = $account;
			$view->render('views/jifen/jifenstep03.php');
			return;
		}

		$view = $this->_initView();
		$view->account = $account;
		$view->render('views/jifen/jifenstep02.php');
	}

	public function doPay()
	{
		if ($this->_request->success) {
			$account = M('User')->getById((int)$this->_request->uid);

			$view = $this->_initView();
			$view->account = $account;
			$view->render('views/jifen/jifenstep0302.php');
			return;
		}

		if ($this->_request->isPost()) {
			if (!$this->_checkCredit($_POST['credit'], $this->user['credit'])) {
				return false;
			}

			$account = M('User')->getById((int)$_POST['uid']);
			$this->user->credit($_POST['credit']*-1, '赠送会员【'.$account['nickname'].'】');
			$account->credit($_POST['credit'], '商家赠送【'.$this->user['nickname'].'】');

			$this->redirect('&success=1&uid='.$account['id'].'&pot='.$_POST['credit']);
			return;
		}
	}

	protected function _checkCredit($c1, $c2)
	{
		if ($c1 > $c2) {
			$view = $this->_initView();
			$view->render('views/jifen/jifenstep04.php');
			return false;
		} else {
			return true;
		}
	}
	//商家充值免费积分
	public function doRecharge() {
		if ($this->_request->isPost()) {
			$view = $this->_initView();
			$view->payments = M('Payment')->select()
				->where('is_enabled = 1')
				->order('rank ASC, id ASC')
				->fetchRows();
			$view->render('views/agentpayway.php');
			die;
		}

		$view = $this->_initView();
		$view->render('views/agentrecharge.php');
	}
	//选择充值
	public function doPayRecharge()
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
								'trade_no' => $prefix.$this->user->id.'-'.time(),
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
}