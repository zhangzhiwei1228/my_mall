<?php

class Admincp_SeoController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Seo')->select()
			->order('id DESC')
			->paginator(20, (int)$this->_request->page);

		if ($this->_request->q) {
			$select->where('`match` LIKE ? OR title LIKE ?', "%{$this->_request->q}%");
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('seo/list.php');
	}
}