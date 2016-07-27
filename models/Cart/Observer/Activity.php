<?php

class Cart_Observer_Activity implements Cart_Observer_Interface
{
	public function observer($cart)
	{
		$status = $cart->getAllStatus();
		$status['joined_activity'] = array();
		$activity = M('Goods_Activity')->getAvailableActivity(); //返回可用的活动

		foreach ($activity as $row) {
			$setting = $row[setting];
			switch ($row['type']) {
				case 'freeshipping':
					if ($status['total_pay_amount'] >= $setting['precond_amount']
						&& $status['total_weight'] < $setting['precond_weight']) { //满足金额及重量
						$status['total_pay_amount'] -= $status['total_freight']; //减免运费
						$status['total_freight'] = 0;
						$status['joined_activity'][] = $row['theme'];
					}
					break;
				case 'gift':
					if ($status['total_pay_amount'] >= $setting['precond_amount']) { //满足金额
						$status['gifts'] = $setting['goods_ids'];
						$status['joined_activity'][] = $row['theme'];
					}
					break;
				case 'reduce':
					if ($status['total_pay_amount'] >= $setting['precond_amount']) { //满足金额
						if ($setting['accumulative']) {
							$n = intval($status['total_pay_amount'] / $setting['precond_amount']);
							$reduceAmount = $setting['reduce_amount'] * $n;
						} else {
							$reduceAmount = $setting['reduce_amount'];
						}
						$status['total_pay_amount'] -= $reduceAmount; //减免实付金额
						$status['total_save'] += $reduceAmount;
						$status['joined_activity'][] = $row['theme'];
					}
					break;
			}
			$status['joined_activity'] = array_unique($status['joined_activity']);
			$cart->setAllStatus($status);
		}

		return $cart;
	}
}