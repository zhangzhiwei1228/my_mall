<?php

class Cart
{
	protected $_items = array();
	protected $_status = array();
	protected $_observer = array(
		'Cart_Observer_Goods',
		#'Cart_Observer_Package',
		'Cart_Observer_Shipping',
		'Cart_Observer_Coupon',
		'Cart_Observer_Activity',
	); //观察者

	/**
	 * 构造
	 */
	public function __construct()
	{
		$data = json_decode(Suco_Cookie::get(__CLASS__), true);
		$this->_items = (array)$data['items'];
		//$this->_status = (array)$data['status'];
		
		//读取会员购物车
		 $uid = M('User')->getCurUser()->id;
		 unset($this->_items);
		 if ($uid && !$this->_items) {
		 	$items = M('User_Cart')->select()
		 		->where('user_id = ?', $uid)
		 		->fetchRows();
		 	foreach ($items as $item) {
		 		$k = $item['goods_id'].'.'.$item['sku_id'].'.'.$item['price_type'];
		 		$this->_items[$k] = array(
		 			'id'=> $item['goods_id'],
		 			'qty'=> $item['qty'],
		 			'skuId'=> $item['sku_id'],
					'shipping_id' => $item['shipping_id'],
		 			'priceType' => $item['price_type'],
		 			'checkout' => $item['checkout'],
		 		);
		 	}
		 }
	}
	/**
	 * 根据移动端token设置购物车
	 */
	public function setAppCart($uid)
	{
		//读取会员购物车
		unset($this->_items);
		if ($uid && !$this->_items) {
			$items = M('User_Cart')->select()
				->where('user_id = ?', $uid)
				->fetchRows();
			foreach ($items as $item) {
				$k = $item['goods_id'].'.'.$item['sku_id'].'.'.$item['price_type'];
				$this->_items[$k] = array(
					'id'=> $item['goods_id'],
					'qty'=> $item['qty'],
					'skuId'=> $item['sku_id'],
					'shipping_id' => $item['shipping_id'],
					'priceType' => $item['price_type'],
					'checkout' => $item['checkout'],
				);
			}
		}
	}

	/**
	 * 添加观察者
	 */
	public function addObserver($class)
	{
		$this->_observer[$class] = $class;
		return $this;
	}

	/**
	 * 删除观察者
	 */
	public function delObserver($class)
	{
		unset($this->_observer[$class]);
		return $this;
	}

	/**
	 * 设置多个观察者
	 */
	public function setObservers($observer)
	{
		$this->_observer = $observer;
		return $this;
	}

	/**
	 * 返回多个观察者
	 */
	public function getObservers()
	{
		return $this->_observer;
	}

	/**
	 * 添加商品
	 * @param int $id 商品ID
	 * @param int $qty 数量
	 * @param mixed $opts 规格选项
	 * @param bool $reset 是否重置
	 * @return string
	 */
	public function addItem($id, $skuId = 0, $qty = 1, $priceType = 0, $checkout = 0, $reset = 0, $shipping_id = 0)
	{
		if (!$id) return;
		$code = $id.'.'.$skuId.'.'.$priceType;
		if (!isset($this->_items[$code]) || $reset) {
			$this->_items[$code] = array('id'=>$id, 'qty'=>$qty, 'skuId'=>$skuId, 'priceType'=>$priceType, 'checkout'=>$checkout, 'shipping_id'=>$shipping_id);
		} else { //追加商品
			$this->_items[$code]['qty'] += $qty;
		}
		$this->save();
		return $code;
	}

	/**
	 * 移除商品
	 * @param string $code 商品标签
	 */
	public function delItem($code,$user_id = false )
	{
		$cart_code = explode('.',$code);
		$uid = M('User')->getCurUser()->id;
		$uid = $uid ? $uid : $user_id;
		if($uid && isset($cart_code[0]) && isset($cart_code[1])) {
			M('User_Cart')->delete('user_id ='. $uid . ' and goods_id ='.$cart_code[0].' and sku_id ='.$cart_code[1].' and price_type = '.$cart_code[2]);
		}
		unset($this->_items[$code]);
		$this->save();
	}

	/**
	 * 设置购物车中的商品
	 * @return array
	 */
	public function setItems($items)
	{
		$this->_items = $items;
		return $this;
	}

	/**
	 * 返回购物车中的商品
	 * @return array
	 */
	public function getItems()
	{
		return $this->_items;
	}

	/**
	 * 返回商品种类数
	 * @return int
	 */
	public function getTotal()
	{
		return $this->_items ? count($this->_items) : 0;
	}

	/**
	 * 返回商品件数
	 * @return int
	 */
	public function getTotalQty()
	{
		$qty = 0;
		foreach($this->_items as $item) {
			$qty += $item['qty'];
		}
		return $qty;
	}

	/**
	 * 返回购物车中商品金额
	 * @return int
	 */
	public function getTotalAmount()
	{
		$amount = 0;
		foreach($this->_items as $item) {
			$amount += $item['subtotal'];
		}
		return $amount;
	}

	/**
	 * 设置购物车状态
	 */
	public function setStatus($k, $v)
	{
		$this->_status[$k] = $v;
		return $this;
	}

	/**
	 * 返回购物车状态
	 */
	public function getStatus($k)
	{
		return $this->_status[$k];
	}

	/**
	 * 设置购物车所有状态
	 */
	public function setAllStatus($status)
	{
		$this->_status = $status;
	}

	/**
	 * 返回购物车所有状态
	 */
	public function getAllStatus()
	{
		return $this->_status;
	}

	/**
	 * 销毁购物车
	 */
	public function destroy()
	{
		$this->_items = null;
		$this->_status = null;
		$this->save();
	}

	/**
	 * @param bool|false $uid
	 * 保存购物车
	 */
	public function save($uid = false,$checkout=0)
	{
		Suco_Cookie::set(__CLASS__, json_encode(array(
			'status' => $this->_status,
			'items' => $this->_items
		)), 3600*24*30);

		//保存会员购物车
		 //$uid = M('User')->getCurUser()->id;
		$uid = $uid ? $uid : M('User')->getCurUser()->id;
		$cart_id = 0;
		 if ($uid) {
		 	foreach($this->_items as $k => $item) {
				$items = M('User_Cart')->select()
					->where('user_id ='. $uid . ' and goods_id ='.$item['id'].' and sku_id ='.$item['skuId'].' and shipping_id ='.$item['shipping_id'].' and price_type='.$item['priceType'])
					->fetchRows()->toArray();
				if($items) {
					M('User_Cart')->update(array('qty'=>$item['qty']),'user_id ='. $uid . ' and goods_id ='.$item['id'].' and sku_id ='.$item['skuId'].' and shipping_id ='.$item['shipping_id'].' and price_type='.$item['priceType']);
				} else {
					$cart_id = M('User_Cart')->insert(array(
						'user_id' => $uid,
						'goods_id' => $item['id'],
						'sku_id' => $item['skuId'],
						'shipping_id' => $item['shipping_id'],
						'price_type' => $item['priceType'],
						'checkout' => $item['checkout'],
						'qty' => $item['qty'],
					));
				}

		 	}
		 	if($checkout) {
				return $cart_id ? $cart_id : $items['id'];
			}
		 }
	}

	/**
	 * @param $uid
	 * @param $id
	 * @param int $skuId
	 * @param int $qty
	 * @param int $priceType
	 * @param int $checkout
	 * @param int $reset
	 * @param int $shipping_id
	 * @return string|void
	 * 移动端加入购物车
	 */
	public function doAppAddItem($uid, $id, $skuId = 0, $qty = 1, $priceType = 0, $checkout = 0, $reset = 0, $shipping_id = 0) {
		if (!$id) return;
		$code = $id.'.'.$skuId.'.'.$priceType;
		if (!isset($this->_items[$code]) || $reset) {
			$this->_items[$code] = array('id'=>$id, 'qty'=>$qty, 'skuId'=>$skuId, 'priceType'=>$priceType, 'checkout'=>$checkout, 'shipping_id'=>$shipping_id);
		} else { //追加商品
			$this->_items[$code]['qty'] += $qty;
		}
		$cart_id = $this->save($uid,$checkout);
		return $checkout ? $cart_id : $code;
	}

	/**
	 * 开始结算
	 * @param mixed $codes 需结算的商品标签
	 * @return array
	 */
	public function checking($codes = 'all')
	{
		//只保留结算项目
		if ($codes != 'all') {
			$codes = is_array($codes) ? $codes : explode(',', $codes);

			foreach ($this->_items as $code => $item) {
				if (!in_array($code, $codes)) {
					unset($this->_items[$code]);
				} else {
					$this->_items[$code]['checkout'] = 1;
				}
			}
		}
		
		//将信息交给观察者处理
		foreach ((array)$this->_observer as $class) {
			M($class)->observer($this);
		}

		return $this;
	}
}