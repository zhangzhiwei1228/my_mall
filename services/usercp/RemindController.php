<?php

class Usercp_RemindController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		if ($this->_request->isPost()) {
			$this->user->setRemind($_POST);
			return $this->_notice(array(
				'title' => '设置成功！',
				'message' => '系统已更新您的设置',
				'links' => array(
					array('返回上一页', '&')
				),
				'autoback' => array('自动返回上一页', '&')
			), 'success');
		}

		$view = $this->_initView();
		$view->remind = $this->user->getRemind();
		$view->render('usercp/remind/index.php');
	}
}
