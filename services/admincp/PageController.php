<?php

class Admincp_PageController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '保存成功，系统已更新您的设置！',
				'links' => array(
					array('返回上一页', '&success')
				),
				'autoback' => array('自动返回上一页', '&success'),
			), 'success');
		}

		$data = M('Page')->getById((int)$this->_request->id);
		if ($this->_request->isPost()) {
			M('Page')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->pages = M('Page')->select()->order('id ASC')->fetchRows();
		$view->data = $data;
		$view->render('page/index.php');
	}

	public function doList()
	{
		$select = M('Page')->select()
			->order('id DESC')
			->paginator(20, (int)$this->_request->page);

		if ($this->_request->q) {
			$select->where('mark LIKE ? OR title LIKE ?', "%{$this->_request->q}%");
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('page/list.php');
	}
}