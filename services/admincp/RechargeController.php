<?php

class Admincp_RechargeController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('User_Recharge')->alias('ur')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = ur.user_id')
			->leftJoin(M('Payment')->getTableName().' AS p', 'p.id = ur.payment_id')
			->columns('ur.*, u.username, p.name AS payment_name')
			->order('ur.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('recharge/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$user = M('User')->getByUserName($_POST['username']);
			if (!$user->exists()) {
				throw new App_Exception('帐户不存在');
			}
			$user->recharge(
				$_POST['amount'],
				$_POST['fee'],
				$_POST['voucher'],
				$_POST['remark'],
				$_POST['payment_id']
			)->commit();
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->payments = M('Payment')->fetchRows('is_enabled');
		$view->account = M('User')->getById((int)$this->_request->uid);
		$view->render('recharge/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('User_Recharge')->updateById($_POST, (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = M('User_Recharge')->getById((int)$this->_request->id);
		$view->payments = M('Payment')->fetchRows('is_enabled');
		$view->render('recharge/input.php');
	}

	public function doDelete()
	{
		$data = M('User_Recharge')->getById((int)$this->_request->id);
		$data->remove();

		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doCommit()
	{
		M('User_Recharge')->getById((int)$this->_request->id)->commit();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doRollback()
	{
		M('User_Recharge')->getById((int)$this->_request->id)->rollback();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doCancel()
	{
		M('User_Recharge')->getById((int)$this->_request->id)->cancel();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}