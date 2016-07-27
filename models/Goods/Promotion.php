<?php

class Goods_Promotion extends Abstract_Model
{
	protected $_name = 'goods_promotion';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if (isset($data['start_time']) && !is_numeric($data['start_time'])) {
			$data['start_time'] = strtotime($data['start_time']);
		}
		if (isset($data['end_time']) && !is_numeric($data['end_time'])) {
			$data['end_time'] = strtotime($data['end_time']);
		}

		return parent::filter($data);
	}
}