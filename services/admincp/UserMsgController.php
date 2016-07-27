<?php

class Admincp_UserMsgController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		//...
	}

	public function doSend()
	{
		if ($this->_request->isPost()) {
			$uid = M('User')->select('id')
				->where('username = ? OR email = ?', $_POST['user'])
				->fetchCol('id');
			M('User_Msg')->send($uid, $_POST['title'], $_POST['content']);

			return $this->_notice(array(
				'title' => '信息发送成功',
				'links' => array(
					array('返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success')
				),
				'autoback' => array('自动返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success'),
			), 'success');
		}

		$view = $this->_initView();
		$view->user = M('User')->getById((int)$this->_request->uid);
		$view->render('user/msg/send.php');
	}
}