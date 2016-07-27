<?php

class NewsController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$this->user = $this->_auth();
		$select = M('Article')->select()
			->where('is_checked = 2')
			->paginator(20, $this->_request->page);
		$messages = M('Message')->alias('m')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = m.sender_uid')
			->columns('m.*, u.username AS sender_name, u.avatar AS sender_avatar')
			->where('m.recipient_uid = ?', $this->user['id'])
			->order('m.is_read ASC, m.id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->cid) {
			$ids = M('Article_Category')->getChildIds((int)$this->_request->cid);
			$select->where('category_id IN ('.($ids ? $ids : 0).')');
		}
		$select->order('id DESC');

		$view = $this->_initView();
		$view->category = M('Article_Category')->getById((int)$this->_request->cid);
		$view->msglist = $messages->fetchRows();
		$view->datalist = $select->fetchRows();
		$view->render('views/welcomew2.php');
	}

	public function doDetail()
	{
		$data = M('Article')->getById((int)$this->_request->id);
		if ($this->_request->mid) {
			$data = M('Message')->getById((int)$this->_request->mid);
		}
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		M('Article')->updateById(array('is_looked' => 1), (int)$this->_request->id);

		$view = $this->_initView();
		$view->data = $data;
		$view->render('views/page.php');
	}
}