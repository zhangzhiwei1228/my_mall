<?php

class Shipping_Freight extends Abstract_Model
{
	protected $_name = 'shipping_freight';
	protected $_primary = 'id';

	/**
	 * 解析目的地
	 * @param	Suco_Object $row
	 * @return string
	 */
	public function parseDest($row)
	{
		$cache = Suco_Cache::factory('file');
		if (!$data = $cache->load('all_areas')) {
			$data = M('Region')->select('id, name')->fetchOnKey('id')->toArray();
			$cache->save($data, 3600);
		}
		
		$list = explode(',', $row['destination']);
		foreach($list as $id) {
			$arr[] = $data[$id]['name'];
		}
		return implode(',', $arr);
	}
}