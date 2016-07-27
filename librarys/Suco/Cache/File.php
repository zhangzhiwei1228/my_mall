<?php

/**
 * Suco_Cache_File, 文件缓存
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Cache
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 * @example
 *	$cache = new Suco_Cache_File();
 *	if (!$data = $cache->load('block_id')) {
 *		$cache->save('test content...', 3600);
 *	}
 *	echo $data;
 */

class Suco_Cache_File implements Suco_Cache_Interface
{
	/**
	 * 缓存文件保存目录
	 *
	 * @var string
	 */
	protected static $_cacheDirectory = '/tmp/';

	/**
	 * 当前缓存块ID
	 *
	 * @var string
	 */
	protected static $_currentId = 'd1';

	/**
	 * 缓存文件后缀
	 *
	 * @var string
	 */
	protected static $_fileSuffix = '.cache';

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

	/**
	 * 设置缓存目录
	 *
	 * @param string $directory
	 */
	public static function setCacheDirectory($directory)
	{
		self::instance()->_cacheDirectotry = $directory;
	}

	/**
	 * 返回缓存目录
	 */
	public static function getCacheDirectory()
	{
		return self::instance()->_cacheDirectotry;
	}

	/**
	 * 删除缓存块
	 *
	 * @param string|int $id
	 *
	 * @return bool
	 */
	public function delete($id)
	{
		$file = self::instance()->getCacheDirectory() . md5($id) . self::$_fileSuffix;
		return Suco_File::delete($file);
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
		static $cache = array();
		self::$_currentId = $id;
		$file = self::getCacheDirectory() . md5($id) . self::$_fileSuffix;
		if (!is_readable($file)) {
			return false;
		}

		if (!$cache[$id]) {
			$cache[$id] = require_once $file;
		}
		parse_str($cache['header'][$id], $header);

		if (($header['time'] + $header['exp']) > time() || !$header['exp']) {
			if ($header['type'] == 'object') {
				return unserialize($cache[$id]['data']);
			}

			return $cache[$id]['data'];
		}

		return false;
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
		if (!$id) { $id = @self::instance()->_currentId; }
		$file = self::getCacheDirectory() . md5($id) . '.cache';
		$type = gettype($data);
		if ($type == 'object') {
			$data = serialize($data);
		}

		$header = 'time='.time().'&exp='.$exp.'&type='.$type;
		$content = '<?php return array(\'header\'=>'.var_export($header, true).', \'data\'=>'.var_export($data, true).');';
		Suco_File::write($file, $content);
	}

	/**
	 * 清空所有缓存
	 * @return void
	 */
	public function flush()
	{
		Suco_File_Folder::clear(self::instance()->getCacheDirectory());
	}
}