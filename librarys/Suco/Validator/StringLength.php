<?php
/**
 * Suco_Validator_StringLength 字符长度验证
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Validator
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

class Suco_Validator_StringLength
{
	public function isValid($str, $min, $max)
	{
		if (strlen($str) < $min) {
			return false;
		}
		if (strlen($str) > $max) {
			return false;
		}
		return true;
	}
}