<?php

class Create_code extends MY_Controller{

	function index(){
	    ob_clean();
		//生成验证码图片
		header("Content-type: image/png");
		// 全数字
		$str = "1,2,3,4,5,6,7,8,9,a,b,c,d,f,g";      //要显示的字符，可自己进行增删
		$list = explode(",", $str);
		$cmax = count($list) - 1;
		$verifyCode = '';
		for ( $i=0; $i < 5; $i++ ){
		      $randnum = mt_rand(0, $cmax);
		      $verifyCode .= $list[$randnum];           //取出字符，组合成为我们要的验证码字符
		}
		$this->load->helper('cookie_helper');
		delete_cookie('code');
		$cookie = array(
					'name'   => 'code',
					'value'  => md5($verifyCode),
					'expire' => time()+3600,
					'path'   => '/',
					);
		set_cookie($cookie);
		/*get_cookie('code', TRUE);*/

		$im = imagecreate(58,28);    //生成图片
		$black = imagecolorallocate($im, 0,0,0);     //此条及以下三条为设置的颜色
		$white = imagecolorallocate($im, 255,255,255);
		$gray = imagecolorallocate($im, 200,200,200);
		$red = imagecolorallocate($im, 255, 0, 0);
		imagefill($im,0,0,$white);     //给图片填充颜色
		 
		//将验证码绘入图片
		imagestring($im, 5, 10, 8, $verifyCode, $black);    //将验证码写入到图片中
		 

		for($i=0;$i<50;$i++) //加入干扰象素 
		{ 
			imagesetpixel($im, mt_rand(0, 58) , mt_rand(0, 28) , $black); //加入点状干扰素
		 	imagesetpixel($im, mt_rand(0, 58) , mt_rand(0, 28) , $red); 
		 	imagesetpixel($im, mt_rand(0, 58) , mt_rand(0, 28) , $gray); 
		}
		imagepng($im);
		imagedestroy($im);
		}
}
?>