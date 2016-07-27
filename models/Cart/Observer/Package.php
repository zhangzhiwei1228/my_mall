<?php

class Cart_Observer_Package extends Suco_Model implements Cart_Observer_Interface
{
	public function observer($cart)
	{
		if (!$items = $cart->getItems()) { return $cart; }

		//找套餐
		$packages = M('Goods_Package')->select()
			->fetchRows();
		foreach ($packages as $package) {
			foreach ((array)$package['setting'] as $pid => $setting) {
				foreach ((array)$cart['items'] as $mark => $item) {
					if ($item['id'] == $pid) {
						$pack[$package['id']][$mark] = array_merge($item, array(
							'promotion' => $package['theme'],
							'current_price' => $setting['package_price'],
							'promotion_price' => $setting['package_price'],
						));
						//DEBUG
						//unset($cart['items'][$mark]);
					}
				}
			}
		}

		foreach ((array)$pack as $items) {
			if (count($items) > 1) { //搭配两件以上组合成套餐
				foreach ($items as $mark => $row) {
					$cart['items'][$mark] = $row;
				}
			}
		}

		//重新汇总金额
		$cart['total_save'] = $cart['total_amount'] = $cart['total_pay_amount'] = 0;
		foreach ((array)$cart['items'] as $mark => $item) {
			//小计
			$item['save_price'] = $item['selling_price'] - $item['current_price'];
			$item['subtotal_save'] = $item['save_price'] * $item['purchase_quantity'];
			$cart['items'][$mark] = $item;

			//统计汇总
			$cart['total_save'] += $item['subtotal_save']; //总节省
			$cart['total_amount'] += $item['subtotal_amount']; //商品总额
			$cart['total_pay_amount'] += $item['subtotal_amount'] - $item['subtotal_save'];
		}

		return $cart;
	}
}