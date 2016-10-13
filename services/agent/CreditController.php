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
	//核销抵佣金
	public function doVerification() {
		$view = $this->_initView();
		$view->render('views/new_text/verification.php');
	}
	public function doQueryGold()
	{
		echo M('Worthglod')->alias('wg')
			->leftJoin(M('User')->getTableName().' AS u', 'wg.uid = u.id')
			->columns('wg.*,u.username,u.mobile')
			->where('wg.code = ?', $this->_request->q)
			->limit(10)
			->fetchRows()
			->toJson();
	}
	public function doCheckout() {
		$glod = M('Worthglod')->getById((int)$this->_request->gid);
		if (!$glod->exists()) {
			throw new App_Exception('此兑换码不存在');
		}
		$account = M('User')->getById((int)$glod['uid']);
		if (!$account->exists()) {
			throw new App_Exception('所属账户不存在');
		}
		if ($this->_request->isPost()) {
			$worthglod = M('User_Credit')->select()->where('user_id='.$account['id'].' and code='."'".$glod['code']."'")->fetchRow()->toArray();
			if($worthglod) {
				throw new App_Exception('此账户已经核销过，请不要重复核销');
			}

			if($account['worth_gold'] < $glod['privilege']) {
				throw new App_Exception('该帐户抵用金不足');
			}
			$glod->write = 2;
			$glod->write_uid = $this->user->id;
			$glod->write_time = time();
			$glod->save();
			$view = $this->_initView();
			$view->account = $account;
			$view->glod = $glod;
			$this->user->worthGold($glod['privilege'],'核销用户【'.$account['username'].'-'.$account['id'].'】【'.$glod['privilege'].'抵用金】', $glod['code']);
			$account->worthGold($glod['privilege'] * -1,'被用户【'.$this->user['username'].'-'.$this->user['id'].'】核销【'.$glod['privilege'].'抵用金】', $glod['code']);
			$view->render('views/new_text/verification_okinfo.php');
			return;
		}

		$view = $this->_initView();
		$view->glod = $glod;
		$view->account = $account;
		$view->render('views/new_text/verification_ok.php');
	}
	//核销记录
	public function doCancel() {
		$datas = M('User_Credit')->alias('uc')
			->where('uc.user_id = '.(int)$this->user->id.' and uc.type='."'".'worth_gold'."'".' and uc.code !='."''")
			->leftJoin(M('Worthglod')->getTableName().' AS wg', 'wg.code = uc.code')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = wg.uid')
			->columns('wg.*,u.username,uc.create_time')
			->order('uc.create_time DESC')
			->paginator(20, $this->_request->page)
			->fetchRows();
		$view = $this->_initView();
		$view->datas = $datas;

		$view->earnings = $this->user->countGold();
		$view->render('views/new_text/verification_table.php');
	}
}