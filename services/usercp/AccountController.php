<?php

class Usercp_AccountController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doProfile()
	{
		if ($this->_request->isPost()) {
			M('User_Extend')->delete('user_id = ?', $this->user['id']);
			foreach($_POST['ext'] as $k => $v) {
				M('User_Extend')->insert(array(
					'user_id' => $this->user['id'],
					'field_key' => $k,
					'field_name' => $v['name'],
					'field_value' => $v['value']
				));
			}

			$this->user->save($_POST);
			return $this->_notice(array(
				'title' => '资料更新成功！',
				'links' => array(
					array('返回上一页', '&')
				),
				'autoback' => array('自动返回上一页', '&'),
			), 'success');
		}

		$view = $this->_initView();
		$view->extFields = M('User')->getExtFieldLists('member');
		$view->render('views/money_information.php');
	}

	public function doAvatar()
	{
		$view = $this->_initView();
		$view->render('usercp/account/avatar.php');
	}


	public function doRemind()
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
		$view->render('usercp/account/remind.php');
	}

	public function doSecurity()
	{
		$view = $this->_initView();
		$view->render('usercp/account/security.php');
	}

	public function doResetLoginPwd()
	{
		if ($this->_request->isPostOnce()) {
			if (!$this->user->checkPass($_POST['old_pass'])) {
				throw new App_Exception('修改失败，当前密码不正确。', 1);
			}
			$this->user['password'] = $_POST['new_pass'];
			$this->user->save();

			return $this->_notice(array(
				'title' => '修改成功！',
				'message' => '系统已更新您的登录密码，请重新登录。',
				'links' => array(
					array('立即登录', 'controller=passport&action=login')
				)
			), 'success');
		}

		/*
		if ($this->user['question'] && !$_SESSION['answer_ok']) {
			$this->redirect('action=check_answer&ref='.$this->_request->url);
		}*/

		$view = $this->_initView();
		$view->render('usercp/account/reset_login_pwd.php');
	}

	public function doResetPayPwd()
	{
		if ($this->_request->isPostOnce()) {
			if (!$this->user->checkPayPass($_POST['old_pass'])) {
				throw new App_Exception('修改失败，当前密码不正确。', 1);
			}
			$this->user->pay_pass = $_POST['new_pass'];
			$this->user->save();

			return $this->_notice(array(
				'title' => '修改成功！',
				'message' => '系统已更新您的支付密码',
				'autoback' => array('自动返回上一页', 'action=security'),
			), 'success');
		}

		/*
		if ($this->user['question'] && !$_SESSION['answer_ok']) {
			$this->redirect('action=check_answer&ref='.$this->_request->url);
		}*/

		$view = $this->_initView();
		$view->render('usercp/account/reset_pay_pwd.php');
	}

	public function doSetQuestion()
	{
		if ($this->_request->isPostOnce()) {

			$this->user['question'] = $_POST['question'];
			$this->user['answer'] = $_POST['answer'];
			$this->user->save();

			$_SESSION['answer_ok'] = 0;
			return $this->_notice(array(
				'title' => '修改成功！',
				'message' => '系统已更新您的安全保护问题。',
				'autoback' => array('自动返回上一页', 'action=security'),
			), 'success');
		}

		if ($this->user['question'] && !$_SESSION['answer_ok']) {
			$this->redirect('action=check_answer&ref='.$this->_request->url);
		}

		$view = $this->_initView();
		$view->render('usercp/account/set_question.php');
	}

	public function doCheckAnswer()
	{
		if ($this->_request->isPostOnce()) {
			if ($_POST['answer'] == $this->user['answer']) {
				$_SESSION['answer_ok'] = 1;
				$this->redirect(base64_decode($this->_request->ref));
			} else {
				$_SESSION['answer_ok'] = 0;
				throw new App_Exception('答案错误，请重新回答。');
			}
		}

		$view = $this->_initView();
		$view->render('usercp/account/check_answer.php');
	}

	public function doLog()
	{
		$select = M('User_Login')->select()
			->where('user_id = ?', $this->user['id'])
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('usercp/account/log.php');
	}

}
