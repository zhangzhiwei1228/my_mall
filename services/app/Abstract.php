<?php

class App_Controller_Action extends Suco_Controller_Action
{
	/**
	 * 权限检查
	 */
	protected function _auth()
	{
		require_once 'Code.php';
		$token = $this->_request->token ;
        $token = isset($token) && $token ? $token : 'a5c5dc0c6730e7f10bd02d7e3b4eb46d';
		if ($token) {
			if(strlen($token) != 32) {
				echo  self::_error_data(API_LOGIN_FAILED_INVALID_TOKEN,'无效的token');
				die();
			}
			$user = M('User')->select('id, token, token_expire_time,is_enabled')->where('token='."'".$token."'")->fetchRow();
		}
		if (!$user) {
			echo  self::_error_data(API_TOKEN_NOT_FOUND,'此token不存在');
			die();
		}
		if (!$user['is_enabled']) {
			echo  self::_error_data(API_USER_DISABLE,'此账户已被禁用');
			die();
		}

		if($user['token_expire_time'] < time()){
			echo  self::_error_data(API_TOKEN_EXPIRE,'用户token已过期，请重新登录');
			die();
		}
		return $user;
	}

	/**
	 * @param $user_id
	 * @param $app_id
	 * @param $token
	 * @param bool|false $token_expire_time
	 * @return mixed
	 */
	protected function _update_or_create_token($user_id, $app_id,$token, $token_expire_time = false) {
		$auth = M('User')->select('id,app_id,token,expire_data')->where('id='.$user_id.' and app_id='.$app_id.' and token !='."''".' and token='.$token. ' and token_expire_time >'.time())->fetchRow();
		if (!$token_expire_time) {
			$token_expire_time = time() + ONE_MONTH;
		}
		if ($auth === false) {
			$auth_token = md5(uniqid(mt_rand(), true));
			$data = array(
				'auth_token'  => $auth_token,
				'app_id'      => $app_id,
				'token_expire_time' => $token_expire_time,
				'token_update_time' => time(),
			);
			$auth = M('User')->updateById($data, (int)$this->_request->id);
		}

		return $auth;
	}

	/**
	 * @param $data
	 * @param bool|false $bin2hex
	 * @param int $code
	 * @param string $msg
	 * @param bool|true $secure
	 * @return string
	 */
	protected function _encrypt_data( $data, $code=1000, $msg='请求成功', $secure = true, $bin2hex = false) {
		require_once 'AES.php';
		$data = array('resultCode'=>$code,'resultMsg'=>$msg,'secure'=>$secure,'data'=>AES::encrypt(json_encode($data),APP_KEY,$bin2hex));
		return json_encode($data);
	}

	/**
	 * @param $data
	 * @param bool|false $bin2hex
	 * @return mixed|string
	 */
	protected function _decrypt_data($data,$bin2hex=false) {
		require_once 'AES.php';
		return AES::decrypt($data,APP_KEY,$bin2hex);
	}

	/**
	 * @param $data
	 * @param bool|false $bin2hex
	 * @param int $code
	 * @param string $msg
	 * @param bool|true $secure
	 * @return string
	 */
	protected function _error_data($code, $msg='请求失败', $data = '', $secure = false, $bin2hex = false) {
		require_once 'AES.php';
		$data = array('resultCode'=>$code,'resultMsg'=>$msg,'secure'=>$secure,'data'=>$data);
		$data = json_encode($data);
		return $secure ? AES::encrypt($data,APP_KEY,$bin2hex) : $data;
	}
}
