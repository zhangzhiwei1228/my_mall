<?php

class Usercp_CreditController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$view = $this->_initView();
		$view->logs = M('User_Credit')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page)
			->fetchRows();

		$view->render('views/payrecord.php');
	}
	public function doConversion() {
		$credit = $this->_request->credit;
		if($credit > $this->user['credit']){
			throw new App_Exception("输入的数字大于您所拥有的", 1001);
		}
		if($credit % 2 != 0){
			throw new App_Exception("不是8的整数倍", 1001);
		}
		$credit_coin = $credit / 2;
		M('User')->updateById('
					credit = credit - '.(int)$credit.',
					credit_coin = credit_coin + '.$credit_coin
			, (int)$this->user['id']);
		$user = M('User')->getById((int)$this->user['id']);
		$user->creditCoin($credit_coin,'积分转换成积分币');
		echo json_encode(array('status'=>1,'msg'=>'转换成功'));
		return ;
	}
}