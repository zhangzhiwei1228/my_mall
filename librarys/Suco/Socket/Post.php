<?php
/**
 * Suco_Socket_Post 操作封装
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Socket
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

require_once 'Suco/Socket/Abstract.php';

class Suco_Socket_Post extends Suco_Socket_Abstract
{
	
	protected $_host;
	protected $_port;
	protected $_timeout;
	protected $_protocol;

		/**
	 * 构造函数
	 * 连接SMTP服务器
	 *
	 * @param string $host
	 * @param int $port
	 * @param string $protocol
	 * @param int $timeout
	 * @return void
	 */
	public function __construct($host, $port, $protocol = 'tcp', $timeout = 3)
	{
		$this->_host = $host;
		$this->_port = $port;
		$this->_protocol = $protocol;
		$this->_timeout = $timeout;

    	$this->open($host, $port, $timeout, $protocol);
	}

	public function send($url, $data)
	{
		if (is_array($data)) {
			$data = http_build_query($data);
		}

		$header = "POST /$url HTTP/1.1\r\n";
		$header .= "Host: $this->_host\r\n";
		$header .= "Content-type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-length: " . strlen($data) . "\r\n";
		$header .= "Connection: Close\r\n\r\n";
	    $header .= "$data\r\n\r\n";

		$this->execute($header, false);
	}

	/**
	 * 构造函数
	 * 关闭 Socket 连接
	 *
	 * @return void
	 */
    public function __destroy()
    {
    	$this->close();
    }
}