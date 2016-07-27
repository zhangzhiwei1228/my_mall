<?php

class HelpController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDetail()
	{
		$data = M('Article')->getById($this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('help/detail.php');
	}
}