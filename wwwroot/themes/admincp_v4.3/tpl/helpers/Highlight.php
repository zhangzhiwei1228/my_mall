<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

class Helper_Highlight implements Suco_Helper_Interface
{
	protected $_str;
	protected $_keyword;
	
	public static function callback($args)
	{
		return new self($args[0], $args[1]);
	}
	
	public function __construct($str, $keyword)
	{
		$this->_str = $str;
		$this->_keyword = $keyword;
	}
	
	public function __toString()
	{
		$str = $this->_str;
		if (!$str) {
			return '';
		}
		
		if (!empty($this->_keyword)) {
			$keywords = explode(" ", $this->_keyword);
			foreach ($keywords as $keyword) {
				$str = preg_replace('#('.$keyword.')#i', '<font color="red">$1</font>', $str);
			}
		}
		return $str;
	}
}