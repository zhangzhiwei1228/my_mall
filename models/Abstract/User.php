<?php

abstract class Abstract_User extends Abstract_Model
{
	protected function _insertBefore($data)
	{
		//Username 滤空
		if (!$data['username']) {
			throw new App_Exception('ERR_USERNAME_IS_EMPTY');
		}
		//Username 滤重
		if ($data['username'] && $this->count($this->quoteInto('username = ?', $data['username']))) {
			throw new App_Exception('ERR_USERNAME_USED');
		}
		//Email 滤重
		if ($data['email'] && $this->count($this->quoteInto('email = ?', $data['email']))) {
			throw new App_Exception('ERR_EMAIL_USED');
		}
	}

	protected function _updateBefore($data, $cond = array())
	{
		if (!is_array($data)) return;
		//Username 滤重
		if (isset($data['username']) && $this->count($this->quoteInto('username = ? AND NOT ('.$cond.')', $data['username']))) {
			throw new App_Exception('ERR_USERNAME_USED');
		}
		//Email 滤重
		if ($data['email'] && $this->count($this->quoteInto('email = ? AND NOT ('.$cond.')', $data['email']))) {
			throw new App_Exception('ERR_EMAIL_USED');
		}
	}

	public function filter($data)
	{
		if (@!$data['salt']) {
			if (isset($data['password']) && $data['password'] && $data['password'] != '#password#') {
				$data['salt'] = substr(uniqid(rand()), -6);
				$data['password'] = $this->encrypt($data['password'], $data['salt']);
			} else {
				unset($data['salt']); unset($data['password']);
			}
		}
		return parent::filter($data);
	}

	/**
	 * 用户登陆
	 * @param	string $user 用户名
	 * @param	string $pass 密码
	 * @return object
	 */
	public function login($user, $pass)
	{
		if (!$user) {
			throw new App_Exception('ERR_LOGIN_FAIL');
		}

		$user = $this->select()->where('username = ? OR email = ? OR mobile = ?', $user)->fetchRow();
		if (!$user->exists() || ($user['password'] != $this->encrypt($pass, $user['salt']))) {
			throw new App_Exception('ERR_LOGIN_FAIL');
		}
		if (!$user['is_enabled']) {
			throw new App_Exception('ERR_ACCOUNT_DISABLED');
		}

		$_SESSION['last_login_time'] = $user['last_login_time'];
		$_SESSION['last_login_ip'] = $user['last_login_ip'];

		$ip = Suco_Controller_Request_Http::getClientIp();
		$user['last_login_time'] = time();
		$user['last_login_ip'] = ip2long($ip);
		$user['login_num'] += 1;
		$user->save();

		$this->setCurUser($user);

		return $user;
	}

	/**
	 * 用户登出
	 * @return void
	 */
	public function logout()
	{
		$this->setCurUser();
	}

	/**
	 * 密码加密
	 * @param	string $pass 密码
	 * @param	string $salt 安全码
	 * @return string
	 */
	public function encrypt($pass, $salt)
	{
		if (substr($pass, 0, 2) != '$.') { //防止二次加密
			return '$.'.md5(md5($pass) . $salt);
		} else {
			return $pass;
		}
	}

	/**
	 * 检查密码
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @param	string $pass 密码
	 * @return bool
	 */
	public function checkPass($user, $pass)
	{
		return $this->encrypt($pass, $user['salt']) == $user['password'] ? true : false;
	}

	/**
	 * 生成令牌, 默认每个令牌只保留1个小时有效期 可以通过date(参数)调整
	 * @param	int|object $user 用户ID或对象
	 * @return string
	 */
	public function getToken($user, $suffix = '')
	{
		$suffix = $suffix ? $suffix : date('ymdh');
		if (!($user instanceof self) && is_int($user)) {
			$user = $this->getById((int)$user);
		}
		return md5($this->getSign($user).$suffix);
	}

	/**
	 * 通过令牌查找用户
	 * @param	string $token 令牌
	 * @return object Suco_Db_Table_Row对象
	 */
	public function getUserByToken($token, $suffix = '')
	{
		$suffix = $suffix ? $suffix : date('ymdh');
		return $this->select()
			->where('md5(concat(md5(concat(\''.$this->_name.'\' ,id, salt)), '.$suffix.')) = ?', $token)
			->fetchRow();
	}

	/**
	 * 返回用户签名
	 * @param	int|object $user 用户ID或对象
	 * @return string
	 */
	public function getSign($user)
	{
		if (!($user instanceof self) && is_int($user)) {
			$user = $this->getById((int)$user);
		}

		return md5($this->_name.$user['id'].$user['salt']);
	}

	/**
	 * 通过签名查找用户
	 * @param	string $sign 签名
	 * @return object Suco_Db_Table_Row对象
	 */
	public function getUserBySign($sign)
	{
		return $this->select()
			->where('md5(concat(\''.$this->_name.'\' ,id, salt)) = ?', $sign)
			->fetchRow();
	}

	/**
	 * 返回当前用户信息
	 * @return object Suco_Db_Table_Row 对象
	 */
	public function getCurUser()
	{
		static $user;
		if (!isset($user[$this->_cookie_name])) {
			$cookie = Suco_Cookie::get($this->_cookie_name);
			list($id, $salt) = explode('.', $cookie);
			$user[$this->_cookie_name] = $this->select()->where('id = ? AND salt = ?', array($id, $salt))->fetchRow();
			if ($user[$this->_cookie_name]->exists()) {
				//COOKIE 继期
				$this->setCurUser($user[$this->_cookie_name]);
				$this->updateById('last_online_time = '.time(), $user[$this->_cookie_name]['id']);
			}
		}
		return $user[$this->_cookie_name];
	}

	/**
	 * 设置当前用户
	 * @param object $user 用户
	 */
	public function setCurUser($user = 0)
	{
		if ($user['id']) { $cookie = $user['id'].'.'.$user['salt']; } 
		else { $cookie = ''; }

		Suco_Cookie::set($this->_cookie_name, $cookie, $this->getLoginTimeout());
	}

	/**
	 * 设置登录超时时间
	 * @param int $timeout
	 */
	public function setLoginTimeout($timeout)
	{
		$objName = strtolower(get_class($this));
		$_SESSION[$objName]['_login_timeout'] = $this->_login_timeout = $timeout;
	}

	/**
	 * 返回登录超时时间
	 * @param int $timeout
	 */
	public function getLoginTimeout()
	{
		$objName = strtolower(get_class($this));
		if (isset($_SESSION[$objName]['_login_timeout'])) {
			$this->_login_timeout = $_SESSION[$objName]['_login_timeout'];
		}

		return $this->_login_timeout;
	}
}

?>