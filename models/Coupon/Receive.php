<?php

class Coupon_Receive extends Abstract_Model
{
	protected $_name = 'coupon_receive';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'coupon' => array(
			'class' => 'Coupon',
			'type' => 'hasone',
			'source' => 'coupon_id',
			'target' => 'id'
		)
	);
}
