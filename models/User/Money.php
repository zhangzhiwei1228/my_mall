<?php

class User_Money extends Abstract_Model
{
	protected $_name = 'user_money';
	protected $_primary = 'id';
	protected $_referenceMap = array(
		'user' => array(
			'class' => 'User',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'user_id'
		)
	);

	public function getTypeText($code)
	{
		$arr = array(
			'recharge' => '充值',
			'withdraw' => '提现',
			'transfer' => '转账',
			'fee' => '手续费',
			'pay' => '支付',
			'refund' => '退款'
		);

		return $arr[$code];
	}
	
	public function commit($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new App_Exception('错误参数');	
		}
		if ($row->status == 0 || $row->status == 1) {
			if ($row->amount > 0) {
				$row->user->income += abs($row->amount);
			} else {
				if ($row->user->balance < abs($row->amount)) { 
					throw new App_Exception('操作失败!当前帐户余额不足.'); 
				}
				$row->user->expend += abs($row->amount);
			}
			$row->user->balance += $row->amount;
			$row->user->save();

			$row->balance = $row->user->balance;
		}
		$row->status = 2;
		$row->save();
	}
	
	public function rollback($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new Fund_Exception('错误参数');	
		}
		if ($row->status == 2) {
			if ($row->amount > 0) {
				if ($row->user->balance < abs($row->amount)) { 
					throw new App_Exception('操作失败!当前帐户余额不足.'); 
				}
				$row->user->income -= abs($row->amount);
			} else {
				$row->user->expend -= abs($row->amount);
			}
			$row->user->balance -= $row->amount;
			$row->user->save();

			$row->balance = $row->user->balance;
		}
		$row->status = 1;
		$row->save();
	}
	
	public function cancel($row)
	{
		if (!$row instanceof Suco_Object) {
			throw new Fund_Exception('错误参数');	
		}
		if ($row->status == 2) {
			if ($row->amount > 0) {
				if ($row->user->balance < abs($row->amount)) { 
					throw new App_Exception('操作失败!当前帐户余额不足.'); 
				}
				$row->user->income -= abs($row->amount);
			} else {
				$row->user->expend -= abs($row->amount);
			}
			$row->user->balance -= $row->amount;
			$row->user->save();

			$row->balance = $row->user->balance;
		}
		$row->status = 0;
		$row->save();
	}
}