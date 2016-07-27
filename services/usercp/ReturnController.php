<?php

class Usercp_ReturnController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$select = M('Order_Return')->alias('r')
			->leftJoin(M('Order_Goods')->getTableName().' AS p', 'r.order_goods_id = p.id')
			->columns('r.*, p.title, p.thumb')
			->where('r.buyer_id = ?', $this->user['id'])
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('usercp/return/list.php');
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
		$view->goods = $data->goods;
		$view->render('usercp/return/detail.php');
	}

	public function doAdd()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '已成功提交退款申请！',
				'links' => array(
					array('查看退换货办理', '&action=list')
				),
			), 'success');
		}

		$goods = M('Order_Goods')->getById((int)$this->_request->opid);
		if (!$goods->exists()) {
			throw new App_Exception('未找到退货商品');
		}
		if ($goods['is_return']) {
			throw new App_Exception('此申请已提交,请勿重复操作');
		}

		if ($this->_request->isPost()) {
			M('Order_Return')->insert(array_merge($this->_request->getPosts(), array(
				'code' => M('Order_Return')->getUniqueCode(),
				'buyer_id' => $this->user['id'],
				'order_id' => $goods['order_id'],
				'order_goods_id' => $goods['id'],
				'is_buyer_accepted' => 1,
				'consult_count' => 1,
				'refund_amount' => $goods['subtotal_amount']-$goods['subtotal_save'],
				'expiry_time' => time() + M('Setting')->get('timeout_refund')
			)));
			M('Order_Goods')->updateById('is_return = 1', (int)$goods['id']); //变更订单商品状态
			M('Order')->updateById('retention_time = expiry_time-'.time().', expiry_time = 0', (int)$goods['order_id']); //冻结订单
			$this->redirect('&success=1');
		}

		$view = $this->_initView();
		$view->goods = $goods;
		$view->render('usercp/return/input.php');
	}
}