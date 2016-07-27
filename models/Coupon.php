<?php

class Coupon extends Abstract_Model
{
	protected $_name = 'coupon';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if (isset($data['start_time']) && !is_int($data['start_time'])) {
			$data['start_time'] = strtotime($data['start_time']);
		}
		if (isset($data['end_time']) && !is_int($data['end_time'])) {
			$data['end_time'] = strtotime($data['end_time']);
		}
		if (isset($data['precond'])) {
			$data['precond'] = serialize($data['precond']);
		}

		return parent::inputFilter($data);
	}

	public function outputFilter($data)
	{
		if (isset($data['precond'])) {
			$data['precond'] = unserialize($data['precond']);
		}

		return parent::outputFilter($data);
	}

	public function getUniversalCode()
	{
		//每小时发放一个万能礼券
		$code = substr(md5('UNV'.abs(crc32(date('YmdH')*.54))), -16);
		for ($i = 0; $i < strlen($code); $i++) {
			if ($i%4 == 0 && $i != 0) {
				$str .= '-';
			}
			$str .= substr($code, $i, 1);
		}
		return strtoupper($str);
	}

	public function checkCoupon($code, $user)
	{
		$coupon = M('Coupon')->select()
			->where('code = ?', $code)
			->fetchRow();

		if (!$coupon->exists()) {
			throw new App_Exception('此红包不存在。');
		}
		if ($coupon->precond['members']
			&& !stristr($coupon->precond['members'], $user['username'])) {
			throw new App_Exception('您不允许领取此红包');
		}
		if ($coupon->start_time > time()) {
			throw new App_Exception('活动还未开始，不允许领取');
		}
		if ($coupon->end_time < time()) {
			throw new App_Exception('活动已结束，不允许领取');
		}
		$mcp = M('Member_Coupon')->select()
				->where('member_id = ? AND coupon_id = ?', array($user['id'], $coupon['id']))
				->fetchRow();
		if ($mcp->exists() && $mcp['is_used']) {
			throw new App_Exception('您已领取此并使用了此红包，请勿重复操作');
		} elseif ($mcp->exists()) {
			throw new App_Exception('您已领取此红包，请勿重复操作');
		}

		return $coupon;
	}

	//领取礼券
	public function receiveCoupon($code, $user)
	{
		$coupon = $this->checkCoupon($code, $user);

		M('Member_Coupon')->insert(array(
			'member_id' => $user['id'],
			'coupon_id' => $coupon['id'],
			'code' => $coupon['code'],
			'is_used' => 0,
			'expire_time' => time() + (3600 * 24 * $coupon['period'])
		));

		return $coupon;
	}
}

?>