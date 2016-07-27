<?php

class Payment extends Abstract_Model
{
	protected $_name = 'payment';
	protected $_primary = 'id';

	/**
	 * 工厂
	 *
	 * @param string $class
	 * @return object
	 */
	public static function factory($class)
	{
		$class = 'Payment_'.ucfirst($class);
		static $instance = array();
		if (!isset($instance[$class])) {
			if (!class_exists($class)) {
				throw new Suco_Exception("找不到模型 {$class}");
			}

			$instance[$class] = new $class();
		}
		return $instance[$class];
	}
}