<?php

class Admincp_ReportController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doOrder()
	{
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
		$todayStat = M('Order')->select('COUNT(*) AS total_orders, 
				SUM(total_pay_amount) AS total_amount,
				SUM(total_quantity) AS total_quantity')
			->where('status IN (2,3,4) AND pay_time >= ?', $today)
			->fetchRow();
		$historyStat = M('Order')->select('COUNT(*) AS total_orders, 
				SUM(total_pay_amount) AS total_amount,
				SUM(total_quantity) AS total_quantity')
			->fetchRow();

		$orders = M('Order')->select('FROM_UNIXTIME(pay_time, \'%Y%m%d\') AS d, 
			SUM(total_quantity) AS q, 
			SUM(total_pay_amount) AS a,
			COUNT(*) AS o')
			->where('pay_time >= ?', time()-3600*24*7)
			->order('d ASC')
			->limit(7)
			->group('d')
			->fetchOnKey('d');

		//按照查询条件得到结果
		$sd = $this->_request->sd;
		$ed = $this->_request->ed;
		$status = (int)$this->_request->status;
		$sd = strtotime($sd);
		$ed = strtotime($ed);
		$time_where = '';

		$check_orders = M('Order')->select('FROM_UNIXTIME(pay_time, \'%Y%m%d\') AS d,
			SUM(total_quantity) AS q,
			SUM(total_pay_amount) AS a,
			COUNT(*) AS o,status')
			->order('d ASC')
			->group('d');

		if(!empty($sd)) {
			$time_where .= 'pay_time >= '.$sd.' and ';
		}
		if(!empty($ed)) {
			$time_where .= ' pay_time <='.$ed.' and ';
		}
		if(!empty($status)) {
			$time_where .= $status == 5 ? 'status < '.$status : 'status='.$status;
		}
		if(!empty($time_where)){
			$check_orders->where($time_where);
		}

		$view = $this->_initView();
		$view->orders = $orders;
		$view->check_orders = $check_orders->fetchOnKey('d');
		$view->todayStat = $todayStat;
		$view->historyStat = $historyStat;
		$view->render('report/order.php');
	}

	public function doRecever()
	{
		$select = M('Order')->alias('o')
			->columns('o.province_id, r.name,
				SUM(total_quantity) AS t_quantity, 
				SUM(total_pay_amount) AS t_amount,
				COUNT(*) AS t_orders')
			->order('t_amount DESC');

		if ($this->_request->sd) {
			$select->where('o.create_time >= ?', (int)strtotime($this->_request->sd));
		}
		if ($this->_request->ed) {
			$select->where('o.create_time <= ?', (int)strtotime($this->_request->ed));
		}

		if ($this->_request->pid) {
			$select->leftJoin(M('Region')->getTableName().' AS r', 'r.id = o.city_id')
				->where('province_id = ?', $this->_request->pid)
				->group('o.province_id');
		} else {
			$select->leftJoin(M('Region')->getTableName().' AS r', 'r.id = o.province_id')
				->group('o.province_id');
		}

		$view = $this->_initView();
		$view->region = M('Region')->getById((int)$this->_request->pid);
		$view->datalist = $select->fetchRows();
		$view->render('report/recever.php');
	}

	public function doGoods()
	{
		//客单价
		$pricestat = M('Order_Goods')->alias('og')
			->leftJoin(M('Order')->getTableName().' AS o', 'o.id = og.order_id')
			->columns('
				SUM(CASE WHEN final_price <= 50 THEN 1 ELSE 0 END) as s50,
				SUM(CASE WHEN final_price > 50 AND final_price <= 100 THEN 1 ELSE 0 END) as s50e100,
				SUM(CASE WHEN final_price > 100 AND final_price <= 200 THEN 1 ELSE 0 END) as s100e200,
				SUM(CASE WHEN final_price > 200 AND final_price <= 300 THEN 1 ELSE 0 END) as s200e300,
				SUM(CASE WHEN final_price > 300 AND final_price <= 500 THEN 1 ELSE 0 END) as s300e500,
				SUM(CASE WHEN final_price > 500 AND final_price <= 1000 THEN 1 ELSE 0 END) as s500e1000,
				SUM(CASE WHEN final_price > 1000 AND final_price <= 2000 THEN 1 ELSE 0 END) as s1000e2000,
				SUM(CASE WHEN final_price > 2000 AND final_price <= 5000 THEN 1 ELSE 0 END) as s2000e5000,
				SUM(CASE WHEN final_price > 5000 THEN 1 ELSE 0 END) as e5000
			')
			//->where('o.status IN (2,3,4)')
			->limit(10)
			->fetchRow()
			->toArray();

		$select1 = M('Goods')->select()
			->order('sales_num DESC')
			->limit(10);

		$select2 = M('Goods')->select()
			->order('clicks_num DESC')
			->limit(10);
		//新的统计，统计一个月的时间


		$view = $this->_initView();
		$view->pricestat = $pricestat;
		$view->topsales = $select1->fetchRows();
		$view->topclicks = $select2->fetchRows();
		$view->render('report/goods.php');
	}

	public function doFinancial()
	{
		$select = M('User_Money')->select(
				'type, SUM(amount) AS val'
			)
			->where('status = 2')
			->group('`type`');

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();

		$view->render('report/financial.php');
	}
	/**
	 * 会员统计报表
	 */
	public function doUser() {
		//按照查询条件得到结果,COUNT(u.*) AS u_total
		$select = M('User')->select('SUM(is_vip) AS u_vip, count(*) as u_total');
		$order = M('Order')->select('sum(total_quantity) as o_total_quantity,sum(total_amount) as o_total_amount, count(*) as o_total');
		$goods = M('Goods')->select('count(*) as g_total');
		if ($this->_request->sd) {
			$select->where('create_time >= ?', (int)strtotime($this->_request->sd));
			$order->where('create_time >= ?', (int)strtotime($this->_request->sd));
		}
		if ($this->_request->ed) {
			$select->where('create_time <= ?', (int)strtotime($this->_request->ed));
			$order->where('create_time <= ?', (int)strtotime($this->_request->ed));
		}

		if ($this->_request->mobile) {
			$select->where('mobile = ?', $this->_request->mobile);
			$parent_id = M('User')->select('id')->where('mobile='.$this->_request->mobile)->fetchRow()->toArray();
			$order->where('buyer_id='.(int)$parent_id['id']);
		}
		if ($this->_request->area_id) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$select->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
			$order->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}

		$view = $this->_initView();
		$view->udata = $select->fetchRow()->toArray();
		$view->order = $order->fetchRow()->toArray();

		$view->goods = $goods->fetchRow()->toArray();

		$view->render('report/user.php');
	}
	//分销统计报表
	public function doResale() {

		$view = $this->_initView();


		$view->render('report/resale.php');
	}
}