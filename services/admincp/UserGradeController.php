<?php

class Admincp_UserGradeController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User_Grade')->select()
			->order('min_exp ASC, id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('user/grade/list.php');
	}
}