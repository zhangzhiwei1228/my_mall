<?php

class Bootstrap extends Suco_Bootstrap
{
	public function init()
	{
		require_once 'Exception.php';

		#记录访问来路
		if (!isset($_SESSION['ref'])) {
			$_SESSION['ref'] = (string)$_SERVER['HTTP_REFERER'];
		}

		#记录推荐人
		if (isset($_GET['recid'])) {
			$_SESSION['recid'] = $_GET['recid'];
		}

		#设置言语包目录及默认语言
		if (isset($_GET['lang'])) {
			$_SESSION['lang'] = $_GET['lang'];
		}

		#设置语言包目录
		Suco_Locale::instance()->setPath(LANG_DIR);

		#设置当前语言
		Suco_Locale::instance()->setLanguage(isset($_SESSION['lang']) ? $_SESSION['lang'] : 'zh_CN');

		#添加全局语言包
		Suco_Locale::instance()->addPackage('global');

		#设置文件缓存目录
		Suco_Cache_File::instance()->setCacheDirectory(CACHE_DIR);

		#设置模块目录
		Suco_Application::instance()
			->getDispatcher()
			->setControllerDirectory(SRV_DIR.'admincp', 'admincp')
			->setControllerDirectory(SRV_DIR.'default', 'default')
			->setControllerDirectory(SRV_DIR.'usercp', 'usercp')
			->setControllerDirectory(SRV_DIR.'agent', 'agent');

		#记录当前位置
		Suco_Application::instance()->getRequest()->url = urlencode(base64_encode(
			Suco_Application::instance()->getRequest()->getRequestUri())
		);

		#URL重写
		Suco_Application::instance()->getRouter()
			->setOptions(Suco_Config::factory(CONF_DIR.'rewrite.conf.php')->toArray());
	}
}