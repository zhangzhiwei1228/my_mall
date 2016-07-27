<?php

class Agent_StaffController extends Agent_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}
	
	public function doDefault()
	{
		$view = $this->_initView();
		$view->datalist = M('User')->select()
			->where('parent_id = ?', (int)$this->user->id)
			->order('id DESC')
			->fetchRows();
		$view->render('views/merchants/merchants_staffw.php');
	}

	public function doDetail()
	{
		$data = M('User')->getById((int)$this->_request->id);
		$parent = M('User')->getById((int)$data['parent_id']);

		if ($parent['role'] == 'agent') {
			$view = $this->_initView();
			$view->data = $data;
			$view->bonus = $data->getBonus();
			$view->render('views/infolist.php');
		} else {
			$view = $this->_initView();
			$view->data = $data;
			$view->bonus = $data->getBonus();
			$view->render('views/infolist.php');
		}
	}
}