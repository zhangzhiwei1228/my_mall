<?php

class Admincp_ShippingController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doSetting()
	{
		$view = $this->_initView();
		$view->data = M('Shipping')->getById((int)$this->_request->id);
		$view->datalist = M('Shipping_Freight')->select()
			->where('shipping_id = ?', (int)$this->_request->id)
			->fetchRows();
		$view->render('shipping/setting.php');
	}
	public function doEnabled() {
		$shipping = M('Shipping')->getById((int)$this->_request->id);
		if($shipping['is_enabled']) {
			M('Shipping')->updateById(array('is_enabled' => 0), (int)$this->_request->id);
		} else {
			M('Shipping')->updateById(array('is_enabled' => 1), (int)$this->_request->id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

}