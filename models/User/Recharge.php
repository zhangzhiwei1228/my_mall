<?php

class User_Recharge extends Abstract_Model
{
	protected $_name = 'user_recharge';
	protected $_primary = 'id';
	protected $_referenceMap = array(
		'user' => array(
			'class' => 'User',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'user_id'
		),
		'payment' => array(
			'class' => 'Payment',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'payment_id'
		)
	);

	public function commit($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new App_Exception('错误参数');	
		}
		
		$logs = M('User_Money')->select()
			->where('voucher = ?', 'RC-'.$row->id)
			->order('id ASC')
			->fetchRows();
		foreach($logs as $log) {
			$log->commit();
		}

		$row->status = 2;
		$row->save();
		
		return $row;
	}
	
	public function rollback($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new App_Exception('错误参数');	
		}

		$logs = M('User_Money')->select()
			->where('voucher = ?', 'RC-'.$row->id)
			->order('id DESC')
			->fetchRows();
		foreach($logs as $log) {
			$log->rollback();
		}

		$row->status = 1;
		$row->save();
		
		return $row;
	}
	
	public function cancel($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new App_Exception('错误参数');	
		}

		$logs = M('User_Money')->select()
			->where('voucher = ?', 'RC-'.$row->id)
			->order('id DESC')
			->fetchRows();
		foreach($logs as $log) {
			$log->cancel();
		}

		$row->status = 0;
		$row->save();
		
		return $row;
	}
	
	public function deleteById($id)
	{
		$data = $this->getById((int)$id);
		if (!$data->exists()) {
			throw new App_Exception('记录不存在');
		}
		if ($data->status == 2) {
			throw new App_Exception('已入账信息不可删除');	
		}
		return parent::deleteById($id);
	}
}