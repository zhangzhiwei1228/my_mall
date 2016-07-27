<?php

require_once 'Abstract.php';

class Admincp_GuestbookController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->admin = $this->_auth();
	}

	public function doList()
	{
		$select = M('Guestbook')->alias('g')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = g.user_id')
			->leftJoin(M('Admin')->getTableName().' AS a', 'a.id = g.reply_uid')
			->columns('g.*, u.username AS user_name, a.username AS admin_name')
			->order('g.id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->username) {
			$select->where('u.username LIKE ?', '%'.$this->_request->username.'%');
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('guestbook/list.php');
	}

	public function doReply()
	{
		$data = M('Guestbook')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new App_Exception('记录不存在');
		}

		if ($this->_request->isPost()) {
			M('Guestbook')->updateById(array_merge($_POST, array(
				'reply_uid' => $this->admin->id,
				'reply_time' => time(),
			)), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('guestbook/reply.php');
	}

	public function doBatchReply()
	{
		foreach ((array)$_POST['data'] as $id => $data) {
			M('Guestbook')->updateById($data, (int)$id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}