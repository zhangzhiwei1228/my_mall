<?php

class Agent_Controller_Action extends Suco_Controller_Action
{
	/**
	 * 权限检查
	 */
	protected function _auth()
	{
		if ($this->_request->token) {
			$user = M('Agent')->getUserByToken($this->_request->token);
		} else {
			$user = M('Agent')->getCurUser();
		}

		if (!$user->exists()) {
			$this->redirect('controller=passport&action=login&ref='.$this->_request->url);
		}
		if (!$user['is_enabled']) {
			throw new App_Exception('This account has been disabled!');
		}

		return $user;
	}

	/**
	 * 显示提示
	 */
	protected function _notice($data, $type = 'success')
	{
		$view = $this->_initView();
		$view->render('views/notice.php', array_merge($data, array('type' => $type)));
	}

	/**
	 * 初始化视图
	 */
	protected function _initView()
	{
		$theme = M('Setting')->theme;

		$view = $this->getView();
		$view->setThemePath($theme);
		$view->setScriptPath(WWW_DIR.trim($theme,'/').'/');
		//$view->setLayoutPath(WWW_DIR.trim($theme,'/').'/tpl/layouts/');
		$view->setHelperPath(WWW_DIR.trim('themes/admincp_v4.3').'/tpl/helpers/');

		$view->setting = M('Setting');
		$view->advert = M('Advert');
		$view->user = M('Agent')->getCurUser();
		//$view->setLayout('default.php');

		require_once WWW_DIR.trim($theme,'/').'/comm.php';

		return $view;
	}
}