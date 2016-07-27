<?php

class Sms
{
	public function send($mobile, $body)
	{
		require_once 'sdks/sms/client.php';

		/**
		 * 网关地址
		 */	
		$gwUrl = 'http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService';

		/**
		 * 序列号,请通过亿美销售人员获取
		 */
		$serialNumber = '6SDK-EMY-6688-KDULR';

		/**
		 * 密码,请通过亿美销售人员获取
		 */
		$password = '027716';

		/**
		 * 登录后所持有的SESSION KEY，即可通过login方法时创建
		 */
		$sessionKey = '123456';

		/**
		 * 连接超时时间，单位为秒
		 */
		$connectTimeOut = 2;

		/**
		 * 远程信息读取超时时间，单位为秒
		 */ 
		$readTimeOut = 10;

		/**
		 * $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
		 * $proxyport		可选，代理服务器端口，默认为 false
		 * $proxyusername	可选，代理服务器用户名，默认为 false
		 * $proxypassword	可选，代理服务器密码，默认为 false
		*/	
		$proxyhost = false;
		$proxyport = false;
		$proxyusername = false;
		$proxypassword = false; 

		$client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
		/**
		 * 发送向服务端的编码，如果本页面的编码为GBK，请使用GBK
		 */
		$client->setOutgoingEncoding("UTF-8");
		$client->login();
		return $client->sendSMS((array)$mobile, $body);
	}
}