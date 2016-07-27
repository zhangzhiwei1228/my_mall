<?php

/**
 * Suco_Config_Json Json 配置文件解析类
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Config
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

class Suco_Config_Php extends Suco_Object implements Suco_Config_Interface
{
	/**
	 * 配置文件路径
	 * @var string
	 */
	protected $_file;

	/**
	 * 导入配置数组
	 *
	 * @param array $array
	 * @return void
	 */
	public function import($array)
	{
		foreach ((array)$array as $key => $val) {
			$this->set($key, $val);
		}
		return $this;
	}

	public function export()
	{
		return $this->_data;
	}

	/**
	 * 清空所以配置
	 *
	 * @return void
	 */
	public function clean()
	{
		$this->_data = array();
	}

	/**
	 * 保存配置到指定文件
	 * @param string $file	为空以当前文件名保存
	 * @return void
	 */
	public function save($file = null)
	{
		$file = $file ? $file : $this->_file;
		file_put_contents($file, "<?php\r\n\r\nreturn ".var_export($this->toArray(), true).';');
	}

	/**
	 * 载入并解析配置文件
	 * @param string $file	为空以当前文件名保存
	 * @return void
	 */
	public function load($file)
	{
		if (!is_file($file)) {
			require_once 'Suco/Config/Exception.php';
			throw new Suco_Config_Exception("找不到配置文件 {$file}");
		}

		$this->_file = realpath($file);
		static $caches = array();
		if (!isset($caches[$file])) {
			$caches[$file] = include_once($file);
		}

		$this->import($caches[$file]);
		return $this;
	}
}