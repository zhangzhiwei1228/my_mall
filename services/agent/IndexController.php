<?php

class Agent_IndexController extends Agent_Controller_Action
{
	public $seller = array();
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}
	
	public function doDefault()
	{
		switch ($this->user['role']) {
			case 'staff':
				$view = $this->_initView();
				$view->parent = M('User')->getById((int)$this->user['parent_id']);
				$view->bonus = $this->user->getBonus();
				$view->render('views/proxyworker.php');
				break;
			case 'resale':
				if ($this->user['resale_grade'] == 4) {
					$view = $this->_initView();
					$view->bonus = $this->user->getStaffBonus();
					$view->render('views/proxyfour.php');
				} else {
					$view = $this->_initView();
					$view->bonus = $this->user->getBonus();
					$view->render('views/onestar.php');
				}
				break;
			case 'seller':
				$view = $this->_initView();
				$view->bonus = $this->user->getStaffBonus();
				$uid = $this->user->id;
				$year = date("Y");
				$month = date("m");
				$day = date("d");
				$dayBegin = mktime(0,0,0,$month,$day,$year);//当天开始时间戳
				$dayEnd = mktime(23,59,59,$month,$day,$year);//当天结束时间戳

				$BeginDate=strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
				$start_month = date('Y-m-01', strtotime(date("Y-m-d")));
				$EndDate =  strtotime(date('Y-m-d', strtotime("$start_month +1 month -1 day")));

				$view = $this->_initView();
				//商家充值赠送的免费积分
				$view->employ = M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit<0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
					->fetchRow()->toArray();
				$view->Memploy = M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit<0 and create_time >'.$BeginDate.' and create_time <'.$EndDate)
					->fetchRow()->toArray();
				$view->recharge =  M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit>0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
					->fetchRow()->toArray();

				//商家充值赠送的抵用券
				$view->Demploy = M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit<0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
					->fetchRow()->toArray();
				$view->MemployV = M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit<0 and create_time >'.$BeginDate.' and create_time <'.$EndDate)
					->fetchRow()->toArray();
				$view->rechargeV =  M('User_Credit')->select('sum(credit) as total')
					->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit>0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
					->fetchRow()->toArray();

				$view->render('views/merchants.php');
				break;
			case 'agent':
				$view = $this->_initView();
				$view->bonus = $this->user->agentearnings();
				$view->render('views/agentearnings.php');
				break;
		}
	}

	public function doRecharge()
	{
		$view = $this->_initView();
		$view->render('views/jifensteps.php');
	}

	public function doStaff()
	{
		$view = $this->_initView();
		$view->render('views/merchants/merchants_staffw.php');
	}
	public function doShopList() {
		//if($this->user['role'] != 'resale') $this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=default');
		$start_time = strtotime(date('Y-m-01 00:00:00', time()));
		$end_time = strtotime(date('Y-m-d 23:59:59',strtotime(date('Y-m-01 23:59:59', time()).' +1 month -1 day')));
		/*$merchants = M('User')->alias('u')
			->leftJoin(M('User_Credit')->getTableName().' AS ct', 'ct.user_id = u.id')
			->group('ct.user_id')
			->columns('u.*, SUM(ct.credit) AS remain, SUM(IF(ct.credit > 0, ct.credit, 0)) AS recharge, SUM(IF(ct.credit < 0, ct.credit, 0)) AS consume, ct.id as ct_id')
			->where('u.parent_id = '.$this->user['id'].' and ct.create_time <= '.$end_time.' and ct.create_time >= '.$start_time.' and role="seller"')
			->order('u.id DESC');*/
		$merchants1 = M('User')->alias('u')->where('u.parent_id = '.$this->user['id'].' and u.role="seller"')->fetchRows();
		foreach($merchants1 as $key=>$merchant) {
			$credits = M('User_Credit')->alias('ct')->group('ct.user_id')
				->columns('SUM(ct.credit) AS remain, SUM(IF(ct.credit > 0, ct.credit, 0)) AS recharge, SUM(IF(ct.credit < 0, ct.credit, 0)) AS consume, ct.id as ct_id')
				->where('ct.user_id = '.$merchant['id'].' and ct.create_time <= '.$end_time.' and ct.create_time >= '.$start_time)->fetchRows();
			$this->seller[$key]['remain'] = $credits[0]['ct_id'] ? $credits[0]['remain'] : 0;
			$this->seller[$key]['recharge'] = $credits[0]['ct_id'] ? $credits[0]['recharge'] : 0;
			$this->seller[$key]['consume'] = $credits[0]['ct_id'] ? $credits[0]['consume'] : 0;
			$this->seller[$key]['name'] = $merchant['nickname'] ? $merchant['nickname'] : $merchant['username'];
		}
		$view = $this->_initView();
//		$view->merchants = $merchants->fetchRows();
		$view->merchants = $this->seller;
		$view->render('views/shoplist.php');
	}
}