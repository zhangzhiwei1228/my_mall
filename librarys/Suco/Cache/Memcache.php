<?php

/**
 * Suco_Cache_Memcache, Memcache 缓存封装
 * 完全继承原始 Memcache 类的所以特性
 *
 * @version		3.0 2009/09/01
 * @license		http://www.suconet.com/license
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Cache
 *
 * <b>Memcache 应用实例:</b>
 * <code>
 *	$memcache = new Suco_Cache_Memcache();
 *	$memcache->setServer(127.0.0.1);
 *	$memcache->setPort(11211)
 *	if (!$data = $memcache->load('block_id')) {
 *		$memcache->save('test content...', 3600);
 *	}
 *	echo $data;
 *	//test content
 * </code>
 *
 */

class Suco_Cache_Memcache extends Memcache implements Suco_Cache_Interface
{
	protected $_host = '127.0.0.1';
	protected $_port = '11211';
	protected $_persistent = false;
	protected $_timeout = 1;

	protected $_currentId = 'd1';

	/**
	 * 析构函数
	 * 关闭Memcache连接
	 */
	public function __destruct()
	{
		parent::close();
	}

	/**
	 * 单件实例
	 */
	public static function instance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	public function connect($host, $port, $timeout = 1)
	{
		$this->setServer($host);
		$this->setPort($port);
		$this->setConnectTimeout($timeout);

		return parent::connect($host, $port, $timeout);
	}

	/**
	 * 设置是否常连接
	 * @param bool $flag
	 */
	public function setPconnect($flag)
	{
		$this->_persistent = $flag;
	}

	/**
	 * 设置连接超时时间
	 * @param int $timeout
	 */
	public function setConnectTimeout($timeout)
	{
		$this->_timeout = $timeout;
	}

	/**
	 * 设置服务器地址
	 * @param string $host
	 */
	public function setServer($host)
	{
		$this->_host = $host;
	}

	/**
	 * 设置端口地址
	 * @param string $host
	 */
	public function setPort($port)
	{
		$this->_port = $port;
	}

	/**
	 * 取出所有缓存块
	 * @return array
	 */
	public function getAllBlocks($prefix = '')
	{
		$hostMark = "$this->_host:$this->_port";
		$items = $this->getExtendedStats('items');
		$items = $items[$hostMark]['items'];
		
		$data = array();
		foreach((array)$items as $key=>$values){
			$number = $key;
			$str = $this->getExtendedStats("cachedump", $number, 0);
			$line = $str[$hostMark];
			if(is_array($line) && count($line) > 0) {
				foreach($line as $key => $value){
					if (substr($key, 0, strlen($prefix)) == $prefix) {
						$data[$key] = $this->get($key);
					}
				}
			}
		}
		return $data;
	}

	/**
	 * 载入缓存块
	 *
	 * @param string|int $id
	 *
	 * @return mixed
	 */
	public function load($id)
	{
		$this->_currentId = $id;
		return $this->get($id);
	}

	/**
	 * 保存缓存
	 *
	 * @param mixed $data 数据
	 * @param mixed $exp 有效期
	 * @param mixed $id 块ID
	 *
	 * @return mixed
	 */
	public function save($data, $exp = 0, $id = null)
	{
		if (!$id) {
			$id = $this->_currentId;
		}
		return $this->set($id, $data, 0, $exp);
	}

	/**
	 * 删除缓存块
	 * @param  string $id 块ID
	 * @return bool
	 */
	public function delete($id)
	{
		return parent::delete($id);
	}

	/**
	 * 清空所有缓存
	 * @return void
	 */
	public function flush()
	{
		return parent::flush();
	}
}