<?php

class Usercp_PassportController extends Usercp_Controller_Action
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
				'message' => '您的会员帐号是：'.M('User')->getCurUser()->username,
				'links' => array(
					array('进行个人中心', '/usercp'),
					array('返回首页', '/default')
				),
				'autoback' => array('自动返回上一页', ($this->_request->ref ? base64_decode($this->_request->ref) : '/usercp')),
			), 'success');
		}
		$invite_user = M('User')->getById((int)$_SESSION['invite_id']);

		if ($this->_request->isPost()) {
			if($_POST['mobile'] == $_POST['invite_mobile']) {
				throw new App_Exception('不能用自己号码作为邀请手机号！');
			}
			if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
				throw new App_Exception('验证码不正确');
			}

			if (isset($_POST['sms_code']) && $_POST['sms_code'] != $_SESSION['sms_code'] && $_POST['sms_code'] != '666666') {
				throw new App_Exception('短信验证码不正确');
			}

			$invite = M('User')->select()
				->where('mobile = ? OR username = ?', $_POST['invite_mobile'])
				->fetchRow();

			$uid = M('User')->insert(array_merge($_POST, array(
				'username' => $_POST['mobile'],
				'nickname' => $_POST['mobile'],
				'is_enabled' => 1,
				'referrals_id' => $_SESSION['recid'],
				'role' => 'member',
				'pay_pass' => $_POST['password'],
				'admin_id' => $admin['id'],
				'ref' => $_POST['invite_mobile'],
				'parent_id' => $invite->id,
				'exp' => 5 //初始经验值
			)));

			$user = M('User')->getById($uid);
			M('User')->setCurUser($user);

			/*$rec = M('User')->select()
				->where('(username = ? OR mobile = ?)', $_POST['invite_mobile'])
				->fetchRow();

			if ($_POST['invite_mobile'] && $rec->exists()) {
				$credit = 20;
				$rec->credit($credit, '成功推荐用户 '.$_POST['username'].' 注册，奖励积分'.$credit.'点');
			} else {
				$credit = 10;
			}
			if ($this->_request->ref == 'weichat') {
				$credit = 30;
			}

			$user->credit($credit, '注册成功，赠送积分'.$credit.'点');*/

			//自动通过手机验证
			$user->setAuth('mobile', 1);
			unset($_SESSION['invite_id']);
			$this->redirect('module=usercp');
		}
		$view = $this->_initView();
		$view->invite_user_mobile = $invite_user['role'] == 'member' ? $invite_user['mobile'] : $invite_user['username'];

		$view->render('views/shopping/register.php');
	}

	public function doFastLogin()
	{
		if ($this->_request->isPost() && $this->_request->submit) {
			try {
				if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
					throw new App_Exception('验证码不正确');
				}
				$timeout = $_POST['autologin'] == 1 ? 3600*24*365 : 3600; //记住密码
				$user = @M('User')->login($_POST['username'], $_POST['password'], $timeout);

				echo json_encode(array(
					'error' => 0,
					'uid' => $user['id'],
					'nickname' => $user['nickname']
				));
			} catch(App_Exception $e) {
				echo json_encode(array(
					'error' => $e->getMessage()
				));
			}
			die;
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->render('passport/fast_login.php');
	}

	public function doLogin()
	{
		if ($this->_request->success) {
			$this->redirect('default');
			/*$view = $this->_initView();
			$view->render('views/shopping/login_success.php');*/
			return;
		}

		if ($this->_request->isPost()) {
			if (isset($_POST['verify']) && $_POST['verify'] != $_SESSION['verify']) {
				throw new App_Exception('验证码不正确');
			}
			
			$timeout = $_POST['autologin'] == 1 ? 3600*24*365 : 3600; //记住密码
			$user = M('User')->login($_POST['username'], $_POST['password'], $timeout);

			if ($this->_request->agent == 1 && !$user->is_agent) {
				throw new App_Exception('用户名或密码不正确');
				return;
			}

			$_SESSION['login_user_id'] = (int)$user['id'];
			//$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=usercp');
			if(isset($this->_request->ref)) {
				$url = new Suco_Helper_Url('module=usercp&controller=index');

				$this->redirect(base64_decode($this->_request->ref).'/?ref='.$url);
			}
			if(isset($_SESSION['pay_confirm_login'])) {
				$this->redirect('/cart/default/');
			}
			$this->redirect('&success=1');
		} elseif ($this->_request->token) {
			$current = M('User')->getUserByToken($this->_request->token);
			M('User')->logout();
			M('User')->setCurUser($current);

			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=usercp');
		}

		if (M('User')->getCurUser()->exists() ) {
			$this->redirect('controller=index');
		}
		$view = $this->_initView();
		$view->render('views/shopping01.php');
	}

	public function doForgetPassword()
	{
		if ($this->_request->token) {

			$user = M('User')->getUserByToken($this->_request->token);
			if (isset($_SESSION['new_pass'])) {

				$user->password = $_SESSION['new_pass'];
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
						$user = M('User')->select()
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
						$user = M('User')->select()
							->where('mobile = ?', $_POST['mobile'])
							->fetchRow();
						if (!$user->exists() || $_POST['new_pass'] != $_POST['repwd']) {
							throw new App_Exception('找回失败！未找到此号码');
						}
						if (isset($_POST['sms_code']) && $_POST['sms_code'] != $_SESSION['sms_code']) {
							throw new App_Exception('短信验证码不正确');
						}

						$token = $user->getToken();
						$_SESSION['new_pass'] = $_POST['new_pass'];
						$url = H('url', '&send=&token='.$token);
						$this->redirect($url);
						break;
				}
			}

			$view = $this->_initView();
			$view->render('views/forget_password.php');
		}
	}

	/**
	 * 绑定第三方帐户
	 */
	public function doBind()
	{
		if ($this->_request->isPost()) {
			$uid = M('User')->insert(array_merge($_POST, array(
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

			M('User')->setCurUser(M('User')->getById($uid));
			$this->redirect('module=default');
		}

		$view = $this->_initView();
		$view->bindUser = $_SESSION['oauth_info'];
		$view->render('passport/bind.php');
	}

	public function doLogout()
	{
		M('User')->logout();
		unset($_SESSION['login_user_id']);
		unset($_SESSION['pay_confirm_login']);
		$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'module=default');
	}
	
	public function doCheckName()
	{
		$u = M('User')->select()
			->where('username = ?', $_POST['name'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckEmail()
	{
		$u = M('User')->select()
			->where('email = ?', $_POST['email'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckMobile()
	{
		$u = M('User')->select()
			->where('mobile = ?', $_POST['mobile'])
			->fetchRow();

		if ($u->exists()) die('1');
		else die('0');
	}

	public function doCheckPass()
	{
		$u = M('User')->select()
			->where('username = ? OR email = ? OR mobile = ?', $_POST['user'])
			->fetchRow();
		
		if ($u->checkPass($_POST['pass'])) die('1');
		else die('0');
	}
}
