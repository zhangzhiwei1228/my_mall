<?php

class Admincp_GoodsCombineController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('Goods_Combine')->select()
			->order('id DESC')
			->paginator($pageSize, $currentPage);

		if ($this->_request->q) {
			$select->where('name LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/combine/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$id = M('Goods_Combine')->insert($this->_request->getPosts());
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list&cid=' . $this->_request->getPost('category_id'));
		}

		$view = $this->_initView();
		$view->render('goods/combine/input.php');
	}

	public function doEdit()
	{
		$data = M('Goods_Combine')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			M('Goods_Combine')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/combine/input.php');
	}

	public function doGoods()
	{
		$select = M('Goods')->select()
			->where('is_selling = 1')
			->order('id DESC')
			->paginator(6, $this->_request->page1);

		$ids = $this->_request->goods_ids;
		if ($ids) {
			$select->where('id NOT IN ('.($ids ? $ids : 0).')');
		}

		if ($this->_request->cid) {
			$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
			$select->where('category_id IN ('.($ids ? $ids : 0).')');
		}
		if ($this->_request->q) {
			$select->where('title LIKE ?', "%{$this->_request->q}%");
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->datalist = $select->fetchRows();
		$view->render('goods/combine/goods.php');
	}

	public function doSelected()
	{
		$data = M('Goods_Activity')->getById((int)$this->_request->pid);
		$ids = $this->_request->goods_ids;
		$select = M('Goods')->select('*')
			->where('is_selling = 1 AND id IN ('.($ids ? $ids : 0).')')
			->order(array('substring_index(\''.$ids.'\',id,1) DESC'))
			->paginator(6, $this->_request->page2);
		if ($this->_request->q2) {
			$select->where('title LIKE ?', "%{$this->_request->q2}%");
		}

		$datalist = $select->fetchRows();
		$setting = $data['setting'];
		foreach ($datalist as $i => $row) {
			$id = $row['id'];
			if (isset($setting[$id])) {
				$row['price'] = $setting[$id]['price'];
			}
			$datalist->set($i, $row);
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->datalist = $datalist;
		$view->render('goods/combine/selected.php');
	}
}