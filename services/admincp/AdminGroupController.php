<?php
/**
 * 管理员分组
 * 
 * @category 	Controller
 * @author 		Eric Yu <blueflu@live.cn>
 * @copyright 	Copyright (c) 2008, Suconet, Inc.
 * @license		http://www.suconet.com/license
 */
class Admincp_AdminGroupController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Admin_Group')->insert($this->_request->getPosts());
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->acl = M('Admin_Acl')->fetchRows();
		$view->render('admin/group/input.php');
	}

	public function doEdit()
	{
		$data = M('Admin_Group')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new App_Exception('记录不存在');
		}
		if ($data->is_locked) {
			throw new App_Exception('禁止操作');
		}
		if ($this->_request->isPost()) {
			M('Admin_Group')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->acl = M('Admin_Acl')->fetchRows();
		$view->data = $data;
		$view->render('admin/group/input.php');
	}
}