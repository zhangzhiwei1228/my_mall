<?php

class Admincp_ShopController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('sc.name AS cate_name, s.*')
			->order('s.id DESC')
			->paginator($pageSize, $currentPage);

		//按分类查找
		if ($this->_request->cid) {
			$ids = M('Shop_Category')->getChildIds((int)$this->_request->cid);
			$select->where('s.category_id IN ('.($ids ? $ids : $this->_request->cid).')');
		}
		//按关键词查找
		if ($this->_request->q) {
			$select->where('s.name LIKE ?', '%'.$this->_request->q.'%');
		}
		$clotypes = M('Coltypes')->select('name')->where("english = 'shop'")->fetchRows()->toArray();
		$view = $this->_initView();
		$view->clotypes = $clotypes;
		$view->category = M('Shop_Category')->getById((int)$this->_request->cid);
		$view->categories = M('Shop_Category')->select()->fetchRows()->toTreeList();
		$view->datalist = $select->fetchRows();
		$view->render('shop/list.php');
	}

	public function doDetail()
	{
		$data = M('Shop')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->prevdata = M('Shop')->select()->where('id > ?', $data['id'])->order('id ASC')->fetchRow();
		$view->nextdata = M('Shop')->select()->where('id < ?', $data['id'])->order('id DESC')->fetchRow();
		$view->paths = M('Shop_Category')->getPath((int)$data['cid']);
		$view->render('shop/detail.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Shop')->insert(array_merge($this->_request->getPosts(), $this->_request->getFiles()));
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list&cid=' . $_POST['cid']);
		}

		$view = $this->_initView();
		$clotypes = M('Coltypes')->select('name')->where("english = 'shop'")->fetchRows()->toArray();
		$view->categories = M('Shop_Category')->toTreeList(M('Shop_Category')->select()->fetchRows()->toArray());
		$view->clotypes = $clotypes;
		$view->render('shop/input.php');
	}

	public function doEdit()
	{
		$data = M('Shop')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			M('Shop')->updateById(array_merge($this->_request->getPosts(), $this->_request->getFiles()), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$clotypes = M('Coltypes')->select('name')->where("english = 'shop'")->fetchRows()->toArray();
		$view->data = $data;
		$view->clotypes = $clotypes;
		$view->categories = M('Shop_Category')->toTreeList(M('Shop_Category')->select()->fetchRows()->toArray());
		$view->render('shop/input.php');
	}

	public function doToggleStatus()
	{
		$fields = array('is_new', 'is_hot', 'is_rec', 'is_selling');
		if (in_array($this->_request->t, $fields)) {
			$field = $this->_request->t;
			$data[$field] = abs($this->_request->v - 1);
			M('Shop')->updateById($data, (int)$this->_request->id);
		} elseif ($this->_request->t == 'is_checked') {
			$data['is_checked'] = $this->_request->v;
			M('Shop')->updateById($data, (int)$this->_request->id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doBatch()
	{
		switch($_POST['act']) {
			case 'delete':
				foreach ((array)$_POST['ids'] as $id) {
					M('Shop')->deleteById((int)$id);
				}
				break;
			case 'move':
				if ($_POST['cid']) {
					foreach ((array)$_POST['ids'] as $id) {
						M('Shop')->updateById('category_id = '.(int)$_POST['cid'], (int)$id);
					}
				}
				break;
		}
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}