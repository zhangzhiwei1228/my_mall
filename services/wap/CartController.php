<?php

class CartController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$cart = M('Cart');

		$cart->setStatus('shipping_id', 0);
		$cart->checking();

		$view = $this->_initView();
		$view->items = $cart->getItems();
		$view->status = $cart->getAllStatus();
		$view->render('cart/index.php');
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
		$view->render('cart/checkout.php');
	}

	/*
	 * 开始下单
	 */
	public function doPlaceOrder()
	{
		//初化始购物车
		$cart = M('Cart');
		$cart->setStatus('freight_id', $_POST['freight_id']);
		$cart->checking();

		$items = $cart->getItems();
		$status = $cart->getAllStatus();
		if (!$cart->getTotalQty()) {
			throw new App_Exception('下单失败，您的购买车中没有需结算的商品');
		}

		M('Order')->getAdapter()->beginTrans();
		try {
			//减库存
			foreach ($items as $item) {
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
				M('Goods')->updateById('
					sales_num = sales_num + '.(int)$item['qty'].',
					trans_num = trans_num + 1,
					quantity =	quantity - '.(int)$item['qty']
				, (int)$item['goods']['id']);
			}

			$buyer = M('User')->getCurUser();

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
			if ($this->_request->use_balance 
				&& $this->user->balance >= $order->total_pay_amount) {

				if (!$this->user->checkPayPass($_POST['paypass'])) {
					throw new App_Exception("支付失败，交易密码不正确", 1);
				}

				$order->pay();
				$this->redirect('&');
			} else {
				$payment = M('Payment')->factory($_POST['payment']);
				$payment->pay($_POST['pay_amount'], http_build_query(array(
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

		$view = $this->_initView();
		$view->order = $order;
		$view->payments = M('Payment')->select()
			->where('is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();
		$view->render('cart/pay.php');
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
		$mark = M('Cart')->addItem(
			(int)$this->_request->goods_id,
			(int)$this->_request->sku_id,
			(int)$this->_request->purchase_quantity
		);

		echo json_encode(array(
			'mark' => $mark,
			'qty' => M('Cart')->getTotalQty()
		));
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
}