<?php

class Order_Delivery extends Abstract_Model
{
	protected $_name = 'order_delivery';
	protected $_primary = 'id';
	
	protected $_referenceMap = array(
		'shipping' => array(
			'class' => 'Shipping',
			'type' => 'hasone',
			'source' => 'shipping_id',
			'target' => 'id'
		)
	);
}
