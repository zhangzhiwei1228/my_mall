<?php

class IndexController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}
	
	public function doDefault()
	{
		$view = $this->_initView();
		//$view->render('index.php');
		$view->render('views/welcome.php');
	}
}