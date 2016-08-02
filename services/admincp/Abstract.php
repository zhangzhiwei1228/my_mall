<?php

class Admincp_Controller_Action extends Suco_Controller_Action
{
	/**
	 * 权限检查
	 */
	protected function _auth()
	{
		if ($this->_request->token) {
			$admin = M('Admin')->getUserByToken($this->_request->token);
		} else {
			$admin = M('Admin')->getCurUser();
		}

		if (!$admin->exists()) {
			$this->redirect('controller=passport&action=login&ref='.$this->_request->url);
		}
		if (!$admin['is_enabled']) {
			throw new App_Exception('此帐户已被禁用');
		}
		if ($admin->group['allow'] != 'ALL') {
			//查找资源是否在权限控制列表中
			$acls = M('Admin_Acl')->select('id, resource')
				->fetchRows();

			$querys = $this->_request->getQuerys();
			foreach ($acls as $row) {
				parse_str($row['resource'], $resource); 
				$q = array_intersect_assoc($querys, $resource);
				sort($resource); sort($q);
				if ($q == $resource) {
					$resourceId = $row['id'];
					break;
				}
			}
			
			$allows = explode(',', $admin->group['allow']);
			if ($resourceId && !in_array($resourceId, $allows)) {
				throw new App_Exception('权限不足，请与系统管理员联系！');
			}
		}

		return $admin;
	}

	/**
	 * 初始化视图
	 */
	protected function _initView()
	{
		$theme = '/themes/admincp_v4.3/';

		$view = $this->getView();
		$view->setThemePath($theme);
		$view->setScriptPath(WWW_DIR.$theme.'tpl/scripts/');
		$view->setLayoutPath(WWW_DIR.$theme.'tpl/layouts/');
		$view->setHelperPath(WWW_DIR.$theme.'tpl/helpers/');

		$view->setLayout('default.php'); //默认布局
		$view->setting = M('Setting')->export();
		$view->admin = M('Admin')->getCurUser();
		$view->paths = M('Admin_Menu')->getCurNote()->getPath();

		$view->nav = M('Admin_Menu')->select()
			->order('rank ASC')
			->fetchRows()
			->toTree();

		return $view;
	}

	/**
	 * 操作提示页面
	 */
	protected function _notice($data, $type = 'success')
	{
		$view = $this->_initView();
		$view->render('notice.php', array_merge($data, array('type' => $type)));
	}

	/**
	 * 格式化模型名称
	 */
	protected function _formatModelName()
	{
		$className = str_replace(array('Admincp_', 'Controller'), '', get_class($this));
		for($i=0; $i<=strlen($className); $i++){
			$alpha = substr($className, $i, 1);
			if ($alpha === strtoupper($alpha)) {
				$modelName .= '_'.$alpha;
			} else {
				$modelName .= $alpha;
			}
		} 
		return trim($modelName, '_');
	}

	/**
	 * 格式化视图名称
	 */
	protected function _formatViewName()
	{
		$modelName = $this->_formatModelName();
		return str_replace('_', '/', strtolower($modelName));
	}

	/**
	 * 默认动作
	 */
	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doSearch()
	{
		$this->doList();
	}

	/**
	 * 列表页
	 */
	public function doList()
	{
		$select = M($this->_formatModelName())->select()
			->order('id DESC')
			->paginator(20, (int)$this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render($this->_formatViewName().'/list.php');
	}

	/**
	 * 详细页
	 */
	public function doDetail()
	{
		$data = M($this->_formatModelName())->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render($this->_formatViewName().'/detail.php');
	}

	/**
	 * 添加
	 */
	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$skus = $this->_request->getPosts();
			if(count($skus['skus']) > 1) {
				foreach($skus['skus'] as $key => &$exts) {
					if($key == 0) continue;
					$exts['exts'] = $skus['skus'][0]['exts'];
				}
			}
			M($this->_formatModelName())->insert(array_merge($skus, $this->_request->getFiles()));
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->render($this->_formatViewName().'/input.php');
	}

	/**
	 * 编辑
	 */
	public function doEdit()
	{
		$data = M($this->_formatModelName())->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			$skus = $this->_request->getPosts();
			if(count($skus['skus']) > 1) {
				foreach($skus['skus'] as $key => &$exts) {
					if($key == 0) continue;
					$exts['exts'] = $skus['skus'][0]['exts'];
				}
			}

			M($this->_formatModelName())->updateById(array_merge($skus, $this->_request->getFiles()), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render($this->_formatViewName().'/input.php');
	}

	/**
	 * 删除
	 */
	public function doDelete()
	{
		if (!M($this->_formatModelName())->deleteById((int)$this->_request->id)) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}


	public function doTrash()
	{
		if (!M($this->_formatModelName())->updateById(array('is_trash' => 1), (int)$this->_request->id)) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	/**
	 * 批量操作
	 */
	public function doBatch()
	{
		switch($_POST['act']) {
			case 'delete':
				foreach ((array)$_POST['ids'] as $id) {
					M($this->_formatModelName())->deleteById((int)$id);
				}
				break;
			case 'trash':
				foreach ((array)$_POST['ids'] as $id) {
					M($this->_formatModelName())->updateById(array('is_trash' => 1), (int)$id);
				}
				break;
			case 'disabled':
				foreach ((array)$_POST['ids'] as $id) {
					M($this->_formatModelName())->updateById(array('is_enabled' => 0), (int)$id);
				}
				break;
			case 'enabled':
				foreach ((array)$_POST['ids'] as $id) {
					M($this->_formatModelName())->updateById(array('is_enabled' => 1), (int)$id);
				}
				break;
			case 'update':
				foreach ((array)$_POST['data'] as $id => $data) { $i++;
					$data['rank'] = $i;
					M($this->_formatModelName())->updateById($data, (int)$id);
				}
				break;
			case 'move':
				foreach ((array)$_POST['ids'] as $id) {
					M($this->_formatModelName())->updateById(array('category_id' => $_POST['cid']), (int)$id);
				}
				break;
		}
		
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : $_SERVER['HTTP_REFERER']);
	}

	public function doChange()
	{
		switch($this->_request->m) {
			case 'is_checked':
				$checked = abs($this->_request->s+1 > 2 ? 0 : $this->_request->s+1);
				M($this->_formatModelName())->updateById('is_checked = '.(int)$checked, (int)$this->_request->id);
				break;
			case 'status':
				M($this->_formatModelName())->updateById('status = abs(status - 1)', (int)$this->_request->id);
				break;
			case 'is_best':
				M($this->_formatModelName())->updateById('is_best = abs(is_best - 1)', (int)$this->_request->id);
				break;
			case 'is_original':
				M($this->_formatModelName())->updateById('is_original = abs(is_original - 1)', (int)$this->_request->id);
				break;
			case 'is_enabled':
				M($this->_formatModelName())->updateById('is_enabled = abs(is_enabled - 1)', (int)$this->_request->id);
				break;
			case 'is_actived':
				M($this->_formatModelName())->updateById('is_actived = abs(is_actived - 1)', (int)$this->_request->id);
				break;
			case 'is_read':
				M($this->_formatModelName())->updateById('is_read = abs(is_read - 1)', (int)$this->_request->id);
				break;
			case 'is_hot':
				M($this->_formatModelName())->updateById('is_hot = abs(is_hot - 1)', (int)$this->_request->id);
				break;
			case 'is_admin':
				M($this->_formatModelName())->updateById('is_admin = abs(is_admin - 1)', (int)$this->_request->id);
				break;
		}

		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}