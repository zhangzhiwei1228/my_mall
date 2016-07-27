<?php

class Admincp_RegionController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}
	
	public function doDefault()
	{
		$this->redirect('action=list');	
	}

	public function doList()
	{
		$select = M('Region')->select()
			->where('parent_id = ?', $this->_request->cid ? $this->_request->cid : 0)
			->order('rank ASC, id ASC');
		
		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('region/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '产区添加成功！',
				'links' => array(
					array('继续添加', '&success'),
					array('返回列表', '&action=list')
				),
				'autoback' => array('自动返回上一页', '&success'),
			), 'success');
		}

		if ($this->_request->isPost()) {
			M('Region')->insert($this->_request->getPosts());
			M('Region')->openNote($_POST['parent_id']);
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->render('region/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('Region')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			M('Region')->openNote($_POST['parent_id']);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = M('Region')->getById((int)$this->_request->id);
		$view->render('region/input.php');
	}

	public function doGetJson()
	{
		echo M('Region')->select('id, parent_id, name, zipcode, level')
			->order('id ASC, rank ASC')
			->fetchOnKey('id')
			->toJson();
	}

	public function doGetMtree()
	{
		echo json_encode(M('Region')->select('id, parent_id, name, zipcode, level')
			->where('level > 1 AND level <= 3')
			->order('id ASC, rank ASC')
			->fetchOnKey('id')
			->toTree());
	}
}