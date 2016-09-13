<?php

class Usercp_IndexController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}
	
	public function doDefault()
	{
		if(isset($_COOKIE['buy_shop'])) {
			setcookie('buy_shop',null,-1,'/');
		}
		$order = M('Order')->select()
			->where('buyer_id = ? AND status IN (1,2,3)', $this->user['id'])
			->fetchRows();

		$orderSummary = M('Order')->select('
				SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as s1,
				SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as s2,
				SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as s3
			')->where('buyer_id = ? AND `status` IN (1,2,3)', $this->user['id'])
			->fetchRow();
		$count = M('Message')->count('recipient_uid='.$this->user['id'].' and is_read=0');

		$view = $this->_initView();
		$view->news = $count;
		$view->order = $order;
		$view->orderSummary = $orderSummary;
		$view->render('views/personalcenter.php');
	}
}