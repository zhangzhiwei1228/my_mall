<?php
/**
 * Suco_Validator_NotEmpty 非空验证
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Validator
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

class Suco_Validator_NotEmpty
{
	public function isValid($str)
	{
		if (empty($str)) {
			return false;
		}
		return true;
	}
}