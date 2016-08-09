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

	//分销人数统计报表
	public function doResaleNumbers() {
		$user = M('User')->select('count(*) as total')->where("role != 'member' and role != ''");
		$user_ids = M('User')->select('id')->where("role != 'member'");
		if ($this->_request->sd) {
			$user->where('create_time >= ?', (int)strtotime($this->_request->sd));
			$user_ids->where('create_time >= ?', (int)strtotime($this->_request->sd));
		}
		if ($this->_request->ed) {
			$user->where('create_time <= ?', (int)strtotime($this->_request->ed));
			$user_ids->where('create_time <= ?', (int)strtotime($this->_request->ed));
		}
		if ($this->_request->area_id && $this->_request->area_id != 1) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$user->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
			$user_ids->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}

		if ($this->_request->role) {
			$role = explode('-',$this->_request->role);
			$role1 = $role[0];
			$role2 = $role[1];
			if(strlen($role2) > 1) {
				$user1 = M('User')->select('id')
					->where('role ='."'".$role2."'")
					->fetchRows()->toArray();
				if ($this->_request->sd) {
					$user1->where('create_time >= ?', (int)strtotime($this->_request->sd));
				}
				if ($this->_request->ed) {
					$user1->where('create_time <= ?', (int)strtotime($this->_request->ed));
				}
				if ($this->_request->area_id) {
					$ids = M('Region')->getChildIds((int)$this->_request->area_id);
					$user1->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
				}
				foreach($user1 as $row) {
					$ids1[] = $row['id'];
				}
				$ids = $ids1 ? implode(',', $ids1) : 0;
				$user->where("role = '".$role1."'".' AND parent_id IN ('.$ids.')');
				$user_ids->where("role = '".$role1."'".' AND parent_id IN ('.$ids.')');

			} else {
				$user->where("role = '".$role1."'".' and resale_grade='.(int)$role2);
				$user_ids->where("role = '".$role1."'".' and resale_grade='.(int)$role2);
			}
		}
		$get_user_ids = $user_ids->fetchRows()->toArray();
		foreach($get_user_ids as $id) {
			$income = M('User')->getBonus($id);
			if($this->_request->sincome && $this->_request->sincome <= $income['amount']) {
				$income_ids[] = $id['id'];
			}
			if($this->_request->eincome && $this->_request->eincome >= $income['amount']) {
				$income_ids[] = $id['id'];
			}
		}
		if($income_ids ) {
			$income_ids = $income_ids ? implode(',', $income_ids) : 0;
			$user->where('id IN ('.$income_ids.')');
		}

		$view = $this->_initView();
		$view->users = $user->fetchRow()->toArray();
		$view->render('report/resale/resalenumbers.php');
	}
	//分销商个人查询
	public function doResalePersonal() {
		$user = M('User')->select('id,role,username,resale_grade,parent_id')->where("role != 'member' and role != ''")->paginator(20, $this->_request->page);
		if ($this->_request->role) {
			$role = explode('-',$this->_request->role);
			$role1 = $role[0];
			$role2 = $role[1];
			if(strlen($role2) > 1) {
				$user1 = M('User')->select('id')
					->where('role ='."'".$role2."'")
					->fetchRows()->toArray();
				if ($this->_request->sd) {
					$user1->where('create_time >= ?', (int)strtotime($this->_request->sd));
				}
				if ($this->_request->ed) {
					$user1->where('create_time <= ?', (int)strtotime($this->_request->ed));
				}
				if ($this->_request->area_id) {
					$ids = M('Region')->getChildIds((int)$this->_request->area_id);
					$user1->where('area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
				}
				foreach($user1 as $row) {
					$ids1[] = $row['id'];
				}
				$ids = $ids1 ? implode(',', $ids1) : 0;
				$user->where("role = '".$role1."'".' AND parent_id IN ('.$ids.')');
			} else {
				$user->where("role = '".$role1."'".' and resale_grade='.(int)$role2);
			}
		}
		if($this->_request->username) {
			$user->where("username = '".$this->_request->username."'");
		}
		$view = $this->_initView();
		$view->users = $user->fetchRows();
		$view->render('report/resale/resalepersonal.php');
	}
	//分销商详情
	public function doResaleDetail() {
		$user = M('User')->select()->where('id='.(int)$this->_request->id)->fetchRow()->toArray();
		$view = $this->_initView();
		switch ($user['role']) {
			case 'staff':
				$view->parent = M('User')->getById((int)$user['parent_id']);
				$view->bonus = M('User')->getBonus($user);
				$view->user = $user;
				$view->render('report/resale/proxyworker.php');
				break;
			case 'resale':
				if ($user['resale_grade'] == 4) {
					$view->bonus = M('User')->getStaffBonus($user);
					$view->user = $user;
					$view->render('report/resale/proxyfour.php');
				} else {
					$view->bonus = M('User')->getBonus($user);
					$view->user = $user;
					$view->render('report/resale/onestar.php');
				}
				break;
			case 'seller':
			case 'agent':
				$view->bonus = M('User')->getStaffBonus($user);
				$view->user = $user;
				$view->render('report/resale/merchants.php');
				break;
		}
	}
	//免费积分统计
	public function doSiteBonus() {
		$credits = M('User_Credit')->alias('uc')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = uc.user_id')
			->columns('uc.*, u.role,u.area_id')->where("uc.type='credit' and uc.note like '".'%'.'购买免费积分'.'%'."'");
		$seller_bonus = 0;
		$member_bonus = 0;
		if ($this->_request->sd) {
			$credits->where('uc.create_time >= ?', (int)strtotime($this->_request->sd));
		}
		if ($this->_request->ed) {
			$credits->where('uc.create_time <= ?', (int)strtotime($this->_request->ed));
		}
		if ($this->_request->area_id && $this->_request->area_id != 1) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$credits->where('u.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
			$credits->where('u.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}
		$credits = $credits->fetchRows()->toArray();
		foreach($credits as $val) {
			if($val['role'] == 'seller' && $val['credit'] > 0) {
				$seller_bonus += $val['credit'];
			} elseif($val['role'] == 'member') {
				$member_bonus += $val['credit'];
			}
		}

		$view = $this->_initView();
		$view->seller_bonus = $seller_bonus;
		$view->member_bonus = $member_bonus;
		$view->render('report/resale/sitebonus.php');
	}
	//会员消耗积分币统计
	public function doExpendCredit() {
		$credits = M('User_Credit')->alias('uc')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = uc.user_id')
			->columns('uc.*, u.role,u.area_id')->where("type='credit_coin' and uc.note like '".'%'.'积分转换成积分币'.'%'."'");
		$exchange_goods = M('User_Credit')->alias('uc')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = uc.user_id')
			->columns('uc.*, u.role,u.area_id')->where("uc.type='credit_coin' and uc.note like '".'%'.'支付订单'.'%'."'");
		if ($this->_request->sd) {
			$credits->where('uc.create_time >= ?', (int)strtotime($this->_request->sd));
			$exchange_goods->where('uc.create_time >= ?', (int)strtotime($this->_request->sd));
		}
		if ($this->_request->ed) {
			$credits->where('uc.create_time <= ?', (int)strtotime($this->_request->ed));
			$exchange_goods->where('uc.create_time <= ?', (int)strtotime($this->_request->ed));
		}
		if ($this->_request->area_id && $this->_request->area_id != 1) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$credits->where('u.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
			$exchange_goods->where('u.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}
		$credits = $credits->fetchRows()->toArray();
		$exchange_goods = $exchange_goods->fetchRows()->toArray();
		$conversion_coins = 0;
		$exchange_coins = 0;
		foreach($credits as $val) {
			$conversion_coins += $val['credit'];
		}
		foreach($exchange_goods as $val) {
			$exchange_coins += $val['credit'];
		}
		$view = $this->_initView();
		$view->conversion_coins = $conversion_coins;
		$view->exchange_coins = $exchange_coins;
		$view->render('report/resale/expendcredit.php');
	}
	//平台总出帐
	public function doOutAccount() {
		$credits = M('User_Credit')->select('sum(credit) as total')->where("type='credit' and note like '".'%'.'购买免费积分'.'%'."'")->fetchRow()->toArray();
		$coins = M('User_Credit')->select('sum(credit) as total')->where("type='credit_coin' and note like '".'%'.'购买积分币'.'%'."'")->fetchRow()->toArray();
		$money = M('User_Money')->select('sum(amount) as total')->where("type='pay' and remark like '".'%'.'VIP激活'.'%'."'")->fetchRow()->toArray();
		$view = $this->_initView();

		$view->credits = $credits;
		$view->coins = $coins;
		$view->money = $money;
		$view->render('report/resale/outaccount.php');
	}
}