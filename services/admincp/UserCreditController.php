<?php

class Admincp_UserCreditController extends Admincp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_auth(__CLASS__);
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User_Credit')->alias('up')
			->leftJoin(M('User')->getTableName().' AS b', 'up.user_id = b.id')
			->columns('up.*, b.username AS account')
			->order('up.id DESC')
			->paginator($pageSize, $currentPage);

		if ($this->_request->account) {
			$select->where('b.username = ?', $this->_request->account);
		}
		if ($this->_request->begin_time) {
			$select->where('up.create_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('up.create_time <= ?', strtotime($this->_request->end_time));
		}

		$total = clone $select;
		$total->reset(array('columns', 'order'))
			->columns('SUM(up.credit) AS total')
			->limit(1);

		$view = $this->_initView();
		$view->total = $total->fetchRow();
		$view->datalist = $select->fetchRows();
		$view->render('user/credit/list.php');
	}

	public function doRecharge()
	{
		if ($this->_request->isPost()) {
			try {
				// if (!is_int($_POST['point'])) {
				// 	throw new App_Exception('请正确填写积分点数');
				// }
				$account = M('User')->getById((int)$_POST['uid']);
				switch($_POST['type']) {
					case 'credit':
						$account->credit($_POST['point'], '后台充值');
						break;
					case 'credit_happy':
						$account->creditHappy($_POST['point'], '后台充值');
						break;
					case 'credit_coin':
						$account->creditCoin($_POST['point'], '后台充值');
						break;
					case 'vouchers':
						$account->vouchers($_POST['point'], '后台充值');
						break;
				}
			} catch (Exception $e) {
				echo $e->getMessage();
				die;
			}
		}
	}
}