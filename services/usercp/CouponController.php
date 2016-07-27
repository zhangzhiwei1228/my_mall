<?php

class Usercp_CouponController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$select = M('Coupon')->alias('c')
			->rightJoin(M('Coupon_Receive')->getTableName().' AS cr', 'cr.coupon_id = c.id')
			->where('user_id = ?', $this->user['id'])
			->order('cr.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('usercp/coupon/index.php');
	}
}
