<?php

class Goods_Type extends Abstract_Model
{
	protected $_name = 'goods_type';
	protected $_primary = 'id';
	
	public function inputFilter($data)
	{
		if (isset($data['attr_setting']) && is_array($data['attr_setting'])) {
			foreach ($data['attr_setting'] as $i => $row) { 
				if (!$row['attr_name']) continue;
				$arr[] = $row;
			}
			$data['attr_setting'] = serialize($arr);
		}
		return parent::inputFilter($data);
	}
	
	public function outputFilter($data)
	{
		if (isset($data['attr_setting']) && !is_array($data['attr_setting'])) {
			$data['attr_setting'] = unserialize($data['attr_setting']);
		}
		return parent::outputFilter($data);
	}
}