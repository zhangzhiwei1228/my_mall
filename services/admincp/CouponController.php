<?php

class Admincp_CouponController extends Admincp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_auth(__CLASS__);
	}

	public function doMake()
	{
		$coupon = M('Coupon')->getById((int)$this->_request->id);
		M('Coupon_Receive')->delete('coupon_id = ?', $coupon['id']);

		for($i=1; $i<=$coupon['quantity']; $i++) {
			echo getGuid();
			echo '<br>';
			M('Coupon_Receive')->insert(array(
				'coupon_id' => $coupon['id'],
				'code' => getGuid()
			));
		}
		//$coupon->is_make = 1;
		//$coupon->save();

		$this->redirect('action=detail&id='.$coupon['id']);
	}

	public function doDetail()
	{
		$data = M('Coupon')->getById((int)$this->_request->id);
		
		$view = $this->_initView();
		$view->data = $data;
		$view->codes = M('Coupon_Receive')->select()
			->where('coupon_id = ?', $data['id'])
			->fetchRows();
		$view->render('coupon/detail.php');
	}
}