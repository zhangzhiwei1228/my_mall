<?php

class Admincp_ShippingFreightController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Shipping_Freight')->insert($this->_request->getPosts());
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->disabledIds = implode(',', M('Shipping_Freight')->select('destination')
			->where('shipping_id = ?', (int)$this->_request->sid)
			->fetchCols('destination'));
		$view->render('shipping/freight/input.php');
	}

	public function doEdit()
	{
		$data = M('Shipping_Freight')->getById((int)$this->_request->id);
		if ($this->_request->isPost()) {
			M('Shipping_Freight')->updateById($this->_request->getPosts(), $this->_request->id);
			$this->redirect($this->_request->ref ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->disabledIds = implode(',', M('Shipping_Freight')->select('destination')
			->where('shipping_id = ? AND id != ?', array($data['shipping_id'], $data['id']))
			->fetchCols('destination'));
		$view->render('shipping/freight/input.php');
	}
}