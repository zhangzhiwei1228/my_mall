<?php

class Admincp_PaymentController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$select = M('Payment')->select()
			->order('rank ASC, id ASC');
			//->paginator(20, (int)$this->_request->page);

		if ($this->_request->q) {
			$select->where('name LIKE', "%{$this->_request->q}%");
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('payment/list.php');
	}
}