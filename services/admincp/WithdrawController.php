<?php

class Admincp_WithdrawController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('User_Withdraw')->alias('uw')
			->leftJoin(M('User')->getTableName().' AS f', 'f.id = uw.user_id')
			->columns('uw.*, f.username')
			->order('uw.id ASC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('withdraw/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$user = M('User')->getByUserName($_POST['username']);
			if (!$user->exists()) {
				throw new App_Exception('帐户不存在');
			}

			$user->withdraw(
				$_POST['amount'],
				$_POST['fee'],
				$_POST['voucher'], 
				$_POST['remark'], 
				$_POST['payee'], 
				$_POST['bank']
			)->commit();
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->account = M('User')->getById((int)$this->_request->uid);
		$view->render('withdraw/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('User_Withdraw')->updateById($_POST, (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = M('User_Withdraw')->getById((int)$this->_request->id);
		$view->render('withdraw/input.php');
	}

	public function doDelete()
	{
		$data = M('User_Withdraw')->getById((int)$this->_request->id);
		$data->remove();

		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doCommit()
	{
		M('User_Withdraw')->getById((int)$this->_request->id)->commit();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doRollback()
	{
		M('User_Withdraw')->getById((int)$this->_request->id)->rollback();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doCancel()
	{
		M('User_Withdraw')->getById((int)$this->_request->id)->cancel();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}