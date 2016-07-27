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
	'member' => array(
		'realname'	=> array('text', '真实姓名'),
		'gender'	=> array('gender','性别',array('男','女')),
		'birthday' 	=> array('birthday','出生日期'),
		'area' 		=> array('area','所在地'),
		'address'	=> array('text','详细地址'),
		'zipcode'	=> array('text','邮编'),
		'major'		=> array('select','职业',array('职业1','职业2','职业3')),
		'qq'		=> array('text','QQ'),
		'wechat' 	=> array('text','微信'),
		'sign' 		=> array('textarea','签名')
	),
	'seller' => array(
		//'company'	=> array('text', '商家名称'),
		'tel' 		=> array('text','电话'),
		'area' 		=> array('area','所在地'),
		'address'	=> array('text','详细地址'),
		'zipcode'	=> array('text','邮编'),
	),
	'resale' => array(),
	'agent' => array(),
);