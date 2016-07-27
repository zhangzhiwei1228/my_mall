<?php

class Usercp_AddressController extends Usercp_Controller_Action
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
		$view = $this->_initView();
		$view->datalist = M('User_Address')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->fetchRows();
		$view->render('views/weblist.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			if ($_POST['is_def'] == 1) {
				M('User_Address')->update('is_def = 0', 'user_id = '.(int)$this->user['id']);
			}
			$id = M('User_Address')->insert(array_merge($_POST, array(
				'user_id' => $this->user['id']
			)));
			$_SESSION['addr_id'] = $id;
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->render('views/addweb.php');
	}

	public function doEdit()
	{
		$data = M('User_Address')->getById((int)$this->_request->id);
		if ($data['user_id'] != $this->user['id']) {
			throw new App_Exception('禁止访问');
		}

		if ($this->_request->isPost()) {
			if ($_POST['is_def'] == 1) {
				M('User_Address')->update('is_def = 0', 'user_id = '.(int)$this->user['id']);
			}
			M('User_Address')->updateById($_POST, $this->_request->id);
			$_SESSION['addr_id'] = $this->_request->id;
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}


		$view = $this->_initView();
		$view->data = $data;
		$view->render('views/addweb.php');
	}

	public function doDelete()
	{
		$data = M('User_Address')->getById((int)$this->_request->id);
		if ($data['user_id'] != $this->user['id']) {
			throw new App_Exception('禁止访问');
		}
		$data->remove();
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}