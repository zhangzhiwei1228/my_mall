<?php

require_once 'runtime.php';
require_once 'func.php';


class Misc extends Suco_Controller_Action
{
	public function init()
	{
		$this->_request = Suco_Application::instance()->getRequest();
		$this->_response = Suco_Application::instance()->getResponse();
	}

	/**
	 * 初始化视图
	 */
	protected function _initView()
	{
		$theme = M('Setting')->theme;

		$view = $this->getView();
		$view->setThemePath($theme);
		$view->setScriptPath(WWW_DIR.trim($theme,'/').'/tpl/scripts/');
		$view->setLayoutPath(WWW_DIR.trim($theme,'/').'/tpl/layouts/');
		$view->setHelperPath(WWW_DIR.trim($theme,'/').'/tpl/helpers/');

		$view->user = M('User')->getCurUser();

		return $view;
	}

	/**
	 * 生成验证码
	 */
	public function doVerify() 
	{
		if (isset($_REQUEST['code'])) { //验证
		    if ($_REQUEST['code'] == $_SESSION['verify']) {
		        echo 1;
		    } else {
		        echo 0;
		    }
		    exit;
		}

		header("Content-type: image/gif");
		/*
		* 初始化
		*/
		$border = 0; //是否要边框 1要:0不要
		$how = 4; //验证码位数
		$w = 80; //图片宽度
		$h = 21; //图片高度
		$fontsize = 20;
		$alpha = "abcdefghijklmnopqrstuvwxyz"; //验证码内容1:字母
		$number = "0123456789"; //验证码内容2:数字
		$randcode = ""; //验证码字符串初始化
		$font = FONT_DIR.'courbd.ttf'; //字体文件

		srand((double)microtime()*1000000); //初始化随机数种子

		$im = imagecreate($w, $h); //创建验证图片

		/*
		* 绘制基本框架
		*/
		$bgcolor = imagecolorallocate($im, 255, 255, 255); //设置背景颜色
		imagefill($im, 0, 0, $bgcolor); //填充背景色
		if($border)
		{
		    $black = imagecolorallocate($im, 0, 0, 0); //设置边框颜色
		    imagerectangle($im, 0, 0, $w-1, $h-1, $black);//绘制边框
		}
		/*
		* 逐位产生随机字符
		*/
		for($i=0; $i<$how; $i++)
		{
		    $alpha_or_number = mt_rand(0, 1); //字母还是数字
//		    $str = $alpha_or_number ? $alpha : $number;
		    $str =  $number;
		    $which = mt_rand(0, strlen($str)-1); //取哪个字符
		    $code = substr($str, $which, 1); //取字符
		    $j = !$i ? 4 : $j+$fontsize; //绘字符位置
		    $color3 = imagecolorallocate($im, mt_rand(0, 180), mt_rand(0, 180), mt_rand(0, 180)); //字符随即颜色
		    #imagechar($im, $fontsize, $j, 3, $code, $color3); //绘字符
		    imagefttext($im, $fontsize, 0, $j, mt_rand(18, 18), $color3, $font, $code);
		    $randcode .= $code; //逐位加入验证码字符串
		}

		/*
		* 添加干扰
		*/
		for($i=0; $i<5; $i++)//绘背景干扰线
		{
		    $color1 = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰线颜色
		    imagearc($im, mt_rand(-5,$w), mt_rand(-5,$h), mt_rand(20,300), mt_rand(20,200), 55, 44, $color1); //干扰线
		}
		for($i=0; $i<$how*25; $i++)//绘背景干扰点
		{
		    $color2 = imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干扰点颜色
		    imagesetpixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2); //干扰点
		}

		//把验证码字符串写入session
		$_SESSION['verify'] = $randcode;

		/*绘图结束*/
		imagegif($im);
		imagedestroy($im);
		/*绘图结束*/
	}

	/**
	 * 输出二维码
	 */
	public function doQrcode()
	{
		require 'Sdks/qrcode/qrcode.php';
		header('Content-Type: image/gif');
		$a=new QR($_REQUEST['text']?$_REQUEST['text']:'http://www.lhjmall.com');
		echo $a->image($_REQUEST['size']?$_REQUEST['size']:4);
	}

	/**
	 * 上传图片
	 */
	public function doUpload()
	{
		$imgConf = Suco_Config::factory(CONF_DIR.'image.conf.php');
		$file = $_FILES['imgFile'];

		$user = M('User')->getUserByToken($_REQUEST['token']);
		/*if (!$user->exists()) {
			$result = array(
				'error' => 1,
				'message' => '身份验证失败！'
			);
		} else {*/
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
				//header('HTTP/1.0 500 ' . $e->getMessage());
				$result = array(
					'error' => 1,
					'message' => $e->getMessage()
				);
			}
		//}
		echo json_encode($result);
	}

	/**
	 * 图片按尺寸压缩输出
	 */
	public function doImage()
	{
		$imgConf = Suco_Config::factory(CONF_DIR.'image.conf.php');
		$allowSize = $imgConf['img_allow_sizes'];

		$imgUrl = './'.$_GET['url'];
		preg_match('/.*_(\d+x\d+)\.\w+/', $imgUrl, $t);
		$size = $t[1];
		if (!in_array($size, $allowSize)) {
			Suco_Application::instance()->getResponse()->setStatus(404);
			die('图片不存在');
		}
		$oImg = str_replace('_'.$size, '', $imgUrl);
		if (is_file($oImg)) {
			list($width, $height) = explode('x', $size);
			$nSrc = @Suco_File_Image::thumb($oImg, $width, $height, $imgUrl);
			header("Content-type: image/jpeg");
			echo file_get_contents($nSrc);
			die;
		}
	}

	/**
	 * 发送短信
	 */
	public function doSms()
	{
		$token = md5('tts_'.date('YmdH'));

		if ($_REQUEST['token'] != $token) {
			die('token验证失败');
		}

		if (isset($_SESSION['sms_time']) && $_SESSION['sms_time'] >= time()-60) {
			die('发送频率过快，请稍后再试');
		}

		if (isset($_SESSION['num'][$m]) && $_SESSION['num'][$m] >= 10) {
			die('此号码已超出单日发送限制');
		}

		if (isset($_SESSION['sms_num']) && $_SESSION['sms_num'] >= 19) {
			die('超出单日发送限制');
		}

		$_SESSION['num'][$m] ++;
		$_SESSION['sms_num'] ++;
		$_SESSION['sms_time'] = time();

//		$_SESSION['sms_code'] = substr(uniqid(rand()), -6);
		$_SESSION['sms_code'] = $this->sendmessage($_REQUEST['m'],6);

		/*require './sms.php';
		sendTemplateSMS($_REQUEST['m'],array($_SESSION['sms_code']),"47897");*/
	}


	//发送短信
	protected function sendmessage($phone, $count = 6, $letter = FALSE)
	{
		$ychar = "2,3,4,5,6,7,8,9";
		if($letter) {
			$ychar .= ',A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y,Z';
		} else {
			$ychar .= ',1,0';
		}
		$list = explode ( ",", $ychar );
		$code = '';
		for($i = 0; $i < $count; $i ++) {
			$randnum = rand ( 0, count($list) - 1 );
			$code .= $list [$randnum];
		}
		// //发送短信
		 $msg="您的注册验证码为：".strtoupper ( $code )."，祝您购物愉快。";
		 $fsurl="http://120.26.69.248/msg/HttpSendSM?account=shiyuan_yishenger&pswd=Yishenger2016&mobile=".$phone."&msg=".$msg."&needstatus=true";
		 $res=file_get_contents($fsurl);
		 $msgarr=explode(',', $res);
		 if($msgarr[1]==0){
		 	return $code;
		 }
		return false;
	}
		/**
	 * 以JSON格式输出区域信息
	 */
	public function doArea()
	{
		echo M('Region')->select('id, parent_id, name, zipcode, level')
			->order('id ASC, rank ASC')
			->fetchOnKey('id')
			->toJson();
	}

	/**
	 * 以JSON格式输出区域信息
	 */
	public function doAdvert()
	{
		echo "document.write('".M('Advert')->getByCode($_GET['code'])."')";
	}

	/**
	 * 加载评论
	 */
	public function doComment()
	{
		$select = M('User_Comment')->select()
			->where('ref_type = ? AND ref_id = ?', array($this->_request->ref_type, $this->_request->ref_id))
			->order('id DESC')
			->paginator(10, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows()
			->hasmanyUser();
		$view->setLayout(false);
		$view->render('ajax/comment.php');
	}
}

if ($_GET['act']) {
	$action = $_GET['act'];
	$control = new Misc();
	$control->dispatch($action);
}