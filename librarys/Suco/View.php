<?php
/**
 * Suco_View 视图类
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		View
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

require_once 'Suco/View/Abstract.php';
require_once 'Suco/View/Interface.php';

class Suco_View extends Suco_View_Abstract implements Suco_View_Interface
{
	/**
	 * 主题目录
	 * @var string
	 */
	protected $_themePath;


	/**
	 * 视图布局
	 * @var string
	 */
	protected $_layoutPath;

	/**
	 * 当前布局文件
	 * @var string
	 */
	protected $_layoutFile;

	/**
	 * 是否已载入布局
	 * @var bool
	 */
	protected $_layoutLoaded = false;

	/**
	 * 魔术方法
	 * 调用辅助类
	 *
	 * @param string $name
	 * @param array $args
	 * @return void
	 */
	public function __call($name, $args)
	{
		$helper = Suco_Helper::factory($name);
		return call_user_func_array(array($helper, 'callback'), array($args));
	}

	/**
	 * 设置主题目录
	 *
	 * @param string $path
	 * @return object
	 */
	public function setThemePath($path)
	{
		$this->_themePath = $path;
		return $this;
	}

	/**
	 * 返回主题目录
	 *
	 * @return string
	 */
	public function getThemePath()
	{
		return $this->_themePath;
	}

	/**
	 * 设置布局文件
	 *
	 * @param string $file
	 * @return object
	 */
	public function setLayout($file)
	{
		$this->_layoutFile = $file;
		return $this;
	}

	/**
	 * 返回布局文件
	 *
	 * @return string
	 */
	public function getLayout()
	{
		return $this->_layoutFile;
	}

	/**
	 * 设置布局路径
	 *
	 * @param string $path
	 * @return object
	 */
	public function setLayoutPath($path)
	{
		$this->_layoutPath = $path;
		return $this;
	}

	/**
	 * 返回布局路径
	 *
	 * @return string
	 */	
	public function getLayoutPath()
	{
		return $this->_layoutPath;
	}

	/**
	 * 设置辅助类路径
	 *
	 * @param string $path
	 * @return object
	 */
	public function setHelperPath($path)
	{
		Suco_Helper::setHelperPath($path);
		return $this;
	}

	/**
	 * 返回辅助类路径
	 *
	 * @return string
	 */
	public function getHelperPath()
	{
		return Suco_Helper::getHelperPath;
	}

	/**
	 * 渲染并返回视图
	 *
	 * @param string $file
	 * @param array $data
	 * @return string
	 */
	public function output($file, $data = null)
	{
		if ($data) {
			$this->assign($data);
		}

		//渲染视图
		$content = $this->_render($file, $this->_scriptPath);

		//渲染布局
		if ($layout = $this->getLayout()) {
			$this->layout = $this->layout();
			$this->layout->content = $content;
			$content = $this->_render($layout, $this->_layoutPath);
		}
		return $content;
	}

	/**
	 * 渲染并显示视图
	 *
	 * @param string $file
	 * @param array $data
	 * @return string
	 */
	public function render($file, $data = null)
	{
		if ($data) {
			$this->assign($data);
		}

		//渲染视图
		$content = $this->_render($file, $this->_scriptPath);

		//渲染布局
		if ($layout = $this->getLayout()) {
			$this->layout = $this->layout();
			$this->layout->content = $content;
			$content = $this->_render($layout, $this->_layoutPath);
		}
		$this->getResponse()->appendBody($content);

		return $content;
	}

	/**
	 * 渲染并显示视图块
	 * 此方法不加载视图的布局
	 *
	 * @param string $file
	 * @param array $data
	 * @return string
	 */
	public function partial($file, $data = null)
	{
		if ($data) {
			$this->assign($data);
		}

		echo $this->_render($file, $this->_scriptPath);
	}

	/**
	 * 捕捉片断开始
	 * 使用此方法时，系统会忽略片断之前和之后的内容，只显示被捕捉到的部分
	 * 如:<code>
	 *
	 * echo 'before output';
	 * $view = new Suco_View();
	 * $view->fragmentStart()
	 * echo '这里是被捕捉到的片断';
	 * $view->fragmentEnd();
	 * echo 'after output';
	 *
	 * #output
	 * 这里是被捕捉到的片断
	 *
	 * </code>
	 *
	 * @return void
	 */
	public function fragmentStart()
	{
		ob_get_clean();
		ob_start();
	}

	/**
	 * 捕捉片断结束
	 *
	 * @return void
	 */
	public function fragmentEnd()
	{
		echo ob_get_clean(); exit;
	}

	/**
	 * 渲染视图
	 *
	 * @return string
	 */
	protected function _render($file, $path = null)
	{
		ob_start();
		$file = str_replace('/', DIRECTORY_SEPARATOR, $path . $file);

		if (!is_file($file)) {
			require_once 'Suco/View/Exception.php';
			throw new Suco_View_Exception("找不到视图 [$file]");
		}

		$v = $view = &$this;

		require $file;

		$site = Suco_Application::instance()->getRequest()->getHost();
		$site = $site ? trim($site, '/').'/' : '';

		$baseUrl = Suco_Application::instance()->getRequest()->getBasePath();
		$baseUrl = $baseUrl ? trim($baseUrl, '/').'/' : '';

		return str_replace('./', $site.$baseUrl.trim($this->getThemePath(),'/').'/', ob_get_clean());
	}

	/**
	 * @return bool 判断浏览器
	 */
	public function isMobile(){
		$isMobile = false;
		$isBot = false;
		$op = isset($_SERVER['HTTP_X_OPERAMINI_PHONE'])?strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']):'';
		$ua = isset($_SERVER['HTTP_USER_AGENT'])?strtolower($_SERVER['HTTP_USER_AGENT']):'';
		$ac = strtolower($_SERVER['HTTP_ACCEPT']);
		$ip = $_SERVER['REMOTE_ADDR'];
		$isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
			|| $op != ''
			|| strpos($ua, 'sony') !== false
			|| strpos($ua, 'symbian') !== false
			|| strpos($ua, 'nokia') !== false
			|| strpos($ua, 'samsung') !== false
			|| strpos($ua, 'mobile') !== false
			|| strpos($ua, 'windows ce') !== false
			|| strpos($ua, 'epoc') !== false
			|| strpos($ua, 'opera mini') !== false
			|| strpos($ua, 'nitro') !== false
			|| strpos($ua, 'j2me') !== false
			|| strpos($ua, 'midp-') !== false
			|| strpos($ua, 'cldc-') !== false
			|| strpos($ua, 'netfront') !== false
			|| strpos($ua, 'mot') !== false
			|| strpos($ua, 'up.browser') !== false
			|| strpos($ua, 'up.link') !== false
			|| strpos($ua, 'audiovox') !== false
			|| strpos($ua, 'blackberry') !== false
			|| strpos($ua, 'ericsson,') !== false
			|| strpos($ua, 'panasonic') !== false
			|| strpos($ua, 'philips') !== false
			|| strpos($ua, 'sanyo') !== false
			|| strpos($ua, 'sharp') !== false
			|| strpos($ua, 'sie-') !== false
			|| strpos($ua, 'portalmmm') !== false
			|| strpos($ua, 'blazer') !== false
			|| strpos($ua, 'avantgo') !== false
			|| strpos($ua, 'danger') !== false
			|| strpos($ua, 'palm') !== false
			|| strpos($ua, 'series60') !== false
			|| strpos($ua, 'palmsource') !== false
			|| strpos($ua, 'pocketpc') !== false
			|| strpos($ua, 'smartphone') !== false
			|| strpos($ua, 'rover') !== false
			|| strpos($ua, 'ipaq') !== false
			|| strpos($ua, 'au-mic,') !== false
			|| strpos($ua, 'alcatel') !== false
			|| strpos($ua, 'ericy') !== false
			|| strpos($ua, 'up.link') !== false
			|| strpos($ua, 'vodafone/') !== false
			|| strpos($ua, 'wap1.') !== false
			|| strpos($ua, 'wap2.') !== false;

		$isBot =  $ip == '66.249.65.39'
			|| strpos($ua, 'googlebot') !== false
			|| strpos($ua, 'mediapartners') !== false
			|| strpos($ua, 'yahooysmcm') !== false
			|| strpos($ua, 'baiduspider') !== false
			|| strpos($ua, 'msnbot') !== false
			|| strpos($ua, 'slurp') !== false
			|| strpos($ua, 'ask') !== false
			|| strpos($ua, 'teoma') !== false
			|| strpos($ua, 'spider') !== false
			|| strpos($ua, 'heritrix') !== false
			|| strpos($ua, 'attentio') !== false
			|| strpos($ua, 'twiceler') !== false
			|| strpos($ua, 'irlbot') !== false
			|| strpos($ua, 'fast crawler') !== false
			|| strpos($ua, 'fastmobilecrawl') !== false
			|| strpos($ua, 'jumpbot') !== false
			|| strpos($ua, 'googlebot-mobile') !== false
			|| strpos($ua, 'yahooseeker') !== false
			|| strpos($ua, 'motionbot') !== false
			|| strpos($ua, 'mediobot') !== false
			|| strpos($ua, 'chtml generic') !== false
			|| strpos($ua, 'nokia6230i/. fast crawler') !== false;

		// 对IPAD排除
		if (strpos($ua, 'ipad') !== false) {
			$isMobile = false;
		}
		return $isMobile;
	}

	/**
	 * @return bool 返回是否是手机浏览器
	 */
	public function is_mobile_check() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if($this->isMobile()){
			if (strpos($user_agent,'MicroMessenger') === false) {
				return true;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
}