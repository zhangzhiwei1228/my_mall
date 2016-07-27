<?php

class Goods_Attribute extends Abstract_Model
{
	protected $_name = 'goods_attribute';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		$data['attr_key'] = md5(trim($data['attr_name']).trim($data['attr_value']));
		return parent::inputFilter($data);
	}
}