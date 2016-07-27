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
			->leftJoin(M('Order_Goods')->getTableName().' AS `og`', 'or.order_goods_id = og.id')
			->columns('or.*, og.thumb, og.title, og.goods_id, og.spec, u.username')
			->order('or.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('order/return/list.php');
	}

	public function doDetail()
	{
		if ($this->_request->opid) {
			$data = M('Order_Return')->select()
				->where('order_goods_id = ?', $this->_request->opid)
				->fetchRow();
		} else {
			$data = M('Order_Return')->getById((int)$this->_request->id);
		}
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
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
		M('Order_Return')->updateById(array('status' => 2), (int)$return->id);
		M('Order_Goods')->updateById(array('is_return' => 2), (int)$return->order_goods_id);

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

		M('Order_Return')->updateById(array('status' => 1), (int)$return->id);
		M('Order_Goods')->updateById(array('is_return' => 3), (int)$return->order_goods_id);

		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}