<?php
/**
 * Suco_Controller_Router_Route_Abstract 路由规则抽象
 *
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2008, Suconet, Inc.
 * @license		http://www.suconet.com/license
 * @package		Controller
 * -----------------------------------------------------------
 */

class Suco_Controller_Router_Route_Abstract
{
	/**
	 * 正向解析表达式
	 * @var string
	 */
	protected $_pattern;

	/**
	 * 反向解析表达式
	 */
	protected $_reverse;

	/**
	 * 地址映射
	 * @var array
	 */
	protected $_mapping;

	/**
	 * 参数映射
	 * @var array
	 */
	protected $_params;

	/**
	 * 默认动作
	 * @var array
	 */
	protected $_defaults;

	/**
	 * 过滤符号
	 * @var array
	 */
	protected static $_filter = array(
		0 => array(' ', '/', '&', '+', '=', '%'),
		1 => array('$0d', '$1d', '$2d', '$3d', '$4d', '$5d'),
	);

	/**
	 * 构造函数
	 * @return void
	 */
	public function __construct($pattern = null, $options = null)
	{
		$this->_pattern = $pattern;

		if (isset($options[Suco_Controller_Router_Route::ROUTE_DEFAULTS])) {
			if (!is_array($options[Suco_Controller_Router_Route::ROUTE_DEFAULTS])) {
				parse_str($options[Suco_Controller_Router_Route::ROUTE_DEFAULTS]
					, $options[Suco_Controller_Router_Route::ROUTE_DEFAULTS]);
			}
			$this->_defaults = $options[Suco_Controller_Router_Route::ROUTE_DEFAULTS];
		}
		if (isset($options[Suco_Controller_Router_Route::ROUTE_MAPPING])) {
			if (!is_array($options[Suco_Controller_Router_Route::ROUTE_MAPPING])) {
				parse_str($options[Suco_Controller_Router_Route::ROUTE_MAPPING]
					, $options[Suco_Controller_Router_Route::ROUTE_MAPPING]);
			}
			$this->_mapping = $options[Suco_Controller_Router_Route::ROUTE_MAPPING];
		}
		if (isset($options[Suco_Controller_Router_Route::ROUTE_REVERSE])) {
			$this->_reverse = $options[Suco_Controller_Router_Route::ROUTE_REVERSE];
		}
		if (isset($options[Suco_Controller_Router_Route::ROUTE_PARAMS])) {
			if (!is_array($options[Suco_Controller_Router_Route::ROUTE_PARAMS])) {
				parse_str($options[Suco_Controller_Router_Route::ROUTE_PARAMS]
					, $options[Suco_Controller_Router_Route::ROUTE_PARAMS]);
			}
			$this->_params = $options[Suco_Controller_Router_Route::ROUTE_PARAMS];
		}
	}

	/**
	 * 参数编码
	 *
	 * @param string $str
	 * @return string
	 */
	public function encode($str)
	{
		return str_replace(self::$_filter[0], self::$_filter[1], @trim($str));
	}

	/**
	 * 参数解码
	 *
	 * @param string $str
	 * @return string
	 */
	public function decode($str)
	{
		if (!function_exists('mb_detect_encoding')) {
			die('请安装 mbstring 扩展！');
		}
		if (mb_detect_encoding($str, array('UTF-8','GB2312')) == 'UTF-8') {
			return str_replace(self::$_filter[1], self::$_filter[0], $str);
		} else {
			return iconv('gb2312', 'utf-8', str_replace(self::$_filter[1], self::$_filter[0], $str));
		}
	}

}