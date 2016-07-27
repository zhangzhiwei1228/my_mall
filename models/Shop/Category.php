<?php

class Shop_Category extends Abstract_Tree
{
	protected $_name = 'shop_category';
	protected $_primary = 'id';
	
	protected $_referenceMap = array(
		self::CHILDNOTES => array(
			'class' => __CLASS__,
			'type' => 'hasmany',
			'target' => self::PARENT_ID
		)
	);
}