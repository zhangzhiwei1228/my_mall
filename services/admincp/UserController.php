<?php

class Admincp_UserController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User')->alias('u')
			->leftJoin(M('User_Grade')->getTableName().' AS ug', 'ug.id = u.grade_id')
			->columns('u.*, ug.name AS grade_name')
			->where('u.is_admin = 0')
			->order('u.id DESC')
			->paginator($pageSize, $currentPage);

		if ($this->_request->role) {
			$select->where('role = ?', $this->_request->role);
		}

		if ($this->_request->q) {
			$select->where('u.username LIKE ? OR u.email LIKE ?', '%'.$this->_request->q.'%');
		}
		switch ($this->_request->t) {
			case 'only_disabled':
				$select->where('u.is_enabled = 0');
				break;
			case 'only_enabled':
				$select->where('u.is_enabled = 1');
				break;
			case 'blacklist':
				$ids = M('User_Blacklist')->select('user_id')->fetchCols('user_id');
				$select->where('u.id IN('.($ids ? implode(',', $ids) : 0).')');
				break;
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('user/list.php');
	}

	public function doMember()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User')->alias('u')
			->leftJoin(M('User_Grade')->getTableName().' AS ug', 'ug.id = u.grade_id')
			->columns('u.*, ug.name AS grade_name')
			->where('u.is_admin = 0 AND role = ?', 'member')
			->order('u.id DESC')
			->paginator($pageSize, $currentPage);

		if ($this->_request->q) {
			$select->where('u.username LIKE ? OR u.email LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('user/list.php');
	}

	public function doAgent()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User')->alias('u')
			->leftJoin(M('User_Grade')->getTableName().' AS ug', 'ug.id = u.grade_id')
			->columns('u.*, ug.name AS grade_name')
			->where('u.is_admin = 0 AND role = ?', 'agent')
			->order('u.id DESC')
			->paginator($pageSize, $currentPage);

		if ($this->_request->q) {
			$select->where('u.username LIKE ? OR u.email LIKE ?', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('user/list.php');
	}

	public function doDetail()
	{
		$data = M('User')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->auth = M('User_Certify')->select()
			->where('user_id = ?', $data['id'])
			->fetchRows();
		$view->data = $data;
		$view->extFields = M('User')->getExtFieldLists($data['role']);
		$view->render('user/detail.php');
	}

	public function doCredit()
	{
		$view = $this->_initView();
		$view->datalist = M('User_Credit')->select()
			->where('user_id = ?', (int)$this->_request->id)
			->order('id DESC')
			->paginator(10, $this->_request->page)
			->fetchRows();
		$view->setLayout(false);
		$view->render('user/detail/credit.php');
	}

	public function doStaff()
	{
		$view = $this->_initView();
		$view->datalist = M('User')->select()
			->where('parent_id = ?', (int)$this->_request->id)
			->order('id DESC')
			->fetchRows();
		$view->setLayout(false);
		$view->render('user/detail/staff.php');
	}

	public function doProfile()
	{
		if ($this->_request->isPost()) {
			M('User_Extend')->delete('user_id = ?', $this->_request->id);
			foreach($_POST['ext'] as $k => $v) {
				M('User_Extend')->insert(array(
					'user_id' => $this->_request->id,
					'field_key' => $k,
					'field_name' => $v['name'],
					'field_value' => $v['value']
				));
			}

			//$this->user->save($_POST);
			return $this->_notice(array(
				'title' => '资料更新成功！',
				'links' => array(
					array('返回上一页', '&')
				),
				'autoback' => array('自动返回上一页', '&'),
			), 'success');
		}
	}

	public function doAvatar()
	{
		$data = M('User')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('user/avatar.php');
	}

	public function doBatchEnable()
	{
		foreach ((array)$_POST['ids'] as $id) {
			M('User')->updateById('is_enabled = 1', (int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doBatchDisable()
	{
		foreach ((array)$_POST['ids'] as $id) {
			M('User')->updateById('is_enabled = 0', (int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doAddBlacklist()
	{
		M('User_Blacklist')->insert(array(
			'user_id' => $this->_request->id,
		));
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doRemoveBlacklist()
	{
		M('User_Blacklist')->delete('user_id = ?', $this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');		
	}
}