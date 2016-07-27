<?php

class Cart_Observer_Shipping implements Cart_Observer_Interface
{
	public function observer($cart)
	{
		$weight = $cart->getStatus('total_weight');
		$fid = $cart->getStatus('freight_id');

		$sf = M('Shipping_Freight')->getById((int)$fid);

		$freight = @ceil(($weight-$sf['first_weight'])/$sf['second_weight'])
			*$sf['second_freight']+$sf['first_freight'];

		$cart->setStatus('total_freight', $freight)
			->setStatus('shipping_id', $sf['shipping_id'])
			->setStatus('total_pay_amount', $cart->getStatus('total_pay_amount')+$freight);
		
		return $cart;
	}
}