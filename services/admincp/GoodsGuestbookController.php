<?php

require_once 'Abstract.php';

class Admincp_GoodsGuestbookController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Goods_Guestbook')->alias('a')
			->leftJoin(M('Goods')->getTableName().' AS b', 'a.goods_id = b.id')
			->leftJoin(M('Member')->getTableName().' AS c', 'a.sender_id = c.id')
			->columns('a.*, b.title AS goods_title, b.thumb, b.code, c.username')
			->order('a.id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->username) {
			$select->where('c.username LIKE ?', '%'.$this->_request->username.'%');
		}
		if (isset($this->_request->is_show)) {
			$select->where('a.is_show = ?', (int)$this->_request->is_show);
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/guestbook/list.php');
	}

	public function doReply()
	{
		$data = M('Goods_Guestbook')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new App_Exception('记录不存在');
		}

		if ($this->_request->isPost()) {
			M('Goods_Guestbook')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/guestbook/reply.php');
	}

	public function doBatchReply()
	{
		foreach ((array)$_POST['data'] as $id => $data) {
			M('Goods_Guestbook')->updateById($data, (int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}