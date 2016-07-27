<?php

class Usercp_MessageController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('action=inbox');
	}

	public function doInbox()
	{
		$select = M('Message')->alias('m')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = m.sender_uid')
			->columns('m.*, u.username AS sender_name')
			->order('m.id DESC')
			->where('m.recipient_uid = ? AND !m.is_delete', $this->user['id']);

		switch($this->_request->t) {
			case 1:
				$select->where('m.is_read = 0');
				break;
			case 2:
				$select->where('m.is_read = 1');
				break;
			default:
				$select->where('m.is_read = 0');
				break;
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('views/welcomew2.php');
	}

	public function doIsRead()
	{
		$msg = M('Message')->getById((int)$this->_request->id);
		if (!$msg->exists()) {
			throw new App_Exception('信息不存在');
		}
		$msg->is_read = 1;
		$msg->save();

		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function doDelete()
	{
		$msg = M('Message')->getById((int)$this->_request->id);
		if (!$msg->exists()) {
			throw new App_Exception('信息不存在');
		}
		$msg->is_delete = 1;
		$msg->save();

		$this->redirect($_SERVER['HTTP_REFERER']);		
	}
}
