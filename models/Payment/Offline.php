<?php

class Payment_Offline extends Suco_Model implements Payment_Interface
{

	public function pay($amount, $params)
	{
		$payment = M('Payment')->select()
			->where('code = ?', 'offline')
			->fetchRow();

		echo '请汇款至以下帐户<br>';
		echo nl2br($payment['setting']);
	}

	public function callback()
	{

	}
}