<?php

class Cart_Observer_Point extends Suco_Model implements Cart_Observer_Interface
{
	public function observer(array $cart)
	{
		$amount = M('Cart')->getUsePoint() / M('Setting')->get('points_rate');
		if ($amount > $cart['total_pay_amount']) {
			$amount = $cart['total_pay_amount'];
		}

		$cart['total_use_points'] = $amount;
		$cart['total_save'] += $amount;
		$cart['total_pay_amount'] -= $amount;
		return $cart;
	}
}