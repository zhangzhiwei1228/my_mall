<?php

class User extends Abstract_User
{
	protected $_name = 'user';
	protected $_primary = 'id';
	protected $_cookie_name = __CLASS__;
	protected $_login_timeout = 3600;

	protected $_referenceMap = array(
		'referrals' => array(
			'class' => __CLASS__,
			'type' => 'hasone',
			'source' => 'referrals_id',
			'target' => 'id'
		),
		'extends' => array(
			'class' => 'User_Extend',
			'type' => 'hasmany',
			'source' => 'id',
			'target' => 'user_id'
		),
		'grade' => array(
			'class' => 'User_Grade',
			'type' => 'hasone',
			'source' => 'grade_id',
			'target' => 'id'
		),
		'parent' => array(
			'class' => 'User',
			'type' => 'hasone',
			'source' => 'id',
			'target' => 'parent_id'
		)
	);

	public function getStaffBonus($user)
	{
		$staff = M('User')->select('id, username,role')
			->where('parent_id = ? AND role =\'staff\'', $user['id'])
			->fetchRows();

		foreach($staff as $row) {
			$bonus = $row->getBonus($user['role']);
			$ct['coin1']['credit_coin']['total'] += $bonus['coin1']['credit_coin']['total'];
			$ct['coin2']['credit_coin']['total'] += $bonus['coin2']['credit_coin']['total'];
			$ct['vouchers1']['vouchers']['total'] += $bonus['vouchers1']['vouchers']['total'];
			$ct['vouchers2']['vouchers']['total'] += $bonus['vouchers2']['vouchers']['total'];
			$ct['buy1']['worth_gold']['total'] += $bonus['buy1']['worth_gold']['total'];
			$ct['buy2']['worth_gold']['total'] += $bonus['buy2']['worth_gold']['total'];
			$ct['conversion1']['credit']['total'] += $bonus['conversion1']['credit']['total'];
			$ct['conversion2']['credit']['total'] += $bonus['conversion2']['credit']['total'];

//			$ct['amount'] += $bonus['amount'];
			$ct['amount'] += $bonus['amount_seller'];
		}
		if($user['role'] == 'agent' || ($user['role'] == 'resale' && $user['resale_grade'] == 4)) {
			//我代理地区会员本月消费积分币
			$agentArea = M('Region')->getById((int)$user['agent_aid']);
			$aIds = $agentArea->getChildIds();

			$ct['area']['member'] = M('Order')->select('SUM(total_credit_coin) AS t_coin')
				->where('area_id IN ('.($aIds?$aIds:0).') AND status IN (2,3,4)')
				->fetchRow()
				->toArray();
			//我代理地区我下线的会员本月商城消费使用抵用券
			$ct['area']['member_v'] = M('Order')->select('SUM(total_vouchers) AS t_coin')
				->where('area_id IN ('.($aIds?$aIds:0).') AND status IN (2,3,4)')
				->fetchRow()
				->toArray();
			//我代理地区商家本月使用免费积分
			$uIds = M('User')->select('id')
				->where('area_id IN ('.($aIds?$aIds:0).')')
				->fetchCols('id');

			$ct['area']['seller'] = M('User_Credit')->select('ABS(SUM(credit)) AS t_credit')
				->where('credit < 0 AND type = \'credit\' AND user_id IN ('.($uIds?implode(',',$uIds):0).')')
				->fetchRow()
				->toArray();
			//我代理地区我下线的商家本月使用抵用券
			$ct['area']['seller_v'] = M('User_Credit')->select('ABS(SUM(credit)) AS t_credit')
				->where('credit < 0 AND type = \'vouchers\' AND user_id IN ('.($uIds?implode(',',$uIds):0).')')
				->fetchRow()
				->toArray();
			//我代理地区我下线的商家本月收到抵用金
			$ct['area']['seller_w'] = M('User_Credit')->select('ABS(SUM(credit)) AS t_credit')
				->where('credit > 0 AND type = \'worth_gold\' AND user_id IN ('.($uIds?implode(',',$uIds):0).')')
				->fetchRow()
				->toArray();
			if($user['role'] == 'agent') {
				$type = 26;
			} else {
				$type = 28;
			}
			$pro = M('Proportion')->select('price,english')->where('type='.(int)$type)->fetchRows()->toArray();
			foreach($pro as $row) {
				$pro_data[$row['english']] = $row['price'];
			}

			$ct['amount'] += $ct['area']['seller']['t_credit']*$pro_data['credit_seller'];
			$ct['amount'] += $ct['area']['member']['t_coin']*$pro_data['credit_coin'];
			$ct['amount'] += $ct['area']['member_v']['t_coin']*$pro_data['vouchers_user'];
			$ct['amount'] += $ct['area']['seller_v']['t_credit']*$pro_data['vouchers_seller'];
			$ct['amount'] += $ct['area']['seller_w']['t_credit']*$pro_data['worth_gold_seller'];
		}

		return $ct;
	}

	/**
	 * 计算奖金
	 * @param	array $data
	 * @return array
	 */
	public function getBonus($user,$prole = false)
	{
		$user1 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id = ? AND role =\'member\'', $user['id'])
			->fetchRows();

		$month = strtotime(date('Y-m-1'));
		$ct['last2']['num'] = 0;
		$ct['last2']['vip'] = 0;
		$ct['history2']['num'] = 0;
		$ct['history2']['vip'] = 0;
		$ct['last1']['num'] = 0;
		$ct['last1']['vip'] = 0;
		$ct['history1']['num'] = 0;
		$ct['history1']['vip'] = 0;
		foreach($user1 as $row) {
			$ids1[] = $row['id'];
			if ($row['create_time'] > $month) {
				$ct['last1']['num'] += 1;
				$ct['last1']['vip'] += $row['is_vip']?1:0;
			}
			$ct['history1']['num'] += 1;
			$ct['history1']['vip'] += $row['is_vip']?1:0;
		}

		//一级会员消费积分币
		$ids = $ids1 ? implode(',', $ids1) : 0;
		$ct['coin1'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"credit_coin"')
			->fetchOnKey('type')
			->toArray();
		//一级会员商城消费使用抵用券
		$ct['vouchers1'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"vouchers"')
			->fetchOnKey('type')
			->toArray();
		//我的一级会员抵用券购买抵用金
		$ct['buy1'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold" and status=1 and conversion='.'"vouchers-worth_gold"')
			->fetchOnKey('type')
			->toArray();

		//我的一级会员抵用券转换成免费积分
		$ct['conversion1'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"credit" and status=2 and conversion='.'"vouchers-credit"')
			->fetchOnKey('type')
			->toArray();
		//统计二级会员
		$user2 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id != 0 AND parent_id IN ('.$ids.') AND role =\'member\'')
			->fetchRows();

		foreach($user2 as $row) {
			$ids2[] = $row['id'];
			if ($row['create_time'] > $month) {
				$ct['last2']['num'] += 1;
				$ct['last2']['vip'] += $row['is_vip']?1:0;
			}
			$ct['history2']['num'] += 1;
			$ct['history2']['vip'] += $row['is_vip']?1:0;
		}

		//二级会员消费积分币
		$ids = $ids2 ? implode(',', $ids2) : 0;
		$ct['coin2'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.') AND type='.'"credit_coin"' )
			->fetchOnKey('type')
			->toArray();
		//我的二级会员商城消费使用抵用券
		$ct['vouchers2'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"vouchers"')
			->fetchOnKey('type')
			->toArray();
		//我的二级会员抵用券购买抵用金
		$ct['buy2'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold" and status=1 and conversion='.'"vouchers-worth_gold"')
			->fetchOnKey('type')
			->toArray();
		//我的二级会员抵用券转换成免费积分
		$ct['conversion2'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"credit" and status=2 and conversion='.'"vouchers-credit"')
			->fetchOnKey('type')
			->toArray();
		//发展的商家
		$user3 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id = ? AND role =\'seller\'', $user['id'])
			->fetchRows();
		foreach ($user3 as $row) {
			$ids3[] = $row['id'];
		}

		//商家员工
		$ids = $ids3 ? implode(',', $ids3) : 0;

		$user4 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id != 0 AND parent_id IN ('.$ids.') AND role =\'staff\'')
			->fetchRows();
		foreach ($user4 as $row) {
			$ids4[] = $row['id'];
		}

		//商家使用免费积分

		$ct['seller'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"credit"'.' and create_time >='.$month )
			->fetchOnKey('type')
			->toArray();
		//我的商家本月赠送抵用券
		$ct['seller_v'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"vouchers"'.' and create_time >='.$month )
			->fetchOnKey('type')
			->toArray();
		//我的商家本月收到抵用金
		$ct['seller_w'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold"'.' and create_time >='.$month )
			->fetchOnKey('type')
			->toArray();
		//区域统计
		$ct['area_seller'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')')
			->fetchOnKey('type')
			->toArray();

		$ct['area_member'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.')')
			->fetchOnKey('type')
			->toArray();

		//一级会员消费积分币

		$ids = $ids4 ? implode(',', $ids4) : 0;
		// $ct['coin3'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
		// 	->where('credit < 0 AND user_id IN ('.$ids.')')
		// 	->fetchOnKey('type')
		// 	->toArray();

		//商家员工会员一级会员
		$user5 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id != 0 AND parent_id IN ('.$ids.') AND role =\'member\'')
			->fetchRows();
		foreach ($user5 as $row) {
			$ids5[] = $row['id'];
		}

		//一级会员消费积分币
		$ids = $ids5 ? implode(',', $ids5) : 0;

		$ct['coin3'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.') AND type='.'"credit_coin"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的一级会员商城消费使用抵用券
		$ct['vouchers3'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.') AND type='.'"vouchers"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的一级会员抵用券购买抵用金
		$ct['buy3'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold" and status=1 and conversion='.'"vouchers-worth_gold"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的一级会员抵用券转换成免费积分
		$ct['conversion3'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"credit" and status=2 and conversion='.'"vouchers-credit"')
			->fetchOnKey('type')
			->toArray();
		//商家员工会员二级会员
		$user6 = M('User')->select('id, username, is_vip, create_time')
			->where('parent_id != 0 AND parent_id IN ('.$ids.') AND role =\'member\'')
			->fetchRows();
		foreach ($user6 as $row) {
			$ids6[] = $row['id'];
		}

		//二级会员消费积分币
		$ids = $ids6 ? implode(',', $ids6) : 0;
		$ct['coin4'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.') AND type='.'"credit_coin"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的二级会员商城消费使用抵用券
		$ct['vouchers4'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
			->where('credit < 0 AND user_id IN ('.$ids.') AND type='.'"vouchers"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的二级会员抵用券购买抵用金
		$ct['buy4'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold" and status=1 and conversion='.'"vouchers-worth_gold"')
			->fetchOnKey('type')
			->toArray();
		//我的商家的二级会员抵用券转换成免费积分
		$ct['conversion4'] = M('User_Credit')->select('type,status,conversion, ABS(SUM(credit)) AS total')
			->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"credit" and status=2 and conversion='.'"vouchers-credit"')
			->fetchOnKey('type')
			->toArray();
		//获取收益比例
		switch($prole) {
			case 'seller':
				$type_id=24;
				break;
			case 'agent':
				$type_id=27;
				break;
			case 'resale':
				$type_id=29;
				break;
			case 'resale-1' :
				$type_id = 32;
				break;
			case 'resale-2' :
				$type_id = 31;
				break;
			case 'resale-3' :
				$type_id = 30;
				break;
		}
		if($type_id) {
			$pro = M('Proportion')->select('price,english')->where('type='.$type_id)->fetchRows()->toArray();
			foreach($pro as $row) {
				$pro_data[$row['english']] = $row['price'];
			}
		}

		//本月激活会员
//		$ct['amount'] = $ct['last1']['vip']+$ct['last2']['vip']*5;
		/*$ct['amount'] = $ct['last1']['vip']*5;
		$ct['amount'] += ($ct['coin1']['credit_coin']['total']*0.1)+($ct['coin2']['credit_coin']['total']*0.05);
		$ct['amount_seller'] += ($ct['coin1']['credit_coin']['total']*0.05)+($ct['coin2']['credit_coin']['total']*0.05);
		$ct['amount'] += ($ct['coin3']['credit_coin']['total']*0.02)+($ct['coin4']['credit_coin']['total']*0.02);
		$ct['amount'] += ($ct['seller']['credit']['total']*0.003);*/

		$ct['amount'] = $ct['last1']['vip']*$pro_data['vip'];
		$ct['amount'] += ($ct['coin1']['credit_coin']['total']*$pro_data['credit_coin1'])+($ct['coin2']['credit_coin']['total']*$pro_data['credit_coin2']);

		$ct['amount_seller'] = ($ct['coin1']['credit_coin']['total']*$pro_data['credit_coin1'])+($ct['coin2']['credit_coin']['total']*$pro_data['credit_coin2']);
		$ct['amount_seller'] += ($ct['vouchers1']['vouchers']['total']*$pro_data['vouchers1'])+($ct['vouchers2']['vouchers']['total']*$pro_data['vouchers2']);
		$ct['amount_seller'] += ($ct['buy1']['worth_gold']['total']*$pro_data['vouchers-worth_gold1'])+($ct['buy2']['worth_gold']['total']*$pro_data['vouchers-worth_gold2']);
		$ct['amount_seller'] += ($ct['conversion1']['credit']['total']*$pro_data['vou_con_credit1'])+($ct['conversion2']['credit']['total']*$pro_data['vou_con_credit2']);
		$ct['amount'] += ($ct['coin3']['credit_coin']['total']*$pro_data['credit_coin_seller1'])+($ct['coin4']['credit_coin']['total']*$pro_data['credit_coin_seller2']);
		$ct['amount'] += ($ct['seller']['credit']['total']*$pro_data['credit_seller']);

		$ct['amount'] += ($ct['vouchers1']['vouchers']['total']*$pro_data['vouchers1']);
		$ct['amount'] += ($ct['vouchers2']['vouchers']['total']*$pro_data['vouchers2']);
		$ct['amount'] += ($ct['vouchers3']['vouchers']['total']*$pro_data['seller_vouchers1']);
		$ct['amount'] += ($ct['vouchers4']['vouchers']['total']*$pro_data['seller_vouchers2']);
		$ct['amount'] += ($ct['seller_v']['vouchers']['total']*$pro_data['seller_vouchers']);
		$ct['amount'] += ($ct['seller_w']['worth_gold']['total']*$pro_data['seller_worth_gold']);


		$ct['amount'] += ($ct['buy1']['worth_gold']['total']*$pro_data['vouchers-worth_gold1']);
		$ct['amount'] += ($ct['buy2']['worth_gold']['total']*$pro_data['vouchers-worth_gold2']);
		$ct['amount'] += ($ct['conversion1']['credit']['total']*$pro_data['vou_con_credit1']);
		$ct['amount'] += ($ct['conversion2']['credit']['total']*$pro_data['vou_con_credit2']);

		$ct['amount'] += ($ct['buy3']['worth_gold']['total']*$pro_data['seller_vouchers-worth_gold1']);
		$ct['amount'] += ($ct['buy4']['worth_gold']['total']*$pro_data['seller_vouchers-worth_gold2']);
		$ct['amount'] += ($ct['conversion3']['credit']['total']*$pro_data['seller_vou_con_credit1']);
		$ct['amount'] += ($ct['conversion4']['credit']['total']*$pro_data['seller_vou_con_credit2']);

		return $ct;
	}
	//代理商管理员按地区计算
	public function agentearnings($user) {
		$destination = $user['destination'];
		if($destination) {
			$month = strtotime(date('Y-m-1'));
			//商家管理员
			$seller = M('User')->select('id, username, is_vip, create_time')
				->where('role =\'seller\' AND area_id IN ('.$destination.')')
				->fetchRows();
			foreach($seller as $row) {
				$ids1[] = $row['id'];
			}
			$ids = $ids1 ? implode(',', $ids1) : 0;
			$ct['seller'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
				->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"credit"'.' and create_time >='.$month )
				->fetchOnKey('type')
				->toArray();
			//多地区会员本月消费
			$user_areas =  M('User_Area')->select('id, user_id,area_id')
				->where('area_id IN ('.$destination.')')
				->fetchRows();
			foreach($user_areas as $row) {
				$ids2[] = $row['user_id'];
			}
			$ids3 = $ids2 ? implode(',', $ids2) : 0;
			$ct['userarea'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
				->where('credit < 0 AND user_id IN ('.$ids3.') AND type='.'"credit_coin"'.' and create_time >='.$month )
				->fetchOnKey('type')
				->toArray();


			//商家本月使用抵用券
			$ct['seller_v'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
				->where('credit < 0 AND user_id IN ('.$ids.')AND type='.'"vouchers"'.' and create_time >='.$month )
				->fetchOnKey('type')
				->toArray();
			//商家本月收到的抵佣金
			$ct['seller_w'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
				->where('credit > 0 AND user_id IN ('.$ids.')AND type='.'"worth_gold"'.' and create_time >='.$month )
				->fetchOnKey('type')
				->toArray();
			//会员本月消费使用的抵用券
			$ct['userarea_v'] = M('User_Credit')->select('type, ABS(SUM(credit)) AS total')
				->where('credit < 0 AND user_id IN ('.$ids3.') AND type='.'"vouchers"'.' and create_time >='.$month )
				->fetchOnKey('type')
				->toArray();
		}
		//获取收益比例
		$pro = M('Proportion')->select('price,english')->where('type=26')->fetchRows()->toArray();
		foreach($pro as $row) {
			$pro_data[$row['english']] = $row['price'];
		}

		$ct['amount'] = isset($ct['seller']) ? $ct['seller']['credit']['total']*$pro_data['credit_seller'] : 0;
		$ct['amount'] += isset($ct['userarea']) ? $ct['userarea']['credit_coin']['total']*$pro_data['credit_coin'] : 0;

		$ct['amount'] += isset($ct['seller_v']) ? $ct['seller_v']['vouchers']['total']*$pro_data['vouchers_seller'] : 0;
		$ct['amount'] += isset($ct['seller_w']) ? $ct['seller_w']['worth_gold']['total']*$pro_data['worth_gold_seller'] : 0;
		$ct['amount'] += isset($ct['userarea']) ? $ct['userarea_v']['vouchers']['total']*$pro_data['vouchers_user'] : 0;
		return $ct;
	}

	/**
	 * 输入验证
	 * @param	array $data
	 * @return array
	 */
	public function validation($data, $event)
	{
		if ($data['username']) {
			if (strlen($data['username']) < 4) {
				throw new App_Exception('用户名不能少于4个字符');
			}
		}
		if ($data['email']) {
			if (!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $data['email'])) {
				throw new App_Exception('邮箱格式不正确');
			}
		}
	}

	/**
	 * 输入过滤
	 * @param	array $data
	 * @return array
	 */
	public function inputFilter($data)
	{
		if (isset($data['expriy_time']) && (!is_numeric($data['expriy_time']) || strpos($data['expriy_time'], '.') !== false)) {
			$data['expriy_time'] = strtotime($data['expriy_time']);
		}
		
		if (@!$data['pay_salt']) {
			if (isset($data['pay_pass']) && $data['pay_pass'] && $data['pay_pass'] != '#password#') {
				$data['pay_salt'] = substr(uniqid(rand()), -6);
				$data['pay_pass'] = $this->encrypt($data['pay_pass'], $data['pay_salt']);
			} else {
				unset($data['pay_salt']); unset($data['pay_pass']);
			}
		}

		return parent::inputFilter($data);
	}
	
	/**
	 * 添加用户
	 * @param	mixed $data
	 * @return int
	 */
	public function insert($data)
	{
		$id = parent::insert($data);
		if (isset($data['profile']) && $data['profile']) {
			M('User_Profile')->insert(array_merge((array)$data['profile'], array('user_id' => $id)));
		}

		//初始化
		$user = M('User')->getById($id);

		//初始化帐户等级
		$grade = M('User_Grade')->select()
			->where('min_exp <= ? AND max_exp >= ?', $user->exp)
			->fetchRow();
		$user->grade_id = $grade->id;
		
		$user->save();

		return $id;
	}
	
	/**
	 * 更新用户
	 * @param	mixed $data
	 * @param	string $id
	 * @return int
	 */
	public function updateById($data, $id)
	{
		if (is_array($data)) {
			if (isset($data['profile']) && $data['profile']) {
				$profile = M('User_Profile')->getById(array('user_id' => $id));
				$profile->save(array_merge((array)$data['profile'], array('user_id' => $id)));
			}
		}
		return parent::updateById($data, $id);
	}

	/**
	 * 删除用户
	 * @param	string $id 用户ID
	 * @return int
	 */
	public function deleteById($id)
	{
		$id = parent::deleteById($id);
		M('User_Extend')->delete('user_id = '.(int)$id);
		M('User_Credit')->delete('user_id = '.(int)$id);
		M('User_Address')->delete('user_id = '.(int)$id);
		M('User_Certify')->delete('user_id = '.(int)$id);
		M('User_Bank')->delete('user_id = '.(int)$id);
		M('User_Bind')->delete('user_id = '.(int)$id);
		M('User_Blacklist')->delete('user_id = '.(int)$id);
		M('User_Cart')->delete('user_id = '.(int)$id);
		M('User_Remind')->delete('user_id = '.(int)$id);
		return $id;
	}

	/**
	 * 通过用户名查找
	 * @param	string $user 用户名
	 * @return object Suco_Db_Table_Row 用户对象
	 */
	public function getByUserName($username)
	{
		return $this->select()
			->where('username = ?', $username)
			->fetchRow();
	}

	/**
	 * 登录
	 * @param	string $user 用户名
	 * @param	string $pass 密码
	 * @param	int $timeout 登录时间
	 * @return object Suco_Db_Table_Row 用户对象
	 */
	public function login($user, $pass, $timeout = 3600)
	{
		$this->setLoginTimeout($timeout);
		$user = parent::login($user, $pass);
		$user->exp(5); //登录增加经验值

		//检查帐户等级
		// $grade = M('User_Grade')->select()
		// 	->where('min_exp <= ? AND max_exp >= ?', $user->stat['exp'])
		// 	->fetchRow();
		// $user->grade_id = $grade->id;
		// $user->save();

		//处理订单
		M('Order')->process($user['id']);

		return $user;
	}

	/**
	 * 检查交易密码
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @param	string $pass 密码
	 * @return bool
	 */
	public function checkPayPass($user, $pass)
	{
		return $this->encrypt($pass, $user['pay_salt']) == $user['pay_pass'] ? true : false;
	}

	/**
	 * 增加或减少经验值
	 * @param	object 	$user 用户对象
	 * @param	int 	$val 值
	 * @return 	bool
	 */
	public function exp($user, $val)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}

		//初始化帐户
		$user->refresh();

		if ($val > 0) { //增加经验
			$user->exp += $val;
		} else { //减少经验
			$user->exp -= abs($val);
		}

		$user->save();
	}

	/**
	 * 检查对象是否已被收藏
	 * @return object Suco_Db_Table_Rowset
	 */
	public function isCollect($user, $obj)
	{
		$refType = $obj->getName();
		$collect = M('User_Collect')->select()
			->where('ref_type = ? AND ref_id = ? AND user_id = ?', array(
				$refType, $obj['id'], $user['id']
			))
			->fetchRow();

		return $collect->exists();
	}

	/**
	 * 增加或减少积分
	 * @param	object 	$user 用户对象
	 * @param	int 	$val 值
	 * @param	string 	$note 备注
	 * @return 	bool
	 */
	public function credit($user, $val, $note, $status=0, $conversion='', $rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}

		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'credit',
			'status' => $status,
			'conversion' => $conversion,
			'credit' => $val,
			'note' => $note,
			'rid' => $rid,
			'create_time' => time()
		));

		if ($val > 0) { //增加经验
			$user->credit += $val;
		} elseif ($val < 0) { //减少经验
			$user->credit -= abs($val);
		}
		$user->save();
	}

	//抵用获得和使用
	public function worthGold($user, $val, $note, $code='',$status=0,$conversion='',$rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}
		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'worth_gold',
			'status' => $status,
			'conversion' => $conversion,
			'credit' => $val,
			'note' => $note,
			'code' => $code,
			'rid' => $rid,
			'create_time' => time()
		));
		if ($val > 0) { //增加经验
			$user->worth_gold += $val;
		} elseif ($val < 0) { //减少经验
			$user->worth_gold -= abs($val);
		}
		$user->save();
	}
	//现金支付抵佣金
	public function cash($user, $val, $note, $code='', $status=0, $rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}
		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'cash',
			'credit' => $val,
			'note' => $note,
			'code' => $code,
			'status' => $status,
			'rid' => $rid,
			'create_time' => time()
		));
		$user->save();
	}

	public function creditHappy($user, $val, $note,$status=0,$conversion='', $rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}

		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'credit_happy',
			'status' => $status,
			'conversion' => $conversion,
			'credit' => $val,
			'note' => $note,
			'rid' => $rid,
			'create_time' => time()
		));

		if ($val > 0) { //增加经验
			$user->credit_happy += $val;
		} elseif ($val < 0) { //减少经验
			$user->credit_happy -= abs($val);
		}
		$user->save();
	}

	public function creditCoin($user, $val, $note, $status=0, $conversion='', $rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}

		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'credit_coin',
			'status' => $status,
			'conversion' => $conversion,
			'credit' => $val,
			'note' => $note,
			'rid' => $rid,
			'create_time' => time()
		));

		if ($val > 0) { //增加经验
			$user->credit_coin += $val;
		} elseif ($val < 0) { //减少经验
			$user->credit_coin -= abs($val);
		}
		$user->save();
	}
	//抵用券
	public function vouchers($user, $val, $note,$status=0,$conversion='', $rid=0)
	{
		//检查帐户
		if (!$user->exists()) {
			throw new App_Exception('帐户不存在');
		}

		$user->refresh();
		M('User_Credit')->insert(array(
			'user_id' => $user['id'],
			'type' => 'vouchers',
			'status' => $status,
			'conversion' => $conversion,
			'credit' => $val,
			'note' => $note,
			'rid' => $rid,
			'create_time' => time()
		));

		if ($val > 0) { //增加经验
			$user->vouchers += $val;
		} elseif ($val < 0) { //减少经验
			$user->vouchers -= abs($val);
		}
		$user->save();
	}

	/**
	 * 冻结资金
	 * @param	object 	$user 用户对象
	 * @param	float 	$amount 金额 （正数为加，负数为减）
	 * @return 	null
	 */
	public function unusable($user, $amount)
	{
		$user->refresh();
		if (!$user instanceof Suco_Db_Table_Row) { throw new App_Exception('参数错误!'); }
		if ($user->balance < $amount) { throw new App_Exception('操作失败!当前帐户余额不足.'); }

		$user->unusable += $amount;
		$user->balance -= $amount;
		$user->save();
	}

	/**
	 * 帐户收入
	 * @param	object 	$user 用户对象
	 * @param	string 	$type 类型
	 * @param	float 	$amount 金额
	 * @param	string 	$voucher 凭证号
	 * @param	string 	$remark 备注
	 * @return 	null
	 */
	public function income($user, $type, $amount, $voucher, $remark = '')
	{
		$user->refresh();
		if (!$user instanceof Suco_Db_Table_Row) { throw new App_Exception('参数错误!'); }
		if ($amount <= 0) { throw new App_Exception('金额不得小于或等于零.'); }

		$id = M('User_Money')->insert(array(
			'type' => $type,
			'user_id' => $user->id,
			'amount' => abs($amount),
			'voucher' => $voucher,
			'remark' => $remark,
			'status' => 1,
		));

		return M('User_Money')->getById($id);
	}

	/**
	 * 帐户支出
	 * @param	object 	$user 用户对象
	 * @param	string 	$type 类型
	 * @param	float 	$amount 金额
	 * @param	string 	$voucher 凭证号
	 * @param	string 	$remark 备注
	 * @return 	null
	 */
	public function expend($user, $type, $amount, $voucher, $remark = '')
	{
		$user->refresh();
		if (!$user instanceof Suco_Db_Table_Row) { throw new App_Exception('参数错误!'); }
		if ($amount <= 0) { throw new App_Exception('金额不得小于或等于零.'); }

		$id = M('User_Money')->insert(array(
			'type' => $type,
			'user_id' => $user->id,
			'amount' => abs($amount) * -1,
			'voucher' => $voucher,
			'remark' => $remark,
			'status' => 1,
		));
		return M('User_Money')->getById($id);
	}

	/**
	 * 帐户充值
	 * @param	object 	$user 用户对象
	 * @param	float 	$amount 金额 （正数为加，负数为减）
	 * @param	float 	$fee 手续费
	 * @param	string 	$voucher 凭证号
	 * @param	string 	$remark 备注
	 * @param	int 	$payment_id 充值方式ID
	 * @return 	null
	 */
	public function recharge($user, $amount, $fee, $voucher, $remark, $payment_id)
	{
		$user->refresh();
		if (!$user instanceof Suco_Db_Table_Row) { throw new App_Exception('参数错误!'); }
		if ($amount <= 0) { throw new App_Exception('金额不得小于或等于零.'); }

		$id = M('User_Recharge')->insert(array(
			'user_id' => $user->id,
			'amount' => abs($amount),
			'fee' => abs($fee),
			'voucher' => $voucher,
			'remark' => $remark,
			'payment_id' => $payment_id,
			'status' => 1,
		));

		$payment = M('Payment')->getById((int)$payment_id);
		$remark = ($remark?'('.$remark.')':'');
		$user->income('recharge', $amount, 'RC-'.$id, $payment['name'].' - 充值'.$remark);
		if ($fee > 0) {
			$user->expend('fee', $fee, 'RC-'.$id, $payment['name'].' - 充值手续费'.$remark);
		}

		return M('User_Recharge')->getById($id);
	}

	/**
	 * 帐户提现
	 * @param	object 	$user 用户对象
	 * @param	float 	$amount 金额 （正数为加，负数为减）
	 * @param	float 	$fee 手续费
	 * @param	string 	$voucher 凭证号
	 * @param	string 	$remark 备注
	 * @param	string 	$payee 提现收款人
	 * @param	array 	$bank 提现银行
	 * @return 	null
	 */
	public function withdraw($user, $amount, $fee, $voucher, $remark, $payee, array $bank)
	{
		$user->refresh();
		if (!$user instanceof Suco_Db_Table_Row) { throw new App_Exception('参数错误!'); }
		if ($user->balance < $amount + $fee) { throw new App_Exception('操作失败!当前帐户余额不足.'); }
		if ($amount <= 0) { throw new App_Exception('金额不得小于或等于零.'); }
		if (!$bank) { throw new App_Exception('银行信息不正确.'); }

		$id = M('User_Withdraw')->insert(array(
			'user_id' => $user->id,
			'amount' => abs($amount),
			'fee' => abs($fee),
			'voucher' => $voucher,
			'remark' => $remark,
			'payee' => $payee,
			'bank_name' => $bank['bank_name'],
			'bank_account' => $bank['bank_account'],
			'bank_sub_branch' => $bank['bank_sub_branch'],
			'bank_swift_code' => $bank['bank_swift_code'],
			'status' => 1,
		));

		//冻结金额
		$user->unusable($amount + $fee);
		$remark = ($remark?'('.$remark.')':'');

		if ($user->balance > $amount + $fee) {
			$amount = $amount;
		} else {
			$amount = $amount - $fee;
		}
		$user->expend('withdraw', $amount, 'WD-'.$id, $bank['bank_name'].' - 提现'.$remark);
		if ($fee > 0) {
			$user->expend('fee', $fee, 'WD-'.$id, $bank['bank_name'].' - 提现手续费'.$remark);
		}

		return M('User_Withdraw')->getById($id);
	}

	/**
	 * 设置认证状态
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @param	string $type 认证类型
	 * @param	int $status 状态
	 * @param	array $attachments 附件 ($_FILES)
	 */
	public function setAuth($user, $type, $status, $attachments = '')
	{
		if ($attachments) {
			try {
				$src = Suco_File::multiUpload($attachments);
			} catch (Suco_File_Exception $e) {
				throw new App_Exception('文件上传失败! '.$e->getMessage());
			}
		}

		M('User_Certify')->delete('user_id = ? AND type = ?', array($user->id, $type));
		M('User_Certify')->insert(array(
			'user_id' => $user->id,
			'type' => $type,
			'status' => $status,
			'attachments' => $src
		));
	}

	/**
	 * 返回认证状态
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @param	string $type 认证类型
	 * @return 	object Suco_Db_Table_Row 对象
	 */
	public function getAuth($user, $type)
	{
		$auth = M('User_Certify')->select()
			->where('user_id = ? AND type = ?', array($user->id, $type))
			->fetchRow();

		return $auth;
	}

	/**
	 * 设置提醒
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @param	array $setting
	 */
	public function setRemind($user, $setting)
	{
		$remind = M('User_Remind')->select()
			->where('user_id = ?', $user['id'])
			->fetchRow();

		$data = array(
			'user_id' => $user['id'],
			'msg' => json_encode($setting['msg']),
			'sms' => json_encode($setting['sms']),
			'mail' => json_encode($setting['mail']),
		);

		if ($remind->exists()) {
			$remind->save($data);
		} else {
			M('User_Remind')->insert($data);
		}
	}

	/**
	 * 返回提醒设置
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @return	object
	 */
	public function getRemind($user)
	{
		$row = M('User_Remind')->select()
			->where('user_id = ?', $user['id'])
			->fetchRow();

		$row['msg'] = json_decode($row['msg'], 1);
		$row['sms'] = json_decode($row['sms'], 1);
		$row['mail'] = json_decode($row['mail'], 1);

		return $row;
	}

	/**
	 * 返回扩展字段值
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @return	object
	 */
	public function getExtField($user, $field)
	{
		static $extends;
		if (!$extends) {
			$extends = M('User_Extend')->select('field_key, field_value')
				->where('user_id = ?', $user['id'])
				->fetchOnKey('field_key');
		}

		return $extends[$field]['field_value'];
	}

	/**
	 * 返回全部扩展字段
	 * @return	object
	 */
	public function getExtFieldLists($role)
	{
		$config = new Suco_Config_Php();
		$extfields = $config->load(CONF_DIR.'extfields.conf.php');

		return $extfields[$role];
	}

	/**
	 * 检查是否黑名单
	 * @param	object $user Suco_Db_Table_Row 用户对象
	 * @return	bool
	 */
	public function isBlacklist($row)
	{
		return M('User_Blacklist')->count('user_id = ?', $row['id']) ? 1 : 0;
	}

	/**
	 * 关联查询结果 (Auth)
	 * @param	object $user Suco_Db_Table_Rowset
	 * @return	object Suco_Db_Table_Rowset
	 */
	public function hasmanyAuth($rows)
	{
		$ids = $rows->getColumns('id');
		$ids = $ids ? implode(',', $ids) : 0;

		$auths = M('User_Certify')->select('user_id, type')
			->where('user_id IN ('.$ids.') AND status = 1')
			->fetchRows()
			->toArray();

		foreach($auths as $item) {
			$tmp[$item['user_id']][$item['type']] = 1;
		}

		foreach($rows as $k => $row) {
			$row->auth = $tmp[$row['id']];
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}
	public function activateAddCredit($user,$uid) {
		$rec = M('User')->select()
			->where('id='.$uid)
			->fetchRow();

		if ($rec->exists() && $rec['parent_id']) {
			$inviter = M('User')->select()
				->where('id='.(int)$rec['parent_id'])
				->fetchRow();
			$credit = 5;
			$this->credit($rec,$credit,'您已成功激活，奖励积分'.$credit.'点');
			$this->credit($inviter,$credit, '成功推荐用户 '.$rec['username'].' 注册并成功激活，奖励积分'.$credit.'点');
		} else {
			$credit = 10;
			$this->credit($rec,$credit, '您已成功激活，奖励积分'.$credit.'点');
		}
		return true;
	}
	//用户角色
	public function getUserRole($user) {
		$role = $user['role'];
		$resale_grade = $user['resale_grade'];
		$str = $role.'-'.$resale_grade;
		switch($str) {
			case $str == 'resale-1' :
				return '一星分销商';
			case $str == 'resale-2' :
				return '二星分销商';
			case $str == 'resale-3' :
				return '三星分销商';
			case $str == 'resale-4' :
				return '四星分销商管理员';
			case $str == 'staff-0' :
				$parent = $this->getById((int)$user['parent_id']);
				$par_str ='';
				if ($parent['role'] == 'agent') {
					$par_str = '代理商员工';
				} elseif ($parent['role'] == 'seller') {
					$par_str = '商家员工';
				} elseif ($parent['role'] == 'resale') {
					$par_str = '四星分销商员工';
				}
				return $par_str;
			case $str == 'agent-0':
				return '代理商管理员';
			case $str == 'seller-0':
				return '商家管理员';
		}
	}
	//计算商家本月核销抵佣金，获得收益
	public function countGold($user) {
		$BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
		$start = strtotime($BeginDate);
		$end =  strtotime(date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day")));
		$count = M('User_Credit')->select('SUM(credit) AS total')
			->where('user_id = '.(int)$user['id'].' and type='."'".'worth_gold'."'".' and code !='."''".' and create_time >'.$start.' and create_time <'.$end)
			->fetchRow()->toArray();
		$proportion = M('Proportion')->getById(19);
		$earnings = floor($count['total']/$proportion['l_digital']);
		return array('total'=>$count['total'],'earnings'=>$earnings);

	}
}