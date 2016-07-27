<?php

class NewsController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$select = M('Article')->select()
			->where('is_checked = 2')
			->paginator(20, $this->_request->page);

		if ($this->_request->cid) {
			$ids = M('Article_Category')->getChildIds((int)$this->_request->cid);
			$select->where('category_id IN ('.($ids ? $ids : 0).')');
		}
		$select->order('id DESC');

		$view = $this->_initView();
		$view->category = M('Article_Category')->getById((int)$this->_request->cid);
		$view->datalist = $select->fetchRows();
		$view->render('news/list.php');
	}

	public function doDetail()
	{
		$data = M('Article')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('news/detail.php');
	}
}