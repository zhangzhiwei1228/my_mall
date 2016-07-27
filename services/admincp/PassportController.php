<?php

class Admincp_PassportController extends Admincp_Controller_Action
{
	public function doLogin()
	{
		if ($this->_request->isPost()){
			M('Admin_Menu')->setCurNote(-1); //初始化菜单节点
			try {
				M('Admin')->login($_POST['username'], $_POST['password']);
				//处理订单数据
				M('Order')->process();
				$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=admincp');
			} catch (App_Exception $e) {
				Suco_Locale::instance()->addPackage('error');
				$error = T($e->getMessage());
				$this->redirect('&error='.$error);
				return;
			}
		}

		$view = $this->_initView();
		$view->setLayout(false); //禁用布局
		$view->render('passport/login.php');
	}

	public function doLogout()
	{
		M('Admin')->logout();
		$this->redirect('action=login');
	}
}