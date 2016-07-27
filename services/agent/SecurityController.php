<?php

class Agent_SecurityController extends Agent_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}
	
	public function doDefault()
	{
		$view = $this->_initView();
		$view->render('usercp/security/index.php');
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
		$view->render('views/password.php');
	}

	public function doResetPayPwd()
	{
		if ($this->_request->isPostOnce()) {
			if ($this->user->pay_pass && !$this->user->checkPayPass($_POST['old_pass'])) {
				throw new App_Exception('修改失败，当前密码不正确。', 1);
			}
			$this->user->pay_pass = $_POST['new_pass'];
			$this->user->save();

			return $this->_notice(array(
				'title' => '修改成功！',
				'message' => '系统已更新您的支付密码',
				'autoback' => array('自动返回上一页', 'action=default'),
			), 'success');
		}

		/*
		if ($this->user['question'] && !$_SESSION['answer_ok']) {
			$this->redirect('action=check_answer&ref='.$this->_request->url);
		}*/

		$view = $this->_initView();
		$view->render('usercp/security/reset_pay_pwd.php');
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
				'autoback' => array('自动返回上一页', 'action=default'),
			), 'success');
		}

		if ($this->user['question'] && !$_SESSION['answer_ok']) {
			$this->redirect('action=chk_answer&ref='.$this->_request->url);
		}

		$view = $this->_initView();
		$view->render('usercp/security/set_question.php');
	}

	public function doChkAnswer()
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
		$view->render('usercp/security/chk_answer.php');
	}

	public function doChkName()
	{
		if ($this->_request->isPost()) {
			$this->user->save($_POST);
			$this->user->setAuth('name', 0, $_FILES['attachments']);
			
			return $this->_notice(array(
				'title' => '信息已提交',
				'message' => '我们将在1~3个工作日内对您的信息进行审核',
				'links' => array(
					array('返回认证中心', 'action=default')
				),
				'autoback' => array('自动返回上一页', 'action=default'),
			), 'success');
		}

		$view = $this->_initView();
		$view->render('usercp/security/chk_name.php');
	}

	public function doChkMail()
	{
		if ($this->user->getAuth('email')->status == 1) {
			$this->redirect('action=default');
		}
		$authkey = $this->user->getToken(date('Ymd'));
		if ($this->_request->authkey) {
			if ($authkey == $this->_request->authkey) {
				$this->user->setAuth('email', 1);
				return $this->_notice(array(
					'title' => '恭喜，您已完成邮箱认证！',
					'message' => '邮箱地址：'.$this->user['email'],
					'links' => array(
						array('返回用户中心', 'controller=index'),
						array('已经完成验证，关闭此页', '#close')
					)
				), 'success');
			} else {
				throw new App_Exception('邮箱验证地址不正确或已经失效');
			}
		} elseif ($this->_request->send && ($this->user['email'] || $_POST['email'])) {
			if ($_POST['email']) {
				$this->user['email'] = $_POST['email'];
				$this->user->save();
			}

			$url = H('url', '&send=&authkey='.$authkey);
			echo M('Mail')->sendTpl($this->user['email'], '', 'mail_auth.tpl', array(
				'nickname' => $this->user['nickname'],
				'url' => $url
			));

			if (!$this->_request->isAjax()) {
				$this->redirect('&send=');
			}
			return;
		}

		$view = $this->_initView();
		$view->render('usercp/security/chk_mail.php');
	}

	public function doChkMobile()
	{
		if ($this->user->getAuth('mobile')->status == 1) {
			$this->redirect('action=default');
		}
		
		if ($this->_request->code) {
			if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
				throw new App_Exception('验证码不正确');
			}
			
			if ($_SESSION['sms_code'] == $this->_request->code) {
				$this->user->setAuth('mobile', 1);
				$this->user['mobile'] = $_POST['mobile'];
				$this->user->save();
				return $this->_notice(array(
					'title' => '恭喜，您已完成手机认证！',
					'message' => '您的手机号码：'.$this->user->profile['mobile'],
					'links' => array(
						array('返回用户中心', 'controller=index'),
						array('已经完成验证，关闭此页', '#close')
					)
				), 'success');
			} else {
				throw new App_Exception('验证码不正确');
			}
		}
		
		$view = $this->_initView();
		$view->render('usercp/security/chk_mobile.php');
	}
}
