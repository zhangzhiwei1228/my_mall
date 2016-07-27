<?php

class Admincp_ArticleCategoryController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Article_Category')->select()
			->where('parent_id = ?', $this->_request->cid ? $this->_request->cid : 0)
			->order('rank ASC, id ASC');
		
		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('article/category/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '分类添加成功！',
				'links' => array(
					array('继续添加', '&success'),
					array('返回列表', '&action=list')
				),
				'autoback' => array('自动返回上一页', '&success'),
			), 'success');
		}

		if ($this->_request->isPost()) {
			M('Article_Category')->insert($this->_request->getPosts());
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->categories = M('Article_Category')->getChilds()->toTreeList();
		$view->render('article/category/input.php');
	}

	public function doEdit()
	{
		$data = M('Article_Category')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			M('Article_Category')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$path = $data['path'] . ',' . $data['id'];

		$view = $this->_initView();
		$view->data = $data;
		$view->categories = M('Article_Category')->getItemsNoSelfChild((int)$this->_request->id)->toTreeList();
		$view->render('article/category/input.php');
	}

	public function doGetJson()
	{
		echo M('Article_Category')->select('id, parent_id, name, level')
			->order('id ASC, rank ASC')
			->fetchOnKey('id')
			->toJson();
	}
}