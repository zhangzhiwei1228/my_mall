<?php

class User_Withdraw extends Abstract_Model
{
	protected $_name = 'user_withdraw';
	protected $_primary = 'id';
	protected $_referenceMap = array(
		'user' => array(
			'class' => 'User',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'user_id'
		)
	);

	public function commit($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new Fund_Exception('错误参数');	
		}
		if ($row->status == 0 || $row->status == 1) {
			//解冻资金
			$row->user->unusable(($row->amount + $row->fee) * -1);
		}

		$logs = M('User_Money')->select()
			->where('voucher = ?', 'WD-'.$row->id)
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
			throw new Fund_Exception('错误参数');	
		}

		$logs = M('User_Money')->select()
			->where('voucher = ?', 'WD-'.$row->id)
			->order('id DESC')
			->fetchRows();
		foreach($logs as $log) {
			$log->rollback();
		}

		if ($row->status == 2 || $row->status == 0) {
			//重新解冻资金
			$row->user->unusable(($row->amount + $row->fee));
		}
		$row->status = 1;
		$row->save();
		
		return $row;
	}
	
	public function cancel($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new Fund_Exception('错误参数');	
		}

		$logs = M('User_Money')->select()
			->where('voucher = ?', 'WD-'.$row->id)
			->order('id DESC')
			->fetchRows();
		foreach($logs as $log) {
			$log->cancel();
		}

		if ($row->status == 1) {
			//解冻资金
			$row->user->unusable(($row->amount + $row->fee) * -1);
		}

		$row->status = 0;
		$row->save();
		
		return $row;
	}
	
	public function deleteById($id)
	{
		$data = $this->getById((int)$id);
		if (!$data->exists()) {
			throw new Fund_Exception('记录不存在');
		}
		if ($data->status == 2) {
			throw new Fund_Exception('已入账信息不可删除');	
		}
		return parent::deleteById($id);
	}
}