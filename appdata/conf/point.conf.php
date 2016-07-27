<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

/*
 * 会员扩展字段
 * 格式说明：
 * array(
 *	'字段名' => array(string 类型, string 中文标签, array 可选参数)
 * );
 */

return array(
	'ext1' => array(
		'name'=>'现金+免费积分', 
		'rate'=>100
	),	
	'ext2' => array(
		'name'=>'现金+积分币', 
		'rate'=>50
	),	
	// 'ext3' => array(
	// 	'name'=>'积分币', 
	// 	'rate'=>1
	// ),
	// 'ext4' => array(
	// 	'name'=>'积分三', 
	// 	'rate'=>1
	// ),
);