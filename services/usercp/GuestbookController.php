<?php

class Usercp_GuestbookController extends Usercp_Controller_Action
{
	public function init()
	{
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$select = M('Guestbook')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('usercp/guestbook/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$ip = Suco_Controller_Request_Http::getClientIp();

			M('Guestbook')->insert(array_merge($_POST, array(
				'user_id' => $this->user->id,
				'client_ip' => ip2long($ip)
			)));
			$this->redirect('action=list');
		}

		$view = $this->_initView();
		$view->render('usercp/guestbook/input.php');
	}

	public function doDelete()
	{
		M('Guestbook')->deleteById((int)$this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}