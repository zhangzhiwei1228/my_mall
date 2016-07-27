<?php

require_once 'runtime.php';
require_once 'func.php';

$beginTime = getMicrotime();
$memory = memory_get_usage();

try {
	Suco_Application::instance()
		->run(SRV_DIR . 'Bootstrap.php', DEBUG);
} catch (Suco_Exception $e) {
	if (DEBUG == 1) {
		echo $e->dump();
	} else {
		echo '对不起，系统出现异常。正在努力排查中...';
	}
}

if (!Suco_Application::instance()->getRequest()->isAjax() && DEBUG == 1) {
	$usedMemory = convert(memory_get_usage() - $memory);
	Suco_Application::instance()->getResponse()->appendBody(
		'<div style="margin-top:20px; background:#333; padding:10px; padding-left:50px; color:#fff; word-break:break-all;">'
		.(Suco_Db::dump())
		.(Suco_Loader::dump())
		.'<p align="right">Use Memory: '.($usedMemory).'</p>'
		.'<p align="right">Time-consuming: '.number_format(getMicrotime()-$beginTime, 5).' ms</p>'
		.'</div>'
	);
}