<?php

class User_Credit extends Abstract_Model
{
	protected $_name = 'user_credit';
	protected $_primary = 'id';

	public function increase($uid, $credit, $note)
	{
		if ($credit <= 0) { return; }

		$this->insert(array(
			'uid' => $uid,
			'credit' => abs($credit),
			'note' => $note,
		));
	}

	public function decrease($uid, $credit, $note)
	{
		if ($credit <= 0) {
			throw new App_Exception('无效的积分数值');
		}
		$balance = $this->getBalance($uid);
		if ($balance - abs($credit) < 0) {
			throw new App_Exception('积分不足');
		}

		$this->insert(array(
			'uid' => $uid,
			'point' => abs($credit) * -1,
			'note' => $note,
		));
	}

	public function getBalance($uid)
	{
		return (int)$this->select('SUM(point) as result')
			->where('uid = ?', $uid)
			->fetchCol('result');
	}
}