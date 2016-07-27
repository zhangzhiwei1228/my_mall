<?php

class Admincp_MessageController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->admin = $this->_auth();
	}

	public function doNew()
	{
		if ($this->_request->isPost()) {
			$uids = explode(',', $_POST['recipient_uid']);
			foreach($uids as $uid) {
				M('Message')->insert(array_merge($_POST, array(
					'recipient_uid' => $uid,
					'sender_uid' => $this->admin['id']
				)));
			}

			return $this->_notice(array(
				'title' => '信息发送成功',
				'links' => array(
					array('返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success')
				),
				'autoback' => array('自动返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success'),
			), 'success');
		}

		$view = $this->_initView();
		$view->recipient = M('User')->getById((int)$this->_request->uid);
		$view->render('message/new.php');
	}

	public function doInbox()
	{
		$select = M('Message')->alias('m')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = m.sender_uid')
			->columns('m.*, u.username AS sender_name, u.avatar AS sender_avatar')
			->where('m.recipient_uid = ? OR m.recipient_uid = -1', $this->admin['id'])
			->order('m.is_read ASC, m.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('message/inbox.php');

		foreach($view->datalist as $row) {
			$row->is_read = 1;
			$row->save();
		}
	}

	public function doGetUnreadList()
	{
		echo M('Message')->alias('m')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = m.sender_uid')
			->columns('m.*, u.username AS sender_name, u.avatar AS sender_avatar')
			->where('(m.recipient_uid = ? OR m.recipient_uid = -1) AND m.is_read = 0', $this->admin['id'])
			->order('m.id DESC')
			->fetchRows()
			->toJson();
	}

	public function doGetUsers()
	{
		echo $users = M('User')->select('id, username AS name')
			->where('username LIKE ?', $this->_request->q.'%')
			->fetchRows()
			->toJson();
	}
}