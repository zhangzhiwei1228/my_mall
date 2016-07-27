<?php

class Admincp_GoodsFeedbackController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('Goods')->alias('g')
			->rightJoin(M('Goods_Feedback')->getTableName().' AS gf', 'g.id = gf.goods_id')
			->columns('g.title AS goods_title, g.img_id, g.code, gf.*')
			->order('gf.create_time DESC')
			->group('gf.id')
			->paginator($pageSize, $currentPage);

		if ($this->_request->username) {
			$select->leftJoin(M('Member')->getTableName().' AS m', 'm.id = gf.sender_id')
				->where('m.username LIKE ?', '%'.$this->_request->username.'%');
		}
		if ($this->_request->score) {
			$select->where('gf.score = ?', $this->_request->score);
		}
		if (isset($this->_request->is_show)) {
			$select->where('gf.is_show = ?', (int)$this->_request->is_show);
		}
		if (isset($this->_request->gid)) {
			$select->where('gf.goods_id = ?', (int)$this->_request->gid);
		}
		if (isset($this->_request->goods_title)) {
			$select->where('g.title LIKE ?', '%'.$this->_request->goods_title.'%');
		}
		if ($this->_request->begin_time) {
			$select->where('gf.create_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('gf.create_time <= ?', strtotime($this->_request->end_time));
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/feedback/list.php');
	}

	public function doReply()
	{
		$data = M('Goods_Feedback')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			M('Goods_Feedback')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/feedback/reply.php');
	}

	public function doBatchReply()
	{
		foreach ((array)$this->_request->getPost('data') as $id => $data) {
			M('Goods_Feedback')->updateById($data, (int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doEnabled()
	{
		$data = M('Goods_Feedback')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		M('Goods_Feedback')->updateById('is_show = abs(is_show - 1)', (int)$this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}