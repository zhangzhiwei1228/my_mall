<?php

class Usercp_BankController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$select = M('User_Bank')->select()
			->where('user_id = ?', $this->user->id)
			->order('id DESC');

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('usercp/bank/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('User_Bank')->insert(array_merge($this->_request->getPosts(), array(
				'user_id' => $this->user->id
			)));
			$this->redirect('action=list');
		}
		$view = $this->_initView();
		$view->render('usercp/bank/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('User_Bank')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect('action=list');
		}

		$view = $this->_initView();
		$view->data = M('User_Bank')->getById((int)$this->_request->id);
		$view->render('usercp/bank/input.php');
	}

	public function doDelete()
	{
		$bank = M('User_Bank')->getById((int)$this->_request->id);
		if ($bank->user_id != $this->user->id) {
			throw new App_Exception('非法访问');
		}
		$bank->remove();
		$this->redirect('action=list');
	}
}
