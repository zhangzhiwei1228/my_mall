<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

/**
 * Suco_Helper_Countdown 倒计时
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Helper
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

class Suco_Helper_Countdown implements Suco_Helper_Interface
{
	public static function callback($args)
	{
		return self::countdown($args[0], $args[1], $args[2]);
	}

	public static function countdown($endTime, $now = 0, $full = 0)
	{
		$string = null;
		if (!$now) {
			$now = time();
		}

		$s = $endTime - $now;

		if ($s >= 60) {
			$i = $s / 60;
			$s = $s % 60;
			if ($i >= 60) {
				$h = $i / 60;
				$i = $i % 60;
				if ($h >= 24) {
					$d = $h / 24;
					$h = $h % 24;
					if ($d >= 30) {
						$m = $d / 30;
						$d = $d % 30;
						if ($m >= 12) {
							$y = $m / 12;
							$m = $m % 12;
						}
					}
				}
			}
		}

		$return = array();
		if (isset($y) && $y > 0) {
			$return[] = intval($y) . '年' . ($y > 1 ? '' : null);
		}
		if (isset($m) && $m > 0) {
			$return[] = intval($m) . '月' . ($m > 1 ? '' : null);
		}
		if (isset($d) && $d > 0) {
			$return[] = intval($d) . '天' . ($d > 1 ? '' : null);
		}
		if (isset($h) && $h > 0) {
			$return[] = intval($h) . '小时' . ($h > 1 ? '' : null);
		}
		if (isset($i) && $i > 0) {
			$return[] = intval($i) . '分钟' . ($i > 1 ? '' : null);
		}
		if (isset($s) && $s > 0) {
			$return[] = intval($s) . '秒' . ($s > 1 ? '' : null);
		}

		if ($return) {
			if ($full) {
				return implode(' ', $return);
			} else {
				return $return[0];
				//return $return[0] . ' ' . @$return[1];
			}
		} else {
			return 0;
		}
	}
}