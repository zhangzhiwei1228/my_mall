<?php

class Cart_Observer_Goods implements Cart_Observer_Interface
{
	public function observer($cart)
	{
		$items = $cart->getItems();
		if (!$items) {
			return $cart;
		}

		foreach($items as $item) {
			if (!$item['skuId']) continue;
			$ids[] = $item['skuId'];
		}

		$ids = $ids ? implode(',', $ids) : 0;
		$goods = M('Goods_Sku')->alias('gs')
			->leftJoin(M('Goods')->getTableName().' AS g', 'gs.goods_id = g.id')
			->columns('gs.*, gs.id AS sku_id, gs.point1, gs.point2, gs.exts, g.id, g.title, g.thumb AS thumb1, g.earn_points, g.package_weight, g.package_unit, g.package_quantity, g.package_lot_unit')
			->where('gs.id IN ('.$ids.')')
			->fetchOnKey('sku_id')
			->hasmanyPromotions();

		foreach($items as $i => $item) {
			$g = $goods[$item['skuId']];
			// 扔掉无效ID
			if (!$g->exists()) {
				unset($items[$i]);
				continue;
			}
		}
		$cart->setItems($items);
		$cart->save();

		$qty = 0; $total = 0; $weight = 0;
		foreach($items as $i => $item) {

			$g = $goods[$item['skuId']];

			// 超出库存
			$item['qty'] = $item['qty']>$g['quantity'] ? $g['quantity'] : $item['qty'];

			// 优先取SKU图片
			$g['thumb'] = $g['thumb'] ? $g['thumb'] : $g['thumb1'];

			switch($item['priceType']) {
				case 1:
					$g['price_text'] = $g['point1'].'快乐积分';
					$g['final_credit_happy'] = $g['point1'];
					break;
				case 2:
					$g['price_text'] = $g['point2'].'免费积分';
					$g['final_credit'] = $g['point2'];
					break;
				case 3:
					$g['price_text'] = $g['exts']['ext1']['cash'].'元+'.$g['exts']['ext1']['point'].'免费积分';
					$g['final_credit'] = $g['exts']['ext1']['point'];
					$g['final_cash'] = $g['exts']['ext1']['cash'];
					break;
				case 4:
					$g['price_text'] = $g['exts']['ext2']['cash'].'元+'.$g['exts']['ext2']['point'].'积分币';
					$g['final_credit_coin'] = $g['exts']['ext2']['point'];
					$g['final_cash'] = $g['exts']['ext2']['cash'];
					break;
			}
			$g['final_price'] = $g['final_cash'];
			// 获取优惠价
			// if ($g['is_promotion']) {
			// 	if ($g['qty_limit']>0) { //有设置限购
			// 		$uid = M('User')->getCurUser()->id;
			// 		$ct = M('Order_Goods')->count('goods_id = ? AND buyer_id = ? AND final_price = ?', 
			// 			array($g['goods_id'], $uid, $g['promotion_price']));

			// 		if ($ct+$item['qty'] <= $g['qty_limit']) {
			// 			if ($g['activity_type'] == 'kill') {
			// 				$g['final_price'] = $g['kill_price'];
			// 			} else {
			// 				$g['final_price'] = $g['promotion_price'];
			// 			}
			// 		} else {
			// 			$g['final_price'] = $g['selling_price'];
			// 			$g['promotion_price'] = 0;
			// 			$g['is_promotion'] = 0;
			// 			$g['save_amount'] = 0;
			// 		}
			// 	} else {
			// 		if ($g['activity_type'] == 'kill') {
			// 			$g['final_price'] = $g['kill_price'];
			// 		} else {
			// 			$g['final_price'] = $g['promotion_price'];
			// 		}
			// 	}
			// } else {
			// 	$g['final_price'] = $g['selling_price'];
			// }


			$items[$i]['goods'] = $g->toArray();
			$items[$i]['qty'] = $item['qty'];
			$items[$i]['unit'] = $g['package_quantity'] ? $g['package_lot_unit'] : $g['package_unit'];
			//$items[$i]['subtotal_save'] = $item['qty'] * $g['save_amount'];
			$items[$i]['subtotal_credit'] = $item['qty'] * $g['final_credit'];
			$items[$i]['subtotal_credit_happy'] = $item['qty'] * $g['final_credit_happy'];
			$items[$i]['subtotal_credit_coin'] = $item['qty'] * $g['final_credit_coin'];
			//$items[$i]['subtotal_cash'] = $item['qty'] * $g['final_cash'];
			$items[$i]['subtotal_amount'] = $item['qty'] * $g['final_cash'];
			$items[$i]['subtotal_weight'] = $item['qty'] * $g['package_weight'];
			if ($g['earn_points'] == -1) {
				$ratio = M('Setting')->get('credit_expend');
				$items[$i]['subtotal_earn_points'] = $item['qty'] * ($g['final_price'] * $ratio);
			} else {
				$items[$i]['subtotal_earn_points'] = $item['qty'] * $g['earn_points'];
			}

			if ($item['checkout']) {
				$qty += $item['qty'];
				$save += $items[$i]['subtotal_save'];
				$total += $items[$i]['subtotal_amount'];
				$weight += $items[$i]['subtotal_weight'];
				$points += $items[$i]['subtotal_earn_points'];

				$credit += $items[$i]['subtotal_credit'];
				$credit_happy += $items[$i]['subtotal_credit_happy'];
				$credit_coin += $items[$i]['subtotal_credit_coin'];
				//$cash += $items[$i]['subtotal_cash'];
			}
		}

		$cart->setItems($items);
		$cart->setStatus('total_amount', $total)
			->setStatus('total_credit', $credit)
			->setStatus('total_credit_happy', $credit_happy)
			->setStatus('total_credit_coin', $credit_coin)
			//->setStatus('total_cash', $cash)
			->setStatus('total_save', $save)
			->setStatus('total_quantity', $qty)
			->setStatus('total_weight', $weight)
			->setStatus('total_pay_amount', $total-$save)
			->setStatus('total_earn_points', $points);

		return $cart;
	}
}