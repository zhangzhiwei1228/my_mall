<?php

class User_Bank extends Abstract_Model
{
	protected $_name = 'user_bank';
	protected $_primary = 'id';

	public function getBackOpts()
	{
		return array(
			'icbc' => '工商银行',
			'abc' => '农业银行',
			'cmb'=> '招商银行',
			'ccb' => '建设银行',
			'bccb' => '北京银行',
			'boc' => '中国银行',
			'bocom' => '交通银行',
			'cmbc' => '民生银行',
			'bos' => '上海银行', 
			'cbhb' => '渤海银行',
			'ceb' => '光大银行',
			'cib' => '兴业银行',
			'citic' => '中信银行',
			'czb' => '浙商银行',
			'cgb' => '广发银行',
			'hkbea' => '东亚银行',
			'hxb' => '华夏银行',
			'hzcb' => '杭州银行',
			'njcb' => '南京银行',
			'pingan' => '平安银行',
			'psbc' => '邮储银行',
			'sdb' => '深发银行',
			'spdb' => '浦发银行',
			'bjrcb' => '北京农村商业银行',
			'srcb' => '上海农村商业银行',
		);
	}
}