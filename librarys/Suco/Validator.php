<?php
/**
 * Suco_Validator 数据验证类
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Validator
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

class Suco_Validator
{
	public function is($validate, $str, $opts = array())
	{
		$className = 'Suco_Validator_' . ucfirst($validate);
		$object = new $className();
		return $object->isValid($str, $opts);
	}
}