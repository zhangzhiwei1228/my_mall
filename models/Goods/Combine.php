<?php

class Goods_Combine extends Abstract_Model
{
	protected $_name = 'goods_combine';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if ($data['goods_ids']) {
			$data['goods_num'] = @count(explode(',',$data['goods_ids']));
		}
		
		if (isset($data['setting'])) {
			$packagePrice = 0;
			foreach ($data['setting'] as $row) {
				$originalPrice += $row['original_price'];
				$packagePrice += $row['package_price'];
			}
			$data['original_price'] = $originalPrice;
			$data['package_price'] = $packagePrice;
			$data['setting'] = serialize($data['setting']);
		}

		return parent::inputFilter($data);
	}

	public function outputFilter($data)
	{
		if (isset($data['setting'])) {
			$data['setting'] = unserialize($data['setting']);
		}
		return parent::outputFilter($data);
	}
}