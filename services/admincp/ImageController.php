<?php

class Admincp_ImageController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->user = $this->_auth();
	}

	public function doList()
	{
		$select = M('Image')->select()
			->order('id DESC')
			->paginator(15, $this->_request->page);

		if (M('Admin')->getCurUser()->group_id != 1) {
			$select->where('sign = ?', M('Admin')->getCurUser()->getSign());
		}
		if ($this->_request->q) {
			$select->where('name LIKE ?', '%'.$this->_request->q.'%');
		}
		if ($this->_request->begin_time) {
			$select->where('create_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('create_time <= ?', strtotime($this->_request->end_time));
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('image/list.php');

	}

	public function doDetail()
	{
		$data = M('Image')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('404');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->nextItem = $data->getNextItem();
		$view->prevItem = $data->getPrevItem();
		$view->render('image/detail.php');
	}

	public function doDelete()
	{
		M('Image')->deleteById((int)$this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doReplace()
	{
		if ($this->_request->isPost()) {
			$image = M('Image')->getById((int)$_POST['id']);
			$conf = Suco_Config::factory(CONF_DIR.'image.conf.php');

			foreach($conf->img_allow_sizes as $size) {
				$url = getImage($image['src'], $size);
				Suco_File::delete(WWW_DIR.$url);
			}

			Suco_File::upload($_FILES['src'], ltrim($image['src'],'/'));

			$image->save(array(
				'format' => $_FILES['src']['type'],
				'size' => $_FILES['src']['size'],
			));

			$this->redirect($_SERVER['HTTP_REFERER']);
		}
	}

	public function doBatchDelete()
	{
		foreach ((array)$this->_request->getPost('ids') as $id) {
			M('Image')->deleteById((int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doSelector()
	{
		$select = M('Image')->select()
			->order('id DESC')
			->paginator(12, $this->_request->page);

		if ($this->_request->ref) {
			$select->where('ref = ?', $this->_request->ref);
		}
		if (M('Admin')->getCurUser()->group_id != 1) {
			$select->where('sign = ?', M('Admin')->getCurUser()->getSign());
		}
		if ($this->_request->q) {
			$select->where('name LIKE ?', '%'.$this->_request->q.'%');
		}
		if ($this->_request->begin_time) {
			$select->where('create_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('create_time <= ?', strtotime($this->_request->end_time));
		}

		$view = $this->_initView();
		$view->type = $this->_request->type ? $this->_request->type : 'default';
		$view->setLayout(false);
		$view->datalist = $select->fetchRows();
		$view->render('image/selector.php');
	}
}