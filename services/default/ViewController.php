<?php

class ViewController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$dir = WWW_DIR.'themes/123mf.com/views';
		$files = Suco_File_Folder::read($dir);

		echo '<pre>';
		print_r($files);

		die;
	}

	public function __call($method, $args = array())
	{
		if ($method != 'doDefault') {
			$tpl = strtolower(substr($method, 2));

			$view = $this->_initView();
			$view->render('views/'.$tpl.'.php');
		} else {
			parent::__call($method, $args);
		}
	}
}