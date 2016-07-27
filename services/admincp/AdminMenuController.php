<?php

class Admincp_AdminMenuController extends Admincp_Controller_Action
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
		M('Admin_Menu')->setCurNote(-1);
		$select = M('Admin_Menu')->select()
			->where('parent_id = ?', $this->_request->cid ? $this->_request->cid : 0)
			->order('rank ASC, id ASC');
		
		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('admin/menu/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '菜单添加成功！',
				'links' => array(
					array('继续添加', '&success'),
					array('返回列表', '&action=list')
				),
				'autoback' => array('自动返回上一页', '&success'),
			), 'success');
		}

		if ($this->_request->isPost()) {
			M('Admin_Menu')->insert($this->_request->getPosts());
			$this->redirect('&success=1');
		}

		M('Admin_Menu')->setCurNote(-1);
		$view = $this->_initView();
		$view->categories = M('Admin_Menu')->getChilds()->toTreeList();
		$view->groups = M('Admin_Group')->fetchRows();
		$view->render('admin/menu/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('Admin_Menu')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			M('Admin_Menu')->openNote($_POST['parent_id']);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		M('Admin_Menu')->setCurNote(-1);
		$view = $this->_initView();
		$view->data = M('Admin_Menu')->getById((int)$this->_request->id);
		$view->categories = M('Admin_Menu')->getItemsNoSelfChild((int)$this->_request->id)->toTreeList();
		$view->groups = M('Admin_Group')->fetchRows();
		$view->render('admin/menu/input.php');
	}

	public function doRedirect()
	{
		$menu = M('Admin_Menu')->getById((int)$this->_request->id)->setCurNote();
		if (substr($menu['redirect'], 0, 1) == '#') {
			$id = substr($menu['redirect'], 1);
			$menu = M('Admin_Menu')->getById((int)$id)->setCurNote();
		}
		$this->redirect($menu['redirect']);
	}

	public function doJson()
	{
		echo M('Admin_Menu')->select('id, parent_id, name')
			->order('id ASC, rank ASC')
			->fetchRows()
			->toJson();
	}
}