<?php

class Admincp_AdminAclController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Admin_Acl')->insert($this->_request->getPosts());
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->groups = M('Admin_Acl')->select('package')->group('package')->fetchCols('package');
		$view->render('admin/acl/input.php');
	}

	public function doEdit()
	{
		$data = M('Admin_Acl')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new App_Exception('记录不存在');
		}
		if ($this->_request->isPost()) {
			M('Admin_Acl')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->groups = M('Admin_Acl')->select('package')->group('package')->fetchCols('package');
		$view->render('admin/acl/input.php');
	}
}