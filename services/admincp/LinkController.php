<?php

class Admincp_LinkController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Link')->select()
			->order('id DESC')
			->paginator(20, (int)$this->_request->page);

		if (isset($this->_request->g)) {
			if ($this->_request->g == '默认分组') {
				$select->where('`group` = ""');
			} else {
				$select->where('`group` = ?', $this->_request->g);
			}
		}

		if ($this->_request->q) {
			$select->where('site LIKE ? OR url LIKE ?', "%{$this->_request->q}%");
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->groups = M('Link')->select('`group`')
			->where('`group` != \'\'')
			->group('`group`')
			->fetchCols('group');
		$view->render('link/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Link')->insert(array_merge($this->_request->getPosts(), $this->_request->getFiles()));
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->groups = M('Link')->select('`group`')
			->where('`group` != \'\'')
			->group('`group`')
			->fetchCols('group');
		$view->render('link/input.php');
	}

	public function doEdit()
	{
		$data = M('Link')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new App_Exception('Not found.');
		}
		if ($this->_request->isPost()) {
			M('Link')->updateById(array_merge($this->_request->getPosts(), $this->_request->getFiles()), $this->_request->id);
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->groups = M('Link')->select('`group`')
			->where('`group` != \'\'')
			->group('`group`')
			->fetchCols('group');
		$view->render('link/input.php');
	}
}