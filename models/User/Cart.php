<?php

class User_Cart extends Abstract_Model
{
	protected $_name = 'user_cart';
	protected $_primary = 'id';
	/**
	 * 根据选择的支付返回
	 */
	public function price_type($cart) {
		$cart['exts'] = is_array($cart['exts']) ? $cart['exts'] : json_decode($cart['exts']);

		switch($cart['price_type']) {
			case 1 :
				$cart['price_text'][0]['name'] =  '快乐积分';
				$cart['price_text'][0]['value'] = $cart['point1'];
				//$cart['price_text'] = $cart['point1'] .'快乐积分';
				//$cart['final_credit_happy'] = $cart['point1'] ;
				break;
			case 2 :
				$cart['price_text'][0]['name'] =  '帮帮币';
				$cart['price_text'][0]['value'] = $cart['point2'];
				//$cart['price_text'] = $cart['point2'] .'帮帮币';
				//$cart['final_credit'] = $cart['point2'] ;
				break;
			case 3 :
				$cart['price_text'][0]['name'] =  '积分币';
				$cart['price_text'][0]['value'] = $cart['point3'];
				//$cart['price_text'] = $cart['point3'] .'积分币';
				//$cart['final_credit_coin'] = $cart['point3'] ;
				break;
			case 4 :
				$cart['price_text'][0]['name'] =  '现金';
				$cart['price_text'][1]['name'] =  '帮帮币';
				$cart['price_text'][0]['value'] = is_array($cart['exts']) ? $cart['exts']['ext1']['cash']  : $cart['exts']->ext1->cash;
				$cart['price_text'][1]['value'] = is_array($cart['exts']) ? $cart['exts']['ext1']['point']  : $cart['exts']->ext1->point ;
				//$cart['price_text'] = $cart['exts']->ext1->cash .'元+'.$cart['exts']->ext1->point.'帮帮币';;
				//$cart['final_credit'] = $cart['exts']->ext1->point;
				//$cart['final_cash'] = $cart['exts']->ext1->cash;
				break;
			case 5 :
				$cart['price_text'][0]['name'] =  '现金';
				$cart['price_text'][1]['name'] =  '积分币';
				$cart['price_text'][0]['value'] = is_array($cart['exts']) ? $cart['exts']['ext2']['cash']  : $cart['exts']->ext2->cash ;
				$cart['price_text'][1]['value'] = is_array($cart['exts']) ? $cart['exts']['ext2']['point']  : $cart['exts']->ext2->point ;
				//$cart['price_text'] = $cart['exts']->ext2->cash .'元+'.$cart['exts']->ext2->point.'积分币';;
				//$cart['final_credit'] = $cart['exts']->ext2->cash;
				//$cart['final_credit_coin'] = $cart['exts']->ext2->point;
				break;
			case 6 :
				$cart['price_text'][0]['name'] =  '抵用券';
				$cart['price_text'][0]['value'] = $cart['point4'];
				//$cart['price_text'] =  $cart['point4'].'抵用券';
				//$cart['final_vouchers'] = $cart['point4'];
				break;
			case 7 :
				$cart['price_text'][0]['name'] =  '现金';
				$cart['price_text'][0]['value'] = $cart['point5'];
				//$cart['price_text'] = $cart['point5'].'现金';
				//$cart['final_cash'] =  $cart['point5'];
				break;
			case 8 :
				$cart['price_text'][0]['name'] =  '现金';
				$cart['price_text'][1]['name'] =  '抵用券';
				$cart['price_text'][0]['value'] = is_array($cart['exts']) ? $cart['exts']['ext3']['cash']  : $cart['exts']->ext3->cash ;
				$cart['price_text'][1]['value'] = is_array($cart['exts']) ? $cart['exts']['ext3']['point']  : $cart['exts']->ext3->point;
				//$cart['price_text'] = $cart['exts']->ext3->cash.'元+'. $cart['exts']->ext3->point.'抵用券';
				//$cart['final_vouchers'] =  $cart['exts']->ext3->point;
				//$cart['final_cash'] =  $cart['exts']->ext3->cash;
				break;
		}
		return $cart;
	}
}