<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

return array(
	'img_gateway' => null,
	'img_watermark' => array(
		'enabled' => 0,
		'src' => './static/img/watermark.gif',
		'pos' => 'bottomRight'
	),
	'img_allow_sizes' => array(
		'60x60','100x100','160x160','220x220','300x300','400x400','500x500','600x600'
	)
);