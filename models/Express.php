<?php

class Express
{
	/**
	 * 工厂
	 *
	 * @param string $class
	 * @return object
	 */
	public static function factory($class)
	{
		$class = 'Express_'.ucfirst($class);
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