<?php

class Usercp_CommentController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('User_Comment')->insert(array_merge($_POST, array(
				'user_id' => $this->user->id
			)));
		}
	}

	public function doDelete()
	{
		$data = M('User_Comment')->getById((int)$this->_request->id);
		if ($data->user_id != $this->user->id) {
			throw new App_Exception('非法操作');
		}
		$data->remove();
	}
}