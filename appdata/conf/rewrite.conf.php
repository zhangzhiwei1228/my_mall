<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

return array(

	'domainMapping' => array(#域名路由
		//'user.lhjmall.com' => 'usercp'
	),

	'routes' => array(
		array(#通用模块路由, 若要指定模块路由直接修改:module变量即可
			"type"		 => "module",
			"defaults"	=> "module=default&controller=index&action=default",
			"match"	 => ":module/:controller/:action/*"
		),
		array(
			"type"		 => "regex",
			"match"	 => "^/page/(.*).html",
			"reverse"	 => "page/%s.html",
			"mapping"	=> "module=default&controller=page&action=detail",
			"params"	=> "1=code"
		),
		array(
			"type"		 => "regex",
			"match"	 => "^/item/(\d+).html",
			"reverse"	 => "item/%d.html",
			"mapping"	=> "module=default&controller=goods&action=detail",
			"params"	=> "1=id"
		)
	)
);