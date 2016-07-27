<?php

define('DS',				DIRECTORY_SEPARATOR);
define('BASE',				dirname(dirname(__FILE__)).DS);
define('ROOT',				dirname(dirname(BASE)).DS);

define('SRV_DIR',			BASE.'services'.DS);
define('LIB_DIR',			BASE.'librarys'.DS);
define('MOD_DIR',			BASE.'models'.DS);
define('WWW_DIR',  			BASE.'wwwroot'.DS);

define('LOG_DIR',			BASE.'appdata'.DS.'logs'.DS);
define('CONF_DIR',			BASE.'appdata'.DS.'conf'.DS);
define('LANG_DIR',			BASE.'appdata'.DS.'langs'.DS);
define('FONT_DIR',			BASE.'appdata'.DS.'fonts'.DS);
define('MTPL_DIR',			BASE.'appdata'.DS.'mailtpls'.DS);
define('CACHE_DIR',			BASE.'appdata'.DS.'caches'.DS);
define('SCWS_DIR',			BASE.'appdata'.DS.'scws'.DS);

define('APP_KEY',			'4eb45084daa1c80f51f1f3d4bad74633﻿');
define('APP_VER',			'3.2.1 Beta');
define('DEBUG',				0);

define('LOGIN_TIMEOUT',		3600);	#登陆超时时间
define('ONLINE_TIMEOUT',	180);	#在线超时时间	此时间内检测不到操作。系统认为用户离线

define('TIME_FORMAT',		'H:i:s');
define('DATE_FORMAT',		'Y/m/d');
define('DATETIME_FORMAT',	'Y/m/d H:i');

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
header('Content-Type:text/html; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
session_cache_limiter('private, must-revalidate');
session_start();
ob_start('ob_gzhandler');

set_include_path(implode(PATH_SEPARATOR, array(
	realpath(MOD_DIR),	realpath(SRV_DIR),	realpath(LIB_DIR), get_include_Path()
)));

require_once 'Suco/Application.php';
require_once 'Exception.php';

//设置私有模型目录
Suco_Model::appendModelDirectory(MOD_DIR);

?>
