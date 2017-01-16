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
		foreach($items as $key => $val) {
			$shippings[$key] = $val['shipping_id'];
		}

		$shippings = $this->getFuseShipping($shippings);

		$fuseGoods = $this->getFuseGoods($shippings, $items, $goods);
		$qty = 0; $total = 0; $weight = 0;
		foreach($items as $i => $item) {

			$g = $goods[$item['skuId']];

			$sku = M('Goods_Sku')->select('*')->where('goods_id = '.(int)$g['goods_id'])->fetchRow()->toArray();

			// 超出库存
			$item['qty'] = $item['qty']>$g['quantity'] ? $g['quantity'] : $item['qty'];

			// 优先取SKU图片
			$g['thumb'] = $g['thumb'] ? $g['thumb'] : $g['thumb1'];

			switch($item['priceType']) {
				case 1:
					$g['price_text'] = ($g['point1']?$g['point1'] : $sku['point1']).'快乐积分';
					$g['final_credit_happy'] = $g['point1']?$g['point1'] : $sku['point1'];
					break;
				case 2:
					$g['price_text'] = ($g['point2']?$g['point2'] : $sku['point2']).'帮帮币';
					$g['final_credit'] = $g['point2']?$g['point2'] : $sku['point2'];
					break;
				case 3:
					$g['price_text'] = ($g['point3']?$g['point3'] : $sku['point3']).'积分币';
					$g['final_credit_coin'] = $g['point3']?$g['point3'] : $sku['point3'];
					break;
				case 4:
					$g['price_text'] = ($g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash']).'元+'.($g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point']).'帮帮币';
					$g['final_credit'] = $g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point'];
					$g['final_cash'] = $g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash'];
					break;
				case 5:
					$g['price_text'] = ($g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash']).'元+'. ($g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point']).'积分币';
					$g['final_credit_coin'] =  $g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point'];
					$g['final_cash'] = $g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash'];
					break;
				case 6:
					$g['price_text'] = ($g['point4']?$g['point4'] : $sku['point4']).'抵用券';
					$g['final_vouchers'] = $g['point4']?$g['point4'] : $sku['point4'];
					break;
				case 7:
					$g['price_text'] = ($g['point5']?$g['point5'] : $sku['point5']).'现金';
					$g['final_cash'] = $g['point5']?$g['point5'] : $sku['point5'];
					break;
				case 8:
					$g['price_text'] = ($g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash']).'元+'. ($g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point']).'抵用券';
					$g['final_vouchers'] =  $g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point'];
					$g['final_cash'] = $g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash'];
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
			$items[$i]['subtotal_vouchers'] = $item['qty'] * $g['final_vouchers'];//抵用券
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
				$vouchers += $items[$i]['subtotal_vouchers'];//抵用券

				$credit += $items[$i]['subtotal_credit'];
				$credit_happy += $items[$i]['subtotal_credit_happy'];
				$credit_coin += $items[$i]['subtotal_credit_coin'];
				//$cash += $items[$i]['subtotal_cash'];
			}
		}

		$cart->setItems($items);
		$cart->setStatus('total_amount', round($total,2))
			->setStatus('total_credit', $credit)
			->setStatus('total_credit_happy', $credit_happy)
			->setStatus('total_credit_coin', $credit_coin)
			->setStatus('total_vouchers', $vouchers)//抵用券
			//->setStatus('total_cash', $cash)
			->setStatus('total_save', $save)
			->setStatus('total_quantity', $qty)
			->setStatus('total_weight', $weight)
			->setStatus('total_pay_amount', round($total-$save,2))
			->setStatus('order_json', $fuseGoods)
			->setStatus('total_earn_points', $points);

		return $cart;
	}

	/**
	 * @param $shippings array
	 * @return mixed array
	 * 将所选商品的 shipping_id 重新分类（同一发货点在一个数组中）
	 */
	public function getFuseShipping($shippings) {
		if(count($shippings) > 1 ) {
			// 获取去掉重复数据的数组
			$uniques = array_unique ( $shippings );
			// 获取重复数据的数组
			$repeats = array_diff_assoc ( $shippings, $uniques );
			$i = 0;
			foreach($uniques as $key =>$repeat) {
				if(in_array($repeat,$repeats)) {
					$shipping[$i][$key] = $repeat;
					foreach($repeats as $ke => $val) {
						if($repeat == $val) {
							$shipping[$i][$ke] = $repeat;
						}
					}
				} else {
					//$arr[$i] = array_diff($uniques,$repeats);
					$shipping[$key] = $repeat;
				}
				$i++;
			}
		}
		return $shipping ? $shipping : $shippings;
	}

	/**
	 * @param $shippings
	 * @param $items
	 * @param $goods
	 * @return string
	 * 将同一发货点的商品打包
	 */
	public function getFuseGoods($shippings,$items,$goods) {
		$keys = 0;
		$ids = array();
		if($shippings) {
			foreach($shippings as $i =>$shipping) {
				if(is_array($shipping)) {
					foreach($shipping as $key => $val) {
						$item = $items[$key];

						$g = $goods[$item['skuId']];
						$sku = M('Goods_Sku')->select('*')->where('goods_id = '.(int)$g['goods_id'])->fetchRow()->toArray();
						$qty = $item['qty'] > $g['quantity'] ? $g['quantity'] : $item['qty'];
						$g['thumb'] = $g['thumb'] ? $g['thumb'] : $g['thumb1'];

						switch($item['priceType']) {
							case 1:
								$g['price_text'] = ($g['point1']?$g['point1'] : $sku['point1']).'快乐积分';
								$g['final_credit_happy'] = $g['point1']?$g['point1'] : $sku['point1'];
								break;
							case 2:
								$g['price_text'] = ($g['point2']?$g['point2'] : $sku['point2']).'帮帮币';
								$g['final_credit'] = $g['point2']?$g['point2'] : $sku['point2'];
								break;
							case 3:
								$g['price_text'] = ($g['point3']?$g['point3'] : $sku['point3']).'积分币';
								$g['final_credit_coin'] = $g['point3']?$g['point3'] : $sku['point3'];
								break;
							case 4:
								$g['price_text'] = ($g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash']).'元+'.($g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point']).'帮帮币';
								$g['final_credit'] = $g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point'];
								$g['final_cash'] = $g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash'];
								break;
							case 5:
								$g['price_text'] = ($g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash']).'元+'. ($g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point']).'积分币';
								$g['final_credit_coin'] =  $g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point'];
								$g['final_cash'] = $g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash'];
								break;
							case 6:
								$g['price_text'] = ($g['point4']?$g['point4'] : $sku['point4']).'抵用券';
								$g['final_vouchers'] = $g['point4']?$g['point4'] : $sku['point4'];
								break;
							case 7:
								$g['price_text'] = ($g['point5']?$g['point5'] : $sku['point5']).'现金';
								$g['final_cash'] = $g['point5']?$g['point5'] : $sku['point5'];
								break;
							case 8:
								$g['price_text'] = ($g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash']).'元+'. ($g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point']).'抵用券';
								$g['final_vouchers'] =  $g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point'];
								$g['final_cash'] = $g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash'];
								break;
						}
						$g['final_price'] = $g['final_cash'];
						$subtotal_cash = $qty * $g['final_cash'];
						$subtotal_credit = $qty * $g['final_credit'];
						$subtotal_credit_happy = $qty * $g['final_credit_happy'];
						$subtotal_credit_coin = $qty * $g['final_credit_coin'];
						$subtotal_weight = $qty * $g['package_weight'];
						$subtotal_vouchers = $qty * $g['final_vouchers'];//抵用券
						if ($g['earn_points'] == -1) {
							$ratio = M('Setting')->get('credit_expend');
							$subtotal_earn_points= $qty * ($g['final_price'] * $ratio);
						} else {
							$subtotal_earn_points = $qty * $g['earn_points'];
						}

						if ($item['checkout']) {
							$ids[$keys]['total'] += $qty;
							$ids[$keys]['shipping_id'] = $val;
							$ids[$keys]['weight'] += $subtotal_weight;
							$ids[$keys]['thumb'] = $g['thumb'];
							$ids[$keys]['points'] += $subtotal_earn_points;
							$ids[$keys]['subtotal_credit'] += $subtotal_credit;
							$ids[$keys]['subtotal_credit_happy'] += $subtotal_credit_happy;
							$ids[$keys]['subtotal_credit_coin'] += $subtotal_credit_coin;
							$ids[$keys]['subtotal_vouchers'] += $subtotal_vouchers;//抵用券
							$ids[$keys]['subtotal_cash'] += $subtotal_cash;//现金

						}
						$key1 = explode('.',$key);
						$shipping[$key1[1].'.'.$key1[2]] = $val;
						$price_text[$key1[1].'.'.$key1[2]] = $g['price_text'];
						$price_type[$key1[1].'.'.$key1[2]] = $item['priceType'];
						$good_qty[$key1[1].'.'.$key1[2]] = $qty;
						unset($shipping[$key]);
					}
					$ids[$keys]['skus_id'] = implode(',',array_keys($shipping));
					$ids[$keys]['price_text'] = $price_text;
					$ids[$keys]['price_type'] = $price_type;
					$ids[$keys]['qty'] = $good_qty;
				} else {
					$param = explode('.',$i);
					$ids[$keys]['skus_id'] = $param[1];
					$item = $items[$i];
					$g = $goods[$item['skuId']];
					$sku = M('Goods_Sku')->select('*')->where('goods_id = '.(int)$g['goods_id'])->fetchRow()->toArray();
					$qty = $item['qty'] > $g['quantity'] ? $g['quantity'] : $item['qty'];
					$g['thumb'] = $g['thumb'] ? $g['thumb'] : $g['thumb1'];
					switch($item['priceType']) {
						case 1:
							$g['price_text'] = ($g['point1']?$g['point1'] : $sku['point1']).'快乐积分';
							$g['final_credit_happy'] = $g['point1']?$g['point1'] : $sku['point1'];
							break;
						case 2:
							$g['price_text'] = ($g['point2']?$g['point2'] : $sku['point2']).'帮帮币';
							$g['final_credit'] = $g['point2']?$g['point2'] : $sku['point2'];
							break;
						case 3:
							$g['price_text'] = ($g['point3']?$g['point3'] : $sku['point3']).'积分币';
							$g['final_credit_coin'] = $g['point3']?$g['point3'] : $sku['point3'];
							break;
						case 4:
							$g['price_text'] = ($g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash']).'元+'.($g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point']).'帮帮币';
							$g['final_credit'] = $g['exts']['ext1']['point']?$g['exts']['ext1']['point'] : $sku['exts']['ext1']['point'];
							$g['final_cash'] = $g['exts']['ext1']['cash']?$g['exts']['ext1']['cash'] : $sku['exts']['ext1']['cash'];
							break;
						case 5:
							$g['price_text'] = ($g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash']).'元+'. ($g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point']).'积分币';
							$g['final_credit_coin'] =  $g['exts']['ext2']['point']?$g['exts']['ext2']['point'] : $sku['exts']['ext2']['point'];
							$g['final_cash'] = $g['exts']['ext2']['cash']?$g['exts']['ext2']['cash'] : $sku['exts']['ext2']['cash'];
							break;
						case 6:
							$g['price_text'] = ($g['point4']?$g['point4'] : $sku['point4']).'抵用券';
							$g['final_vouchers'] = $g['point4']?$g['point4'] : $sku['point4'];
							break;
						case 7:
							$g['price_text'] = ($g['point5']?$g['point5'] : $sku['point5']).'现金';
							$g['final_cash'] = $g['point5']?$g['point5'] : $sku['point5'];
							break;
						case 8:
							$g['price_text'] = ($g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash']).'元+'. ($g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point']).'抵用券';
							$g['final_vouchers'] =  $g['exts']['ext3']['point']?$g['exts']['ext3']['point'] : $sku['exts']['ext3']['point'];
							$g['final_cash'] = $g['exts']['ext3']['cash']?$g['exts']['ext3']['cash'] : $sku['exts']['ext3']['cash'];
							break;
					}
					$g['final_price'] = $g['final_cash'];
					$subtotal_cash = $qty * $g['final_cash'];
					$subtotal_credit = $qty * $g['final_credit'];
					$subtotal_credit_happy = $qty * $g['final_credit_happy'];
					$subtotal_credit_coin = $qty * $g['final_credit_coin'];
					$subtotal_weight = $qty * $g['package_weight'];
					$subtotal_vouchers = $qty * $g['final_vouchers'];//抵用券
					if ($g['earn_points'] == -1) {
						$ratio = M('Setting')->get('credit_expend');
						$subtotal_earn_points= $qty * ($g['final_price'] * $ratio);
					} else {
						$subtotal_earn_points = $qty * $g['earn_points'];
					}
					if ($item['checkout']) {
						$ids[$keys]['total'] += $qty;
						$ids[$keys]['shipping_id'] = $shipping;
						$ids[$keys]['weight'] += $subtotal_weight;
						$ids[$keys]['thumb'] = $g['thumb'];
						$ids[$keys]['points'] += $subtotal_earn_points;
						$ids[$keys]['subtotal_credit'] += $subtotal_credit;
						$ids[$keys]['subtotal_credit_happy'] += $subtotal_credit_happy;
						$ids[$keys]['subtotal_credit_coin'] += $subtotal_credit_coin;
						$ids[$keys]['subtotal_vouchers'] += $subtotal_vouchers;//抵用券
						$ids[$keys]['subtotal_cash'] += $subtotal_cash;//现金
						$ids[$keys]['price_text'] = $g['price_text'];
						$ids[$keys]['price_type'] = $item['priceType'];
						$ids[$keys]['qty'] = $qty;
					}
				}
				$keys++;
			}
		}

		return json_encode($ids);
	}
}