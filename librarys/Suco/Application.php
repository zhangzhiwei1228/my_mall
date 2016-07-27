<?php
/**
 * Suco_Application 框架主程序
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2008, Suconet, Inc.
 * @package		Application
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

require_once 'Suco/Loader.php';
require_once 'Suco/Controller/Front.php';
Suco_Loader::setAutoload(true);

class Suco_Application extends Suco_Controller_Front
{
	/**
	 * 单件实例
	 *
	 * @return object
	 */
	public static function instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * 设置配置对象
	 *
	 * @param object $conf
	 * @return object
	 */
	public function setConfigure($conf)
	{
		$this->_configure = $conf;
	}

	/**
	 * 返回配置对象
	 *
	 * @return object
	 */
	public function getConfigure()
	{
		return $this->_configure;
	}

	/**
	 * 返回版本号
	 *
	 * @return string
	 */
	public function getVersion()
	{
		return 'V5.3.1 release';
	}
}
