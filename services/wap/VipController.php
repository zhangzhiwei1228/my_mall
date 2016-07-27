<?php

class VipController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$view = $this->_initView();

		if ($this->_request->isPost()) {
			if ($_POST['code'] == 'dell109823') {
				$_SESSION['vip_discount'] = 0.8;
				$view->render('vip/dell.php');
				return;
			} else {
				throw new App_Exception('您输入的不是一个有效的VIP CODE');
			}
		}

		unset($_SESSION['vip_discount']);
		$view->render('vip/index.php');
	}

	public function doGoods()
	{
		$_SESSION['vip_discount'] = 0.8;
		$select = M('Goods')->select()
			->where('is_selling = 1')
			->order('id DESC')
			->paginator(18, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows()->hasmanyPromotions();
		$view->render('vip/goods.php');
	}

	public function doTest()
	{
		$view = $this->_initView();
		$view->render('usercp/security/chk_mobile.php');
	}
}