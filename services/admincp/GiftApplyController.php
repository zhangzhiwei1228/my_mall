<?php

class Admincp_GiftApplyController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Gift_Apply')->alias('ga')
			->leftJoin(M('Gift')->getTableName().' AS g', 'g.id = ga.gift_id')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = ga.user_id')
			->columns('ga.*, g.thumb, g.title, g.market_price, g.points, u.nickname')
			->order('ga.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('/gift/apply/list.php');
	}
}