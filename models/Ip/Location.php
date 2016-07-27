<?php

define('DATE_FILE', dirname(__FILE__) . DS . 'ip.dat');

class Ip_Location extends Suco_Model
{
	protected $_datafile = DATE_FILE;
	protected $_handle = '';
	protected $_ip = '';
	protected $_addr = array();
	
	public function __construct($ip = null)
	{
		if(!$this->_handle = @fopen($this->_datafile, 'rb')) {
			throw new App_Exception('Invalid IP data file');
		}
		$this->_ip = $ip;
	}
	
	public function __destruct()
	{
		fclose($this->_handle);
	}
	
	public function __toString()
	{
		$addr = $this->_parse($this->_ip);
		return $this->_filter(implode(' ', $addr));
	}

	public function instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}
	
	static public function getAddr1($ip)
	{	
		$addr = self::instance()->_parse($ip);
		return self::_filter($addr[0]);
	}
	
	static public function getAddr2($ip)
	{
		$addr = self::instance()->_parse($ip);
		return self::_filter($addr[1]);
	}
	
	protected function _parse($ip)
	{
		$ip = explode('.', $ip);
		if (count($ip) <= 3) {
			throw new App_Exception('Invalid IP Address');	
		}
		fseek($this->_handle, 0);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
		if(!($DataBegin = fread($this->_handle, 4)) || !($DataEnd = fread($this->_handle, 4)) ) return;
		@$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		@$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;
		$BeginNum = $ip2num = $ip1num = 0;
		$this->_addr[0] = $this->_addr[1] = '';
		$EndNum = $ipAllNum;
	
		while($ip1num > $ipNum || $ip2num < $ipNum) {
			$Middle= intval(($EndNum + $BeginNum) / 2);
			fseek($this->_handle, $ipbegin + 7 * $Middle);
			$ipData1 = fread($this->_handle, 4);
			if(strlen($ipData1) < 4) {
				return '- System Error';
			}
			$ip1num = implode('', unpack('L', $ipData1));
			if($ip1num < 0) $ip1num += pow(2, 32);
			if($ip1num > $ipNum) {
				$EndNum = $Middle;
				continue;
			}
			$DataSeek = fread($this->_handle, 3);
			if(strlen($DataSeek) < 3) {
				return '- System Error';
			}
			$DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
			fseek($this->_handle, $DataSeek);
			$ipData2 = fread($this->_handle, 4);
			if(strlen($ipData2) < 4) {
				return '- System Error';
			}
			$ip2num = implode('', unpack('L', $ipData2));
			if($ip2num < 0) $ip2num += pow(2, 32);
	
			if($ip2num < $ipNum) {
				if($Middle == $BeginNum) {
					return '- Unknown';
				}
				$BeginNum = $Middle;
			}
		}
	
		$ipFlag = fread($this->_handle, 1);
		if($ipFlag == chr(1)) {
			$ipSeek = fread($this->_handle, 3);
			if(strlen($ipSeek) < 3) {
				return '- System Error';
			}
			$ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
			fseek($this->_handle, $ipSeek);
			$ipFlag = fread($this->_handle, 1);
		}
	
		if($ipFlag == chr(2)) {
			$AddrSeek = fread($this->_handle, 3);
			if(strlen($AddrSeek) < 3) {
				return '- System Error';
			}
			$ipFlag = fread($this->_handle, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($this->_handle, 3);
				if(strlen($AddrSeek2) < 3) {
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($this->_handle, $AddrSeek2);
			} else {
				fseek($this->_handle, -1, SEEK_CUR);
			}
	
			while(($char = fread($this->_handle, 1)) != chr(0))
			$this->_addr[1] .= $char;
	
			$AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
			fseek($this->_handle, $AddrSeek);
	
			while(($char = fread($this->_handle, 1)) != chr(0))
			$this->_addr[0] .= $char;
		} else {
			fseek($this->_handle, -1, SEEK_CUR);
			while(($char = fread($this->_handle, 1)) != chr(0))
			$this->_addr[0] .= $char;
	
			$ipFlag = fread($this->_handle, 1);
			if($ipFlag == chr(2)) {
				$AddrSeek2 = fread($this->_handle, 3);
				if(strlen($AddrSeek2) < 3) {
					return '- System Error';
				}
				$AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
				fseek($this->_handle, $AddrSeek2);
			} else {
				fseek($this->_handle, -1, SEEK_CUR);
			}
			while(($char = fread($this->_handle, 1)) != chr(0))
			$this->_addr[1] .= $char;
		}
	
		if(preg_match('/http/i', $this->_addr[1])) {
			$this->_addr[1] = '';
		}
		
		return array(
			@iconv('gb2312', 'utf-8', $this->_addr[0]),
			@iconv('gb2312', 'utf-8', $this->_addr[1])
		);
	}
	
	protected function _filter($string)
	{
		$string = preg_replace('/CZ88\.NET/is', '', $string);
		$string = preg_replace('/^\s*/is', '', $string);
		$string = preg_replace('/\s*$/is', '', $string);
		if(preg_match('/http/i', $string) || $string == '') {
			return false;
		}
		
		return $string;
	}
}

?>