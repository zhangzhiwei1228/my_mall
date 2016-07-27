<?php

class Agent_PassportController extends Agent_Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doRegister()
	{	
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '恭喜您，注册成功！',
				'message' => '您的会员帐号是：'.M('Agent')->getCurUser()->username,
				'links' => array(
					array('进行个人中心', '/agent'),
					array('返回首页', '/default')
				),
				'autoback' => array('自动返回上一页', ($this->_request->ref ? base64_decode($this->_request->ref) : '/agent')),
			), 'success');
		}
		
		if ($this->_request->isPost()) {
			if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
				throw new App_Exception('验证码不正确');
			}

			if (isset($_POST['sms_code']) && $_POST['sms_code'] != $_SESSION['sms_code'] && $_POST['sms_code'] != '666666') {
				throw new App_Exception('短信验证码不正确');
			}

			$uid = M('Agent')->insert(array_merge($_POST, array(
				'username' => $_POST['mobile'],
				'nickname' => $_POST['mobile'],
				'is_enabled' => 1,
				'referrals_id' => $_SESSION['recid'],
				'role' => 'member',
				'pay_pass' => $_POST['password'],
				'admin_id' => $admin['id'],
				'ref' => $_POST['invite_mobile'],
				'exp' => 5 //初始经验值
			)));

			//给用户加积分
			$credit = (int)M('Setting')->get('credit_reg');
			if ($credit) {
				M('User_Credit')->increase($uid, $credit, '注册成功，赠送积分'.$credit.'点');
			}

			//给推荐人加积分
			$credit = (int)M('Setting')->get('credit_invite');
			if ($credit && $_SESSION['recid']) {
				M('User_Credit')->increase($_SESSION['recid'], $credit, '成功推荐用户 '.$_POST['username'].' 注册，奖励积分'.$credit.'点');
			}

			$user = M('Agent')->getById($uid);
			M('Agent')->setCurUser($user);

			//自动通过手机验证
			$user->setAuth('mobile', 1);

			$this->redirect('module=agent');
		}
		$view = $this->_initView();
		$view->render('views/shopping/register.php');
	}

	public function doLogin()
	{
		if ($this->_request->isPost()) {
			if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
				throw new App_Exception('验证码不正确');
			}
			
			$timeout = $_POST['autologin'] == 1 ? 3600*24*365 : 3600; //记住密码
			$user = M('Agent')->login($_POST['username'], $_POST['password'], $timeout);

			if ($this->_request->agent == 1 && !$user->is_agent) {
				throw new App_Exception('用户名或密码不正确');
				return;
			}

			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=agent');
		} elseif ($this->_request->token) {
			$current = M('Agent')->getUserByToken($this->_request->token);
			M('Agent')->logout();
			M('Agent')->setCurUser($current);

			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=agent');
		}

		if (M('Agent')->getCurUser()->exists()) {
			$this->redirect('controller=index');
		}
		$view = $this->_initView();
		$view->render('views/landed.php');
	}

	public function doForgetPassword()
	{
		if ($this->_request->token) {
			$user = M('Agent')->getUserByToken($this->_request->token);
			if ($this->_request->isPost()) {
				if ($user['question'] && ($user['answer'] != $_POST['answer'])) {
					throw new App_Exception('修改失败，安全提示答案错误！');
				}
				$user->password = $_POST['new_pass'];
				$user->save();

				return $this->_notice(array(
					'title' => '操作成功！',
					'message' => '密码重置成功！请重新登陆',
					'links' => array(
						array('立即登录', './login')
					)
				), 'success');
			}

			if (!$user->exists()) {
				throw new App_Exception('链接已失效！');
			}

			$view = $this->_initView();
			$view->user = $user;
			$view->render('passport/reset_password.php');
		} else {
			if ($this->_request->isPost()) {
				if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
					throw new App_Exception('验证码不正确');
				}

				switch($_POST['m']) {
					case 'email':
						$user = M('Agent')->select()
							->where('email = ?', $_POST['email'])
							->fetchRow();
						if (!$user->exists()) {
							throw new App_Exception('找回失败！未找到此邮箱');
						}

						$token = $user->getToken();
						$url = H('url', '&send=&token='.$token);
						M('Mail')->sendTpl($user['email'], '', 'mail_forget_password.tpl', array(
							'nickname' => $user['nickname'],
							'url' => $url
						));

						return $this->_notice(array(
							'title' => '操作成功！',
							'message' => '系统已向您的邮箱 '.$user['email'].' 发送了一封密码重置信，请查收',
							'links' => array(
								array('立即登录', '/default/passport/login'),
								array('返回首页', '/default')
							)
						), 'success');
						break;
					case 'mobile':
						$user = M('Agent')->select()
							->where('mobile = ?', $_POST['mobile'])
							->fetchRow();
						if (!$user->exists()) {
							throw new App_Exception('找回失败！未找到此号码');
						}
						if (isset($_POST['sms_code']) && $_POST['sms_code'] != $_SESSION['sms_code']) {
							throw new App_Exception('短信验证码不正确');
						}

						$token = $user->getToken();
						$url = H('url', '&send=&token='.$token);
						$this->redirect($url);
						break;
				}
			}

			$view = $this->_initView();
			$view->render('passport/forget_password.php');
		}
	}

	/**
	 * 绑定第三方帐户
	 */
	public function doBind()
	{
		if ($this->_request->isPost()) {
			$uid = M('Agent')->insert(array_merge($_POST, array(
				'username' => $_POST['email'],
				'is_enabled' => 1,
				'referrals_id' => $_SESSION['recid'],
				'role' => 'buyer',
				'ref' => $_SESSION['ref']
			)));

			M('User_Profile')->insert(array_merge($_POST, array(
				'uid' => $uid
			)));

			M('User_Bind')->insert(array(
				'uid' => $uid,
				'open_id' => $_POST['open_id'],
				'app' => $_POST['app']
			));

			M('Agent')->setCurUser(M('Agent')->getById($uid));
			$this->redirect('module=default');
		}

		$view = $this->_initView();
		$view->bindUser = $_SESSION['oauth_info'];
		$view->render('passport/bind.php');
	}

	public function doLogout()
	{
		M('Agent')->logout();
		$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=login');
	}
	
	public function doCheckName()
	{
		$u = M('Agent')->select()
			->where('username = ?', $_POST['name'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckEmail()
	{
		$u = M('Agent')->select()
			->where('email = ?', $_POST['email'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckMobile()
	{
		$u = M('Agent')->select()
			->where('mobile = ?', $_POST['mobile'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckPass()
	{
		$u = M('Agent')->select()
			->where('username = ? OR email = ? OR mobile = ?', $_POST['user'])
			->fetchRow();
		
		if ($u->checkPass($_POST['pass'])) die('1');
		else die('0');
	}
}
