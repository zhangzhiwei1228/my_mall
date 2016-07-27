<?php

class Cart_Observer_Coupon extends Suco_Model implements Cart_Observer_Interface
{
	public function observer($cart)
	{
		//$cart->setStatus('total_amount', $total);
		$cr = M('Coupon_Receive')->find(array('code'=>$_SESSION['coupon_code']));
		$amount = $cart->getStatus('total_pay_amount') > $cr->coupon['amount'] ? $cr->coupon['amount'] : $cart->getStatus('total_pay_amount');

		$total_freight = $cart->getStatus('total_freight');
		$total_pay_amount = $cart->getStatus('total_pay_amount');

		$cart->setStatus('coupon_amount', $amount)
			->setStatus('coupon_code', $_SESSION['coupon_code'])
			->setStatus('total_save', $cart->getStatus('total_save') + $amount)
			->setStatus('total_freight', $cr->coupon['amount'] < $total_pay_amount ? $total_freight - $amount : 0)
			->setStatus('total_pay_amount', $total_pay_amount - $amount)
			->save();

		return $cart;
	}
}