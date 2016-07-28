<?php

class CallbackController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doPayment()
	{
		$payment = M('Payment')->factory($this->_request->t);
		$payment->callback();

		//写入日志
		$q = array_merge($_POST, $_GET);
		$ip = Suco_Controller_Request_Http::getClientIp();
		$text = '====== '.date('Y/m/d H:i:s').'('.$ip.')====== '."\r\n"
			.http_build_query($q)."\r\n";

		$logFile = $this->_request->t.'_'.date('Ymd').'.log';
		Suco_File::write(LOG_DIR.$logFile, $text, 'a+');

		echo '<script>window.close();</script>';
	}

	public function doExpress()
	{
		$express = M('Express')->factory($this->_request->t);
		echo $express->tracking($_REQUEST['code'])->toJson();
	}
	public function doKuaidi100() {
		$com = $this->_request->com;
		$nu = $this->_request->nu;
		$AppKey='0aae9b18c0359331';
		$url ='http://api.kuaidi100.com/api?id='.$AppKey.'&com='.$com.'&nu='.$nu.'&show=2&muti=1&order=asc';

		$powered = '查询数据由：<a href="http://kuaidi100.com" target="_blank">KuaiDi100.Com （快递100）</a> 网站提供 ';

		//优先使用curl模式发送数据
		if (function_exists('curl_init') == 1){
			$curl = curl_init();
			curl_setopt ($curl, CURLOPT_URL, $url);
			curl_setopt ($curl, CURLOPT_HEADER,0);
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
			curl_setopt ($curl, CURLOPT_TIMEOUT,5);
			$get_content = curl_exec($curl);
			curl_close ($curl);
		} else {
			include(MOD_DIR."Snoopy.php");
			$snoopy = new snoopy();
			$snoopy->referer = 'http://www.google.com/';//伪装来源
			$snoopy->fetch($url);
			$get_content = $snoopy->results;
		}
		echo $get_content . '<br/>' . $powered;
	}
}