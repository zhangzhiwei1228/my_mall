<?php

class PageController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDetail()
	{
		$data = M('Page')->getByCode($this->_request->code);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('page.php');
	}

	/**
	 * 返回JSON
	 */
	public function doGetContent()
	{
		$page = M('Page')->getByCode($this->_request->code);
			echo $page->content;
	}
}