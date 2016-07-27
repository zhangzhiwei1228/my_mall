<?php

class Goods_Sku extends Abstract_Model
{
	protected $_name = 'goods_sku';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if (isset($data['exts']) && is_array($data['exts'])) {
			$data['exts'] = json_encode($data['exts']);
		}

		return parent::inputFilter($data);
	}

	public function outputFilter($data)
	{
		if (isset($data['exts'])) {
			$data['exts'] = json_decode($data['exts'], 1);
		}

		return parent::outputFilter($data);
	}

	/**
	 * 返回商品促销信息
	 * @return object Suco_Db_Table_Rowset
	 */
	public function hasonePromotion($row)
	{
		$promotion = M('Goods_Promotion')->select()
			->where('is_enabled AND goods_id = ?', $row['goods_id'])
			->where('(start_time = 0 OR start_time <= ?) AND (end_time = 0 OR end_time >= ?)', time())
			->fetchRow();

		if ($promotion->exists()) {
			$row->is_promotion = 1;
			$row->price_label = $promotion->price_label;
			if ($promotion->activity_type == 'kill') {
				$row->kill_price = $promotion->kill_price;
				$row->promotion_price = $row->kill_price;
				$row->qty_limit = $promotion->qty_limit;
			} elseif ($promotion->activity_type == 'discount') {
				$row->discount = $promotion->discount;
				$row->promotion_price = $row->selling_price * ($promotion->discount/10);
				$row->qty_limit = $promotion->qty_limit;
			}
		} elseif ($_SESSION['vip_discount']) {
			$row->is_promotion = 1;
			$row->price_label = 'VIP折扣';
			$row->discount = $_SESSION['vip_discount']*10;
			$row->promotion_price = $row->selling_price*$_SESSION['vip_discount'];
			$row->qty_limit = $promotion->qty_limit;
		}

		return $row;
	}

	/**
	 * 装载促销信息
	 * @return object Suco_Db_Table_Rowset
	 */

	public function hasmanyPromotions($rows)
	{
		$ids = $rows->getColumns('goods_id');
		$ids = $ids ? implode(',', $ids) : 0;

		$promotions = M('Goods_Promotion')->select()
			->where('is_enabled AND goods_id IN ('.$ids.')')
			->where('(start_time = 0 OR start_time <= ?) AND (end_time = 0 OR end_time >= ?)', time())
			->fetchOnKey('goods_id')
			->toArray();

		foreach($rows as $k => $row) {
			$id = $row['id'];
			if (isset($promotions[$id])) {
				$row->is_promotion = 1;
				$row->price_label = $promotions[$id]['price_label'];
				$row->activity_id = $promotions[$id]['activity_id'];
				$row->activity_type = $promotions[$id]['activity_type'];
				if ($row->activity_type == 'kill') {
					$row->kill_price = $promotions[$id]['kill_price'];
					$row->promotion_price = $row->kill_price;
					$row->qty_limit = $promotions[$id]['qty_limit'];
					$row->save_amount = round($row->selling_price - $row->promotion_price, 2);
				} elseif ($row->activity_type == 'discount') {
					$row->discount = $promotions[$id]['discount'];
					$row->promotion_price = $row->selling_price * ($promotions[$id]['discount']/10);
					$row->qty_limit = $promotions[$id]['qty_limit'];
					$row->save_amount = round($row->selling_price - $row->promotion_price, 2);
				}
				
				$rows->set($k, $row->toArray());
			} elseif ($_SESSION['vip_discount']) {
				$row->is_promotion = 1;
				$row->price_label = 'VIP折扣';
				$row->discount = $_SESSION['vip_discount']*10;
				$row->activity_id = 0;

				$row->promotion_price = $row->selling_price * $_SESSION['vip_discount'];
				$row->qty_limit = $promotions[$id]['qty_limit'];
				$row->save_amount = round($row->selling_price - $row->promotion_price, 2);

				$rows->set($k, $row->toArray());
			}
		}

		return $rows;
	}
}