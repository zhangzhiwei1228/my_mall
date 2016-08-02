<?php

class Usercp_OrderController extends Usercp_Controller_Action
{
	public function init()
	{
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('./list');
	}

	public function doList()
	{
		$select = M('Order')->alias('o')
			->leftJoin(M('User')->getTableName().' AS b', 'o.buyer_id = b.id')
			->leftJoin(M('User')->getTableName().' AS s', 'o.seller_id = s.id')
			->leftJoin(M('Payment')->getTableName().' AS p', 'o.payment_id = p.id')
			->leftJoin(M('Shipping')->getTableName().' AS d', 'o.shipping_id = d.id')
			->columns('o.*, b.username AS buyer_account, s.username AS seller_account, p.name AS payment_name, d.name AS shipping_name')
			->where('o.buyer_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(10, $this->_request->page);

		switch ($this->_request->t) {
			case 'awaiting_payment': $select->where('o.status = 1'); break;
			case 'shiped': $select->where('o.status = 2'); break;
			case 'pending_receipt': $select->where('o.status = 3'); break;
			case 'completed': $select->where('o.status = 4'); break;
			case 'closed': $select->where('o.status = 0'); break;
		}
		switch ($this->_request->sm) {
			case 'code':
				$this->_request->keyword && $select->where('o.code = ?', $this->_request->keyword);
				break;
			case 'consignee':
				$this->_request->keyword && $select->where('o.consignee LIKE ?', '%'.$this->_request->keyword.'%');
				break;
			case 'buyer_account':
				$this->_request->keyword && $select->where('b.username LIKE ?', '%'.$this->_request->keyword.'%');
				break;
		}
		if ($this->_request->begin_time) {
			$select->where('o.create_time >= ?', strtotime($this->_request->begin_time));
		}
		if ($this->_request->end_time) {
			$select->where('o.create_time <= ?', strtotime($this->_request->end_time) + (3600 * 24));
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
	$view->render('views/allorders.php');
	}

	public function doDetail()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $order;
		$view->render('views/shopping/order_detail.php');
	}

	public function doReceive()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		//赠送积分
		if ($order['total_earn_points']) {
			$order->buyer->credit($order['total_earn_points'], '消费'.$order['total_amount'].'元，领取积分红包'.$order['total_earn_points'].'点');
		}

		$order->is_receive = 1;
		$order->save();

		return $this->_notice(array(
			'title' => '领取成功',
			'links' => array(
				array('返回上一页', $_SERVER['HTTP_REFERER'])
			),
			'autoback' => array('自动返回上一页', $_SERVER['HTTP_REFERER']),
		), 'success');

	}

	public function doDelivery()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $order;
		$view->render('usercp/order/delivery.php');
	}

	public function doContract()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $order;
		$view->render('usercp/order/contract.php');
	}

	public function doConfirm()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '签收成功',
				'links' => array(
					array('我要评价', 'action=comment&id='.$this->_request->id),
					array('查看当前订单', 'action=detail&id='.$this->_request->id),
				)
			), 'success');
		}

		// if ($this->_request->isPost()) {
		// 	if (!$this->user->checkPayPass($_POST['paypass'])) {
		// 		throw new App_Exception('签收失败，支付密码不正确');
		// 	}
			$order->confirm(date(DATETIME_FORMAT)." - 买家确认签收\r\n");
			$this->redirect('&success=1');
		// }

		// $view = $this->_initView();
		// $view->data = $order;
		// $view->render('usercp/order/confirm.php');
	}
	public function doExpress() {
		$view = $this->_initView();
		$view->render('views/shopping/order_express.php');
	}

	public function doComment()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '评价成功',
				'message' => '感谢您为我们的服务做出评价！',
				'links' => array(
					array('返回用户中心', 'module=usercp'),
					array('查看评价', '&success='),
				)
			), 'success');
		}

		if ($this->_request->isPost()) {
			foreach($_POST['data'] as $skuId => $c) {
				M('Goods_Comment')->insert(array(
					'sku_id' => $skuId,
					'order_id' => $order->id,
					'buyer_id' => $this->user->id,
					'goods_id' => $c['goods_id'],
					'spec' => $c['spec'],
					'score' => $c['score'],
					'comment' => $c['comment'],
					'is_show' => 1
				));
			}
			$order->is_comment = 1;
			$order->save();
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->data = $order;
		$view->comments = M('Goods_Comment')->select()
			->where('order_id = ?', $order->id)
			->fetchOnKey('goods_id');
		$view->render('usercp/order/comment.php');
	}

	public function doCancel()
	{
		$order = M('Order')->getById((int)$this->_request->id);
		if (!$order->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		
		$order->cancel(date(DATETIME_FORMAT)." - 买家取消了订单\r\n");
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}