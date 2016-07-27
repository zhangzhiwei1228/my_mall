<?php

class Admincp_IndexController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		//$time = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
		$time = mktime(0,0,0,date('m'),1,date('Y'));
		$hotSale = M('Order_Goods')->alias('og')
			->leftJoin(M('Order')->getTableName().' AS o', 'o.id = og.order_id')
			->columns('og.*, COUNT(*) AS sales_num')
			->where('o.status IN (2,3,4) AND pay_time >= ?', $time)
			->order('sales_num DESC')
			->group('og.goods_id')
			->limit(8)
			->fetchRows();

		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$orderStat = M('Order')->select('COUNT(*) AS total_orders, 
				SUM(total_pay_amount) AS total_amount,
				SUM(total_quantity) AS total_quantity')
			->where('status IN (2,3,4) AND pay_time >= ?', $today)
			->fetchRow();

		$userStat = M('User')->select('COUNT(*) AS total')
			->where('create_time >= ?', $today)
			->fetchRow();

		$orders = M('Order')->select('FROM_UNIXTIME(pay_time, \'%Y%m%d\') AS d, 
			SUM(total_quantity) AS q, 
			SUM(total_pay_amount) AS a,
			COUNT(*) AS o')
			->where('pay_time >= ?', time()-3600*24*10)
			->order('d ASC')
			->limit(10)
			->group('d')
			->fetchOnKey('d');

		$view = $this->_initView();
		$view->orders = $orders;
		$view->orderStat = $orderStat;
		$view->userStat = $userStat;
		$view->hotSale = $hotSale;
		$view->render('index.php');
	}
}