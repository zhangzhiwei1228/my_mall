<?php

class Admincp_MoneyController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		$recharge = M('User_Recharge')->alias('ur')
			->leftJoin(M('Payment')->getTableName().' AS p', 'p.id = ur.payment_id')
			->columns('p.name AS payment_name, SUM(ur.amount) AS total')
			->where('ur.status = 2')
			->group('ur.payment_id')
			->fetchRows();

		$withdraw = M('User_Withdraw')->alias('uw')
			->columns('uw.bank_name, SUM(uw.amount) AS total')
			->where('uw.status = 2')
			->group('uw.bank_name')
			->fetchRows();

		$logs = M('User_Money')->select('type, FROM_UNIXTIME(create_time, \'%Y年%m月\') AS month, SUM(amount) AS total')
			->group('type, month')
			->where('status = 2')
			->fetchRows();

		foreach($logs as $log) {
			$report[$log['month']][$log['type']] = $log['total'];
		}

		$view = $this->_initView();
		$view->report = $report;
		$view->recharge = $recharge;
		$view->withdraw = $withdraw;
		$view->render('money/index.php');
	}

	public function doLog()
	{
		$select = M('User_Money')->alias('um')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = um.user_id')
			->columns('um.*, u.username')
			->order('um.id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->q) {
			$select->where('u.username LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('money/log.php');
	}

	public function doAccount()
	{
		$select = M('User')->select()
			->order('id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('money/account.php');
	}

	public function doDetail()
	{
		$view = $this->_initView();
		$view->data = M('User')->getById((int)$this->_request->id);
		$view->logs = M('User_Money')->select()
			->where('user_id = ?', $this->_request->id)
			->paginator(10, $this->_request->page)
			->fetchRows();
		$view->banks = M('User_Bank')->select()
			->where('user_id = ?', $this->_request->id)
			->fetchRows();
		$view->render('money/detail.php');
	}
}