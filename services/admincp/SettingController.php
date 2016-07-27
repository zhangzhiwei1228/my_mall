<?php

class Admincp_SettingController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '保存成功，系统已更新您的设置！',
				'links' => array(
					array('返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success')
				),
				'autoback' => array('自动返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success'),
			), 'success');
		}

		if ($this->_request->isPost()) {
			M('Setting')->import($_POST['config'])
				->save();
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->themes = Suco_File_Folder::read(WWW_DIR.'themes');
		$view->data = M('Setting')->export();
		$view->render('setting/index.php');
	}
}