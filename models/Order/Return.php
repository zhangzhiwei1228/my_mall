<?php

class Order_Return extends Abstract_Model
{
	protected $_name = 'order_return';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'order' => array(
			'class' => 'Order',
			'type' => 'hasone',
			'source' => 'order_id',
			'target' => 'id'
		),
		'goods' => array(
			'class' => 'Order_Goods',
			'type' => 'hasone',
			'source' => 'order_goods_id',
			'target' => 'id'
		)
	);
}
