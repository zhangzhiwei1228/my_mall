<?php

class Admincp_OrderReturnController extends Admincp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_auth(__CLASS__);
	}

	public function doList()
	{
		$select = M('Order_Return')->alias('`or`')
			->leftJoin(M('User')->getTableName().' AS u', 'or.buyer_id = u.id')
			->leftJoin(M('Goods_Sku')->getTableName().' AS `gs`', 'or.sku_id = gs.id')
			->leftJoin(M('Goods')->getTableName().' AS `g`', 'or.order_goods_id = g.id')
			->leftJoin(M('Order')->getTableName().' AS `o`', 'or.order_id = o.id')
			->columns('or.*, g.thumb, g.title, gs.goods_id, gs.spec, u.username,o.code as oCode,o.total_freight')
			->order('or.id DESC')
			->paginator(20, $this->_request->page);
		if($this->_request->q) {
			$select->where('o.code ='.$this->_request->q);
		}
		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('order/return/list.php');
	}

	public function doDetail()
	{
		if ($this->_request->opid) {
			$goods = M('Order_Goods')->getById((int)$this->_request->opid)->toArray();
			$data = M('Order_Return')->select()
				->where('order_goods_id = ? and order_id = ?', array($goods['goods_id'],$goods['order_id']))
				->fetchRow();
		} else {
			$data = M('Order_Return')->getById((int)$this->_request->id);
			$goods = M('Order_Goods')->select()->where('order_id = ? and goods_id = ?',array($data['order_id'],$data['order_goods_id']))->fetchRow()->toArray();
		}
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}
		$good = M('Goods')->select('id,title,thumb')->where('id ='.$data['order_goods_id'])->fetchRow()->toArray();
		$sku = M('Goods_Sku')->getById((int)$goods['sku_id'])->toArray();
		$order = M('Order')->select('order_json,total_freight')->where('id = '.$data['order_id'])->fetchRow()->toArray();
		$orderJson = json_decode($order['order_json']);
		foreach($orderJson as $row){
			$row = get_object_vars($row);
			if($row['skus_id'] == $goods['sku_id']) {
				$good['qty'] = $row['qty'];
				$good['price_text'] = $row['price_text'];
			} else {
				continue;
			}
		}
		$view = $this->_initView();
		$view->data = $data;
		$view->good = $good;
		$view->sku = $sku;
		$view->order = $order;
		$view->render('order/return/detail.php');
	}

	public function doRefund()
	{
		$return = M('Order_Return')->getById((int)$this->_request->id);
		$order = M('Order')->getById((int)$return->order_id);

		//开始退款
		if ($return->status == 2) {
			if ($return['refund_amount']>0 && $return['is_return'] == 1) { //金额大于零且是退货单
				$order->buyer->income('refund', $return['refund_amount'], 'RF-'.$return['code'], '订单号：#TS-'.$order['code'])
					->commit();
			}
			$return->status = 3;
			$return->save();
		}

		$exts = array(
			'buyer_id' => $return['buyer_id'],
			'order_id' => $return['order_id'],
			'sku_id' => $return['sku_id'],
			'goods_id' => $return['order_goods_id'],
			'price_type' => $return['price_type'],
		);
		$query = get_sql($exts);
		M('Order_Goods')->update(array('is_return' => 3), $query);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doAccept()
	{
		$return = M('Order_Return')->getById((int)$this->_request->id);
		$order = M('Order')->getById((int)$return->order_id);

		$s = 0; $n = 0;
		foreach ($order->goods as $row) {
			if ($row['is_return'] == 1) { $s++; } //只要还有退款未处理，继续冻结
			elseif ($row['is_return'] == 2) { $n++; }
		}

		if ($order->goods->total() == $n + 1) { //整单退，关闭交易
			M('Order')->updateById(array('status' => 0),(int)$return->order_id);
		} elseif ($s - 1 == 0) { //已经没有退款，恢复订单
			M('Order')->updateById('expiry_time = retention_time + '.time(),(int)$return->order_id);
		}
		//M('Order_Return')->updateById(array('status' => 2), (int)$return->id);
		//M('Order_Goods')->updateById(array('is_return' => 2), (int)$return->order_goods_id);
		$exts = array(
			'buyer_id' => $return['buyer_id'],
			'order_id' => $return['order_id'],
			'sku_id' => $return['sku_id'],
			'goods_id' => $return['order_goods_id'],
			'price_type' => $return['price_type'],
		);
		$query = get_sql($exts);
		M('Order_Return')->updateById(array('status' => 2), (int)$return->id);
		M('Order_Goods')->update(array('is_return' => 2), $query);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doRefuse()
	{
		$return = M('Order_Return')->getById((int)$this->_request->id);
		$order = M('Order')->getById((int)$return->order_id);
		$s = 0; $n = 0;
		foreach ($order->goods as $row) {
			if ($row['is_return'] == 1) { $s++; } //只要还有退款未处理，继续冻结
			elseif ($row['is_return'] == 2) { $n++; }
		}

		if ($s - 1 == 0) { //已经没有退款，恢复订单
			M('Order')->updateById('expiry_time = retention_time + '.time(),(int)$return->order_id);
		}

		//M('Order_Return')->updateById(array('status' => 1), (int)$return->id);
		//M('Order_Goods')->updateById(array('is_return' => 3), (int)$return->order_goods_id);
		$exts = array(
			'buyer_id' => $return['buyer_id'],
			'order_id' => $return['order_id'],
			'sku_id' => $return['sku_id'],
			'goods_id' => $return['order_goods_id'],
			'price_type' => $return['price_type'],
		);
		$query = get_sql($exts);
		M('Order_Return')->updateById(array('status' => 1), (int)$return->id);
		M('Order_Goods')->update(array('is_return' => 3), $query);

		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}