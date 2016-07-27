<?php

if(!defined('APP_KEY')) { exit('Access Denied'); }

class Helper_Url implements Suco_Helper_Interface
{
	protected $_url;
	protected $_route;
	
	public static function callback($args)
	{
		return @new self($args[0], $args[1]);
	}

	public function __construct($url = null, $route = null)
	{
		if ((stristr($url, '/') || !stristr($url, '=')) 
			&& !stristr($url, 'http://')
				&& !stristr($url, 'https://')
					&& !stristr($url, '.html')
						&& substr($url, 0, 1) != '&') {
			$url = trim($url, '/');
			list($p, $q) = explode('?', $url);
			$arr = explode('/', $p);

			if ($arr[0] == '.') {
				$query = array(
					'action' => (string)$arr[1]
				);
			} elseif (Suco_Application::instance()->getDispatcher()->isModule($arr[0])) {
				$query = array(
					'module' => (string)$arr[0],
					'controller' => (string)$arr[1],
					'action' => (string)$arr[2]
				);
			} else {
				$query = array(
					'controller' => (string)$arr[0],
					'action' => (string)$arr[1]
				);
			}
			$url = http_build_query($query);
			if ($q) $url.='&'.$q;
		}

		$this->_url = $url;
		$this->_route = $route;
	}

	public function encode($param)
	{
		return Suco_Controller_Router_Route_Abstract::encode($param);
	}

	public function decode($param)
	{
		return Suco_Controller_Router_Route_Abstract::decode($param);
	}

	public function __toString()
	{
		if (!$this->_url) { return ''; }

		list($url, $author) = explode('#', $this->_url);

		return Suco_Application::instance()
			->getRouter()
			->reverse($url, $this->_route) . ($author ? '#'.$author : '');
	}
}