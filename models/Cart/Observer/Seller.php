<?php

class Cart_Observer_Seller extends Suco_Model implements Cart_Observer_Interface
{
	public function observer(array $cart)
	{
		//根据卖家裁切订单
		foreach ((array)$cart['items'] as $mark => $row) {
			$ids[] = $row['seller_id'];
			$sid = (int)$row['seller_id'];
			$mark .= $row['shipping_id'];
			$arr[$sid]['items'][$mark] = $row;
		}
		unset($cart['items']);

		$sellers = M('Member')->select()
			->where('id IN ('.($ids ? implode(',', $ids) : 0).')')
			->fetchOnKey('id');
		
		foreach ((array)$arr as $sid => $seller) {
			$arr[$sid]['id'] = $sid;
			$arr[$sid]['store_name'] = $sellers[$sid]->getStoreName();
			foreach ($seller['items'] as $item) {
				$arr[$sid]['total_amount'] += $item['subtotal_amount'];
				$arr[$sid]['total_save'] += $item['subtotal_save'];
				$arr[$sid]['total_freight'] += $item['subtotal_freight'];
				$arr[$sid]['total_pay_amount'] += $item['subtotal_amount'] - $item['subtotal_save'] + $item['subtotal_freight'];
			}
		}
		$cart = array_merge($cart, array('sellers' => $arr));
		return $cart;
	}
}