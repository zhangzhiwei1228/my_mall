<?php

class CartController extends Controller_Action
{
	public function init()
	{
		parent::init();

	}

	public function doDefault()
	{
		unset($_SESSION['pay_confirm_login']);
		unset($_SESSION['pay_confirm_login_url']);
		$cart = M('Cart');

		$cart->setStatus('shipping_id', 0);
		$cart->checking();

		$view = $this->_initView();
		$view->items = $cart->getItems();
		$view->status = $cart->getAllStatus();
		$view->render('views/shopping.php');

		//print_r($view->items);
	}

	/*
	 * 弹出购物车
	 */
	public function doPop()
	{
		$cart = M('Cart');
		$cart->checking();

		$view = $this->_initView();
		$view->items = $cart->getItems();
		$view->status = $cart->getAllStatus();
		$view->render('cart/pop.php');
	}

	/*
	 * 购物车结算
	 */
	public function doCheckout()
	{
		$this->user = $this->_auth();

		$cart = M('Cart');
		if (!$cart->getTotalQty() && !$this->_request->isAjax()) {
			$this->redirect('action=default');
		}

		//找出结算项目
		foreach((array)$_POST['cart'] as $k => $item) {
			if ($item['checkout']) {
				$codes[] = $k;
			}
		}

		if (!$codes) {
			throw new App_Exception('请选择需要结算的商品');
		}


		$cart->checking($codes);

		$view = $this->_initView();
		$view->items = $cart->getItems();
		$view->status = $cart->getAllStatus();
		$view->addrs = M('User_Address')->select()->where('user_id = ?', $this->user['id'])->fetchRows();
		$view->render('views/choseweb.php');
	}

	/*
	 * 开始下单
	 */
	public function doPlaceOrder()
	{
		$buyer = M('User')->getCurUser();
		$shippings = array();
		//初化始购物车
		$cart = M('Cart');
		$cart->setStatus('freight_id', $_POST['freight_id']);
		$cart->checking();

		if (!$_POST['addr_id']) {
			M('User_Address')->insert(array_merge($_POST, array(
				'user_id' => $buyer['id']
			)));
		} else {
			$addr = M('User_Address')->getById((int)$_POST['addr_id']);
			$addrPost = $addr->toArray();
			unset($addrPost['id']);
			unset($addrPost['user_id']);
			unset($addrPost['create_time']);
			$_POST = array_merge($_POST, $addrPost);
		}

		$items = $cart->getItems();

		$status = $cart->getAllStatus();
		if (!$cart->getTotalQty()) {
			throw new App_Exception('下单失败，您的购买车中没有需结算的商品');
		}

		M('Order')->getAdapter()->beginTrans();
		try {
			//减库存
			foreach ($items as $key =>$item) {
				//处理规格库存
				if ($item['skuId']) {
					$sku = M('Goods_Sku')->select()
						->where('id = ?', (int)$item['skuId'])
						->forUpdate(1)
						->fetchRow();
					if ($sku['quantity'] < $item['qty'] || $item['qty'] == 0) {
						throw new Suco_Exception('很抱歉，商品 “'.$item['goods']['title'].'” 已经缺货。');
					}
					$sku->quantity -= $item['qty'];
					$sku->sales_num += $item['qty'];
					$sku->save();
				}
				if($item['shipping_id']) {
					$status['shipping_id'] = $item['shipping_id'];
				}
				M('Goods')->updateById('
					sales_num = sales_num + '.(int)$item['qty'].',
					trans_num = trans_num + 1,
					quantity =	quantity - '.(int)$item['qty']
				, (int)$item['goods']['id']);
				$shippings[$key] = $item['shipping_id'];
			}

			$oid = M('Order')->insert(array_merge($_POST, $status, array(
				'code' => time(),
				'buyer_id' => $buyer->id,
				'invoice_id' => (int)$invoiceId,
				'status' => 1,
				'is_virtual' => 0,
				'expiry_time' => time() + (int)M('Setting')->timeout_pay,
			)));
			foreach($items as $k => $row) {
				if (!$row['checkout']) continue;
				unset($row['goods']['id']);
				M('Order_Goods')->insert(array_merge($row['goods'], array(
					'order_id' => $oid,
					'buyer_id' => $buyer->id,
					'subtotal_amount' => $row['subtotal_amount'],
					'subtotal_weight' => $row['subtotal_weight'],
					'subtotal_save' => $row['subtotal_save'],
					'purchase_quantity' => $row['qty'],
					'promotion' => $row['goods']['price_label'],
					'unit' => $row['unit'],
					'sku_id' => $row['skuId']
				)));
				$cart->delItem($k);
			}

			//发票处理
			if ($_POST['invoice']['type_id']) {
				$invoiceId = M('Invoice')->insert(array_merge($_POST['invoice'], $_POST, array(
					'order_ids' => $oid,
					'invoice_amount' => $status['total_amount']
				)));
			}

			//销毁购物车
			//$cart->destroy();
			M('Order')->getAdapter()->commit();
			$this->redirect('action=pay&id='.$oid);
		} catch (Suco_Exception $e) {
			M('Order')->getAdapter()->rollback();
			return $this->_notice(array(
				'title' => '订单提交失败',
				'message' => $e->getMessage(),
				'links' => array(
					array('修改购物车', 'controller=cart'),
					array('返回首页', 'index')
				),
			), 'error');
		}
	}

	/*
	 * 订单支付
	 */
	public function doPay()
	{

		$this->user = $this->_auth();
		$order = M('Order')->getById((int)$this->_request->id);

		$order_json = json_decode($order['order_json']);
		$order_postage = 0;
		//将分好的商品的邮费计算出来
		foreach($order_json as $key =>$val) {
			if(strpos($val->skus_id,',')) {
				$sku_ids = explode(',',$val->skus_id);
				foreach($sku_ids as $sku_id) {
					$sku = M('Goods_Sku')->select()->where('id = ?', (int)$sku_id)->fetchRow();
					$good = M('Goods')->select()->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
					$val->goods[$sku_id] = $good;
				}

			} else {
				$sku = M('Goods_Sku')->select()->where('id = ?', (int)$val->skus_id)->fetchRow();
				$good = M('Goods')->select()->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
				$val->goods[$val->skus_id] = $good;
			}
			$val->order_postage = $this->doPostAge($order, $val->total, $val->weight);
			$order_postage += $this->doPostAge($order, $val->total, $val->weight);
		}
		$total_postage = $order['order_json'] ? $order_postage : $this->doPostAge($order);//计算邮费

		if ($order['status'] >= 2) {
			$url = H('url', 'module=usercp&controller=order&action=detail&id='.$order['id']);
			return $this->_notice(array(
				'title' => '支付成功！订单号：[TS-'.$order['code'].']',
				'message' => '感谢您的惠顾！我们将在24小时内安排发货。<br>'
					.'您可以<a href="'.$url.'">点击这里</a>可以查看当前订单的发货状态',
				'links' => array(
					array('查看其它待付订单', 'module=usercp&controller=order&action=list&t=awaiting_payment'),
					array('返回首页', 'index')
				),
			), 'success');
		}

		if ($this->_request->isPost()) {
			if ($this->_request->use_balance && $this->user->balance >= $order->total_pay_amount) {
				if (!$this->user->checkPayPass($_POST['paypass'])) {
					throw new App_Exception("支付失败，交易密码不正确", 1);
				}
				$order->pay();
				$this->redirect('&');
			} else {
				$order->total_pay_amount = $total_postage+$order->total_pay_amount;
				$order->total_amount = $total_postage+$order->total_amount;
				if($_POST['payment'] == 'wxpay') {
					$this->redirect('action=payjsapi&amount='.$order['total_amount'].'&params='.base64_encode(http_build_query(array('user_id' => $this->user->id, 'trade_no' => 'TS-'.$order->code, 'subject' => '支付订单', 'use_balance' => $_POST['use_balance'], 'bankcode' => $_POST['bankcode']))));
					return false;
				}
				$payment = M('Payment')->factory($_POST['payment']);
				$payment->pay($order['total_amount'], http_build_query(array(
					'user_id' => $this->user->id,
					'trade_no' => 'TS-'.$order->code,
					'subject' => '支付订单',
					'use_balance' => $_POST['use_balance'],
					'bankcode' => $_POST['bankcode']
				)));
				die;
			}
		}

		if ($order->buyer_id != M('User')->getCurUser()->id) {
			throw new App_Exception('ERROR');
		}

		try {
			if ($order['total_credit'] > 0 && $this->user['credit'] < $order['total_credit']) {
				throw new App_Exception("支付失败，您的免费积分不足", 101);
			}
			if ($order['total_credit_happy'] > 0 && $this->user['credit_happy'] < $order['total_credit_happy']) {
				throw new App_Exception("支付失败，您的快乐积分不足", 102);
			}
			if ($order['total_credit_coin'] > 0 && $this->user['credit_coin'] < $order['total_credit_coin']) {
				throw new App_Exception("支付失败，您的积分币不足", 103);
			}
		} catch(App_Exception $e) {
			$view = $this->_initView();
			$view->message = $e->getMessage();
			$view->code = $e->getCode();
			$view->render('views/shopping/no_enough.php');
			return;
		}

		if ($order['total_amount'] > 0 || $total_postage > 0) {
			$view = $this->_initView();
			$view->order = $order;
			$view->orders_json = $order_json;
			$view->total_postage = $total_postage;
			$view->payments = M('Payment')->select()
				->where('is_enabled = 1')
				->order('rank ASC, id ASC')
				->fetchRows();
			$view->render('views/shopping/paying.php');
		} else {
			if ($order['total_credit']) {
				$this->user->credit($order['total_credit']*-1, '支付订单【TS-'.$order['id'].'】');
			}
			if ($order['total_credit_happy']) {
				$this->user->credit($order['total_credit_happy']*-1, '支付订单【TS-'.$order['id'].'】');
			}
			if ($order['total_credit_coin']) {
				$this->user->credit($order['total_credit_coin']*-1, '支付订单【TS-'.$order['id'].'】');
			}
			$order->status = 2;
			$order->save();

			$this->redirect('&');
		}
	}

	/**
	 * jsapi页面
	 */
	public function doPayjsapi()
	{
		$amount = $this->_request->amount;
		$params = base64_decode($this->_request->params);

		$view = $this->_initView();
		$view->amount = $amount;
		$view->params = $params;
		$view->render('views/shopping/jsapi.php');
	}
	/**
	 * jsapi notify_url
	 */
	public function doWxnotify() {
		require_once LIB_DIR."Sdks/wxpay/lib/mb_notify.php";
	}
	/**
	 * jsapi 返回前台通知
	 */
	public function doDealbusiness() {
		$trade_no = $this->_request->trade_no;

		$setting = M('Setting');
		try {
			list($type, $code) = explode('-', trim($trade_no));
			$voucher = 'ALI-'.$trade_no;

			//滤重
			$recharge = M('User_Recharge')->select()
				->where('voucher = ? AND payment_id = ?', array($voucher, $this->_pid))
				->fetchRow();

			if ($recharge->exists()) {
				die('fail');
			}

			switch($type) {
				case 'TS': //支付订单
					if (!$code) {
						die('fail');
					}
					$order = M('Order')->getByCode($code);

					if ($order->exists() && $order->status == 1) {
						$order->buyer->recharge(
							$order['total_amount'], 0, $voucher, '微信支付', 2
						)->commit();
						$order->pay();
						die('success');
					}
					break;
				case 'RC': //帐户充值
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						die('success');
					}
					break;
				case 'RCA': //免费积分充值
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', $this->_pid
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '购买免费积分#'.$voucher
						)->commit();

						$point = $setting['credit_rate']*$user['amount'];
						$user->credit($point, '购买免费积分');
						die('success');
					}
					break;
				case 'RCB': //快乐积分充值
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '购买快乐积分#'.$voucher
						)->commit();

						$point = $setting['credit_happy_rate']*$user['amount'];
						$user->creditHappy($point, '购买快乐积分');
						die('success');
					}
					break;
				case 'RCC': //积分币充值
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '购买积分币#'.$voucher
						)->commit();

						$point = $setting['credit_coin_rate']*$user['amount'];
						$user->creditCoin($point, '购买积分币');
						die('success');
					}
					break;
				case 'VIP': //VIP激活
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, 'VIP激活'
						)->commit();
						$user->is_vip = 1;
						$user->save();
						die('success');
					}
					break;
				case 'VIP1': //VIP激活
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '升级一星分销商'
						)->commit();
						$user->is_vip = 2;
						$user->save();

						//赠送500免费积分
						$user->credit(500, '升级一星分销商，赠送免费积分');
						die('success');
					}
					break;
				case 'VIP2': //VIP激活
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '升级二星分销商'
						)->commit();
						$user->is_vip = 3;
						$user->save();

						//赠送500免费积分
						$user->credit(500, '升级二星分销商，赠送免费积分');
						die('success');
					}
					break;
				case 'VIP3': //VIP激活
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '升级三星分销商'
						)->commit();
						$user->is_vip = 4;
						$user->save();

						//赠送500免费积分
						$user->credit(500, '升级三星分销商，赠送免费积分');
						die('success');
					}
					break;
				case 'VIP4': //VIP激活
					$user = M('User')->getById($code);

					if ($user->exists()) {
						$user->recharge(
							$user['amount'], 0, $voucher, '微信充值', 2
						)->commit();
						$user->expend(
							'pay', $user['amount'], $voucher, '升级四星分销商'
						)->commit();
						$user->is_vip = 5;
						$user->save();

						//赠送500免费积分
						$user->credit(500, '升级四星分销商，赠送免费积分');
						die('success');
					}
					break;
			}
		} catch(Suco_Exception $e) {
			die('fail');
		}
	}
	/*
	 * 载入用户地址库
	 */
	public function doLoadDelivery()
	{
		$view = $this->_initView();
		$view->datalist = M('User_Address')->select()
			->where('user_id = ?', M('User')->getCurUser()->id)
			->order('id DESC')
			->fetchRows();
		$view->selected = $_SESSION['addr_id'];
		$view->render('cart/delivery.php');
	}

	/*
	 * 载入可用物流方式
	 */
	public function doLoadShipping()
	{
		$cart = M('Cart');
		if ($this->_request->getFreight == 1) {
			$cart->setStatus('freight_id', $_REQUEST['freight_id'])
				->checking();
			echo json_encode($cart->getAllStatus());
			return;
		}

		if (!$_REQUEST['area_id']) {
			die('<div class="notfound">请选择或填写收货地址</div>');
		}

		$_SESSION['addr_id'] = $_REQUEST['addr_id'];
		$reg = M('Region')->getById((int)$_REQUEST['area_id']);
		foreach($reg->getPath() as $row) {
			$cond[] = 'FIND_IN_SET(\''.$row['id'].'\', sf.destination)';
		}

		$cond = implode(' OR ', $cond);

		$view = $this->_initView();
		$view->datalist = M('Shipping_Freight')->alias('sf')
			->leftJoin(M('Shipping')->getTableName().' AS s', 'sf.shipping_id = s.id')
			->columns('sf.*, s.name, s.logo, s.description')
			->where('s.is_enabled AND (sf.destination = \'\' OR '.$cond.')')
			->group('s.id')
			->fetchRows();
		$view->render('cart/shipping.php');
	}

	/*
	 * 检查优惠券
	 */
	public function doUseCoupon()
	{
		if (!$this->_request->code) {
			unset($_SESSION['coupon_code']);
			return;
		}

		$user = M('User')->getCurUser();

		$coupon = M('Coupon_Receive')->select()
			->where('code = ?', $this->_request->code)
			->fetchRow();

		if (!$coupon->exists()) {
			$ret = array('error' => 1, 'message' => '您输入的不是一个有效的优惠券代码');
		} elseif ($coupon['is_used'] == 1) {
			$ret = array('error' => 1, 'message' => '此优惠券已经被使用');
		} elseif ($coupon['user_id'] != 0 && $coupon['user_id'] != $user['id']) {
			$ret = array('error' => 1, 'message' => '此优惠券已经被其它人抢先领取');
		} else {
			$ret = array('error' => 0);
			
			if ($user->exists()) { //自动领取优惠券
				$coupon->user_id = $user['id'];
				$coupon->save();
			}

			$_SESSION['coupon_code'] = $this->_request->code;
		}

		echo json_encode($ret);
		die;
	}

	/*
	 * 添加到购物车
	 */
	public function doAdd()
	{
		$this->user = $this->_auth();
		if (!$this->_request->price_type) {
			throw new App_Exception('请选择购买方式');
		}
		if(!$this->user['is_vip']) {
			throw new App_Exception('您还没有激活，请先激活会员');
		}

		if ($this->_request->buynow) {
			$mark = M('Cart')->addItem(
				(int)$this->_request->goods_id,
				(int)$this->_request->sku_id,
				(int)$this->_request->quantity,
				(int)$this->_request->price_type,
				1,1,(int)$this->_request->shipping_id
			);
			$url = H('Url', 'action=checkout');
			echo '<form name="checkout" method="post" action="'.$url.'" style="display:none">';
			echo '<input name="cart['.$mark.'][id]" value="'.$this->_request->goods_id.'">';
			echo '<input name="cart['.$mark.'][skuId]" value="'.$this->_request->sku_id.'">';
			echo '<input name="cart['.$mark.'][priceType]" value="'.$this->_request->price_type.'">';
			echo '<input name="cart['.$mark.'][qty]" value="'.$this->_request->quantity.'">';
			echo '<input name="cart['.$mark.'][checkout]" value="1">';
			echo '<input name="cart['.$mark.'][shipping_id]" value="'.$this->_request->shipping_id.'">';
			echo '</form>';
			echo '<script>document.checkout.submit();</script>';
		} else {
			$mark = M('Cart')->addItem(
				(int)$this->_request->goods_id,
				(int)$this->_request->sku_id,
				(int)$this->_request->quantity,
				(int)$this->_request->price_type
				,0,1,(int)$this->_request->shipping_id
			);
			$this->redirect('action=default');		
		}
		

		// echo json_encode(array(
		// 	'mark' => $mark,
		// 	'qty' => M('Cart')->getTotalQty()
		// ));
	}

	/*
	 * 删除购物车物品
	 */
	public function doDelete()
	{
		M('Cart')->delItem($this->_request->code);
	}

	/*
	 * 更新购物车
	 */
	public function doUpdate()
	{
		M('Cart')->setItems($_POST['cart'])
			->save();
	}

	/**
	 * @param $order
	 * @param int $total
	 * @param int $weight
	 * @return float
	 */
	public function doPostAge($order, $total=0, $weight=0) {
		//计算邮费
		$total_weight = $total ? $total : round($order['total_weight'],2);
		$total_quantity = $weight ? $weight : round($order['total_quantity'],2);
		$user_adder_area_id = (int)$order['area_id'];
		$shipping_id = (int)$order['shipping_id'];
		$region = M('Region')->getById($user_adder_area_id);
		//$region_parent_id = (int)$region['parent_id'];

		$region_path_ids = $region['path_ids'];
		$region_path_ids = explode(',',$region_path_ids);
		$region_province = $region_path_ids[2];//获取省
		$region_city = $region_path_ids[3];//获取市
		//在存运费的时候，如果存的是全省中的，那destination的值是省id，如果是某些市，那会是市id
		$shipping_freight = M('Shipping_Freight')->select()
			->where('shipping_id = '. $shipping_id.' and (destination like '.'"%'.$region_province.'%" or destination like '.'"%'.$region_city.'%")')->fetchRow();

		$first_weight = (int)$shipping_freight['first_weight'];//首重
		$first_freight = round($shipping_freight['first_freight'],2);//一千克首重价格
		$second_weight = (int)$shipping_freight['second_weight'];//继重
		$second_freight = round($shipping_freight['second_freight'],2);//一千克继重价格
//		$one_weight = round($total_weight/$total_quantity,3);
		$one_weight = ceil($total_weight/$total_quantity);//向上取正

		if($one_weight > $first_weight) {
			$total_postage = $first_weight*$first_freight+($one_weight-$first_weight)*$second_weight*$second_freight;
		} else {
			$total_postage = $first_weight*$first_freight;
		}
		return round($total_quantity*$total_postage,2);
	}
}