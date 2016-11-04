<?php

class App_Controller_Action extends Suco_Controller_Action
{
	/**
	 * 权限检查
	 */
	protected function _auth()
	{
		if ($this->_request->token) {
			$token = $this->_request->token;

			if(strlen($token) != 32) {
				echo  self::_error_data(array('code'=>1001,'msg'=>'无效的token'));
				die();
			}
			$user = M('User')->select('id, token, token_expire_time,is_enabled')->where('token='."'".$token."'")->fetchRow();
		}
		if (!$user) {
			echo  self::_error_data(array('code'=>1002,'msg'=>'此token不存在'));
			die();
		}
		if (!$user['is_enabled']) {
			echo  self::_error_data(array('code'=>1003,'msg'=>'此账户已被禁用'));
			die();
		}

		if($user['token_expire_time'] < time()){
			echo  self::_error_data(array('code'=>1004,'msg'=>'用户token已过期，请重新登录'));
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
	 * @return string
	 */
	protected function _encrypt_data($data, $bin2hex=false) {
		require_once 'AES.php';
		$data = array('status'=>'ok','result'=>$data);
		$data = json_encode($data);
		return AES::encrypt($data,APP_KEY,$bin2hex);
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
	 * @return string
	 */
	protected function _error_data($data,$bin2hex=false) {
		require_once 'AES.php';
		$data = array('status'=>'fail','error'=>$data);
		$data = json_encode($data);
		return AES::encrypt($data,APP_KEY,$bin2hex);
	}
}
