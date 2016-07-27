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
}