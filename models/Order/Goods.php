<?php

class Order_Goods extends Abstract_Model
{
	protected $_name = 'order_goods';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'order' => array(
			'class' => 'Order',
			'type' => 'hasone',
			'source' => 'order_id',
			'target' => 'id'
		),
		'package' => array(
			'class' => 'Goods_Package',
			'type' => 'hasone',
			'source' => 'package_id',
			'target' => 'id'
		)
	);

	public function outputFilter($data)
	{
		if (isset($data['is_return'])) {
			switch($data['is_return']) {
				case 1:	
					$data['return_text'] = '退换中'; 
					break;
				case 2:	
					$data['return_text'] = '已退换'; 
					break;
				case 3:	
					$data['return_text'] = '退换失败'; 
					break;
			}
		}

		return $data;
	}
}