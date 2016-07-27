<?php

class Suco_Sdk_Client
{
	private $_appKey = '';
	private $_appSecret = '';
	private $_gateway = 'http://dev.sucomall.com/openapi/';

	public function setAppKey($key)
	{
		$this->_appKey = $key;
	}

	public function setAppSecret($secret)
	{
		$this->_appSecret = $secret;
	}

	public function generateToken($params)
	{
		ksort($params);
		foreach ($params as $k => $v) {
			if (substr($v, 0, 1) == '@') {
				unset($params[$k]);
			}
		}
		return strtolower(md5($this->_appSecret.implode('', $params).date('ymdhi')));
	}

	public function exec($method, $params)
	{
		$url = $this->_gateway.$method.'/?appkey='.$this->_appKey.'&token='.$this->generateToken($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//https 请求
		if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}

		echo curl_exec($ch);
	}
}