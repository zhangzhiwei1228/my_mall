<?php

class Shipping_Zone extends Abstract_Model
{
	protected $_name = 'shipping_zone';
	protected $_primary = 'id';

	public function deleteById($id)
	{
		Shipping_Freight::instance()->delete('zone_id = '.(int)$id);
		return parent::deleteById((int)$id);
	}
	
	/**
	 * 返回物流方式已设置过的区域
	 * @param int $sid
	 * @return array
	 */
	public function getSelectedCountries($sid)
	{
		$zone = M(__CLASS__)->select('countries')
			->where('shipping_id = ?', $sid)
			->fetchCols('countries');

		$selected = array();
		foreach ($zone as $c) {
			$selected = array_merge($selected, explode(',', $c));
		}
		
		return $selected;
	}
}