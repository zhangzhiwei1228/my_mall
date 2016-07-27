<?php
/**
 * 系统管理员控制器
 *
 * @category Controllers
 */

class Admincp_AdminController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Admin')->alias('a')
			->leftJoin(M('Admin_Group')->getTableName().' AS ag', 'a.group_id = ag.id')
			->columns('a.*, ag.name AS group_name')
			->where('a.is_admin = 1')
			->order('a.id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->gid) {
			$select->where('a.group_id = ?', (int)$this->_request->gid);
		}

		if ($this->_request->q) {
			$select->where('a.username LIKE ? OR a.nickname LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('admin/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Admin')->insert($this->_request->getPosts());
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->groups = M('Admin_Group')->fetchRows();
		$view->render('admin/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('Admin')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = M('Admin')->getById((int)$this->_request->id);
		$view->groups = M('Admin_Group')->fetchRows();;
		$view->render('admin/input.php');
	}

	public function doProfile()
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

		if ($this->_request->isPost()) {
			M('Admin')->updateById($this->_request->getPosts(), (int)M('Admin')->getCurUser()->id);
			$this->redirect('&success=1');
		}

		M('Admin_Menu')->setCurNote(-1);

		$view = $this->_initView();
		$view->data = M('Admin')->getCurUser();
		$view->groups = M('Admin_Group')->fetchRows();
		$view->render('admin/profile.php');
	}

	public function doAvatar()
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

		if ($this->_request->isPost()) {
			M('Admin')->updateById($this->_request->getPosts(), (int)M('Admin')->getCurUser()->id);
			$this->redirect('&success=1');
		}

		M('Admin_Menu')->setCurNote(-1);

		$view = $this->_initView();
		$view->data = M('Admin')->getCurUser();
		$view->render('admin/avatar.php');
	}
}