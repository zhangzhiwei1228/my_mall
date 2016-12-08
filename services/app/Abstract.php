<?php

class App_Controller_Action extends Suco_Controller_Action
{
	/**
	 * 权限检查
	 */
	private $uid;
	public function __construct(){
		require_once 'Code.php';
	}
	protected function _auth()
	{
		$token = $this->_request->token ;
        //$token = isset($token) && $token ? $token : 'a5c5dc0c6730e7f10bd02d7e3b4eb46d';
		if ($token) {
			if(strlen($token) != 32) {
				echo  self::_error_data(API_LOGIN_FAILED_INVALID_TOKEN,'无效的token');
				die();
			}
			$user = M('User')->select('id, token, token_expire_time,is_enabled,is_vip,credit,password,salt,shop_id,nickname,avatar,credit_happy,credit_coin,worth_gold,vouchers')->where('token='."'".$token."'")->fetchRow();
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
		$count = M('User_Cart')->count('user_id = '.$user->id);
		$extends = M('User_Extend')->select('field_key,field_name,field_value')->where('user_id ='.$user->id)->fetchRows()->toArray();
		$user->__set('exts',$extends);
		$user->__set('avatar','http://'.$_SERVER['HTTP_HOST'].$user['avatar']);
		$user->__set('count_cart',$count);
		$this->uid = $user->id;
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
		$auth = M('User')->select('id,app_id,token,token_expire_time')->where('id='.$user_id.' and app_id='.$app_id.' and token !='."''".' and token='.$token. ' and token_expire_time >'.time())->fetchRow();
		if (!$token_expire_time) {
			$token_expire_time = time() + ONE_MONTH;
		}
		if ($auth === false) {
			$auth_token = md5(uniqid(mt_rand(), true));
			$data = array(
				'token'  => $auth_token,
				'app_id'      => $app_id,
				'token_expire_time' => $token_expire_time,
				'token_update_time' => time(),
			);
			$auth = M('User')->updateById($data, (int)$user_id);
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
		$data = $data ? AES::encrypt(json_encode($data),APP_KEY,$bin2hex) : '';
		$data = array('resultCode'=>$code,'resultMsg'=>$msg,'secure'=>$secure,'data'=>$data);
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
		$data = $data ? AES::encrypt(json_encode($data),APP_KEY,$bin2hex) : '';
		$data = array('resultCode'=>$code,'resultMsg'=>$msg,'secure'=>$secure,'data'=>$data);
		return json_encode($data);
	}

	/**
	 * @param $data
	 * @return mixed|string
	 * 显示数据
	 */
	protected function show_data($data) {
		$d = json_decode($data);
		$data =  $this->_decrypt_data($d->data);
		return $data ;
	}
	protected function encrypt($pass, $salt)
	{
		if (substr($pass, 0, 2) != '$.') { //防止二次加密
			return '$.'.md5(md5($pass) . $salt);
		} else {
			return $pass;
		}
	}

	/**
	 * @param $data
	 * @return mixed|string
	 */
	protected function parameter($data) {
		$data = $this->_request->$data;
		return $this->_decrypt_data($data,true);
	}

	/**
	 * @param bool|false $file
	 * @return array 上传图片
	 */
	protected function Upload($file = false) {
		$imgConf = Suco_Config::factory(CONF_DIR.'image.conf.php');
		$file = $file ? $file : $_FILES['imgFile'];
		$user = M('User')->getUserByToken($_REQUEST['token']);
		try {
			if (!$file) {
				throw new Suco_Exception('The file upload fail');
			}
			$url = Suco_File::upload($file, 'uploads/image', array(
				'jpg','jpeg','png','gif','bmp','pdf','txt','rar','zip','gzip',
				'doc','docx','xls','xlsx','ppt','pptx'), getUploadFileSize());
			$url = (string)new Suco_Helper_BaseUrl($url, false);

			$result = $data = array(
				'error' => 0,
				//'user' => $user,
				'ref' => $_REQUEST['ref'],
				'sign' => $user->getSign(),
				'format' => $file['type'],
				'name' => $file['name'],
				'size' => $file['size'],
				'url' => $url,
				'src' => $url
			);

			//保存至数据库
			M('Image')->insert($data);
		} catch(Suco_Exception $e) {
			$result = array(
				'error' => 1,
				'message' => $e->getMessage()
			);
		}
		return $result;
	}

	/**
	 * 二进制流转换成图片
	 */
	public function stream2Image() {
		//二进制数据流
		$data = file_get_contents ( 'php://input' ) ? file_get_contents ( 'php://input' ) : gzuncompress ( $GLOBALS ['HTTP_RAW_POST_DATA'] );
		$save_name = md5(microtime()) . '.' ;
		$dest = 'uploads/image';
		$dest = rtrim($dest, '/') . '/' . date('Ymd') . '/';
		if (!is_dir($dest)) mkdir($dest, 0777);
		$dest = $dest . date('H') . '/';
		if (!is_dir($dest)) mkdir($dest, 0777);
		$dest1 = WWW_DIR.$dest;
		//数据流不为空，则进行保存操作
		if (! empty ( $data )) {
			//创建并写入数据流，然后保存文件
			if (@$fp = fopen ( '/tmp/file', 'w+' )) {
				fwrite ( $fp, $data );
				fclose ( $fp );
				$img_info = getimagesize('/tmp/file');
				if(!$img_info) {
					unlink('/tmp/file');
					echo  self::_error_data(API_IMAGE_TYPE_ERROR,'上传文件格式错误');
					die();
				}
				$img_size = filesize('/tmp/file');
				if($img_size > 2048000) {
					unlink('/tmp/file');
					echo  self::_error_data(API_IMAGE_SIZE_ERROR,'上传文件最大为2M');
					die();
				}
				switch ($img_info[2]){
					 case 1:
  						$imgtype = "gif";
  						break;
					case 2:
 						$imgtype = "jpg";
 						break;
					case 3:
 						$imgtype = "png";
	 					break;
					default:
						$imgtype = "jpg";
						break;
				}
				$allowTypes = array('jpg','jpeg','png','gif');
				$denyTypes = array('php', 'asp', 'jsp', 'aspx', 'html', 'js', 'css');//禁止类型
				if ((!in_array($imgtype, $allowTypes)) || in_array($denyTypes, $denyTypes)) {
					unlink('/tmp/file');
					echo  self::_error_data(API_IMAGE_TYPE_ERROR,'上传文件格式错误');
					die();
				} else {
					if (@$fp1 = fopen ( $dest1.$save_name.$imgtype, 'w+' )) {
						fwrite ( $fp1, $data );
						fclose ( $fp1 );
						unlink('/tmp/file');
					}
					return '/'.$dest.$save_name.$imgtype;
				}
			} else {
				echo  self::_error_data(API_IMAGE_WRITE_FAIL,'文件写入失败');
				die();
			}
		} else {
			echo  self::_error_data(API_UPLOAD_RESOURCES_NULL,'上传资源为空');
			die();
		}
	}
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
		$view->user = M('User')->getCurUser();
		//$view->setLayout('default.php');

		require_once WWW_DIR.trim($theme,'/').'/comm.php';

		return $view;
	}
}
