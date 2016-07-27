<?php
/**
 * Suco_Loader 装载器
 *
 * @version		3.0 2009/09/01
 * @author		Eric Yu (blueflu@live.cn)
 * @copyright	Copyright (c) 2009, Suconet, Inc.
 * @package		Loader
 * @license		http://www.suconet.com/license
 * -----------------------------------------------------------
 */

require_once 'Suco/Loader/Class.php';

class Suco_Loader extends Suco_Loader_Class
{
	/**
	 * 是否开启自动装载
	 *
	 * @param bool $enabled
	 * @return void
	 */
	public static function setAutoload($enabled = true)
	{
		require_once 'Suco/Loader/Autoload.php';
        if ($enabled === true) {
            Suco_Loader_Autoload::enable();
        } else {
            Suco_Loader_Autoload::disable();
        }
	}

	/**
	 * 打印调试台
	 * 部署模式下请勿使用此方法
	 *
	 * @return string
	 */
	public static function dump()
	{
		$loaded = Suco_Loader_File::getLoaded();
		foreach ($loaded as $i => $file) {
			$output .= '<li>['.$i.'] '.$file.'</li>';
		}

		return '<div style="font-family:\'Courier New\'; font-size:12px; line-height:1.5em; padding:10px; margin:0px;">'
				.'<h2 style="font-weight:bold; font-size:24px;">[FILES LOADED]</h2>'
				.'<ul style="padding:15px; list-style:inside disc;">'
				.$output
				.'</ul>'
				.'<p style="padding:4px; margin-left:20px;">'
				.'Total quantity:<strong>'.count($loaded).'</strong>'
				.'</p>'
				.'</div>';
	}
}
?>