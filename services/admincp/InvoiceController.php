<?php

class Admincp_InvoiceController extends Admincp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_auth(__CLASS__);
	}

	public function doList()
	{
		$select = M('Invoice')->select()
			->where('status != 0')
			->order('id DESC')
			->paginator(20, $this->_request->page);

		if ($this->_request->q) {
			$select->where('title LIKE ?', '%'.$this->_request->q.'%');
		}

		switch($this->_request->t) {
			case 'pending':
				$select->where('status = 1');
				break;
			case 'yes':
				$select->where('status = 2');
				break;
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('invoice/list.php');
	}
}