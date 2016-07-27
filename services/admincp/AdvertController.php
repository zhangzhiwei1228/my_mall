<?php

class Admincp_AdvertController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Advert')->select()
			->order('id DESC')
			->paginator(20, (int)$this->_request->page);

		if ($this->_request->q) {
			$select->where('mark LIKE ? OR name LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('advert/list.php');
	}

	public function doSetting()
	{
		$data = M('Advert')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('Advert_Element')->select()
			->where('advert_id = ?', (int)$data['id'])
			->order('rank ASC, id DESC')
			->paginator($pageSize, $currentPage);

		$view = $this->_initView();
		$view->data = $data;
		$view->elements = $select->fetchRows();
		$view->render('advert/setting.php');
	}

	public function doGetCode()
	{
		$data = M('Advert')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('advert/code.php');
	}
}