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
		$data = M('Proportion')->getById((int)$this->_request->cid);
		$left_name = M('Coltypes')->select('name,english')->where('id='.$data['left_id'])->fetchRow()->toArray();
		$right_name = M('Coltypes')->select('name,english')->where('id='.$data['right_id'])->fetchRow()->toArray();
		$view->data = $data;
		$view->left_name = $left_name['name'];
		$view->left_type = $left_name['english'];
		$view->right_name = $right_name['name'];
		$view->right_type = $right_name['english'];
		/*$view->logs = M('User_Credit')->select()
			->where('user_id = ?', $this->user['id'])
			->order('id DESC')
			->paginator(20, $this->_request->page)
			->fetchRows();*/

		$view->render('views/payrecord.php');
	}
	public function doConversion() {
		$credit = $this->_request->credit;
		$data = M('Proportion')->getById((int)$this->_request->cid);
		$left_name = M('Coltypes')->select('name,english')->where('id='.$data['left_id'])->fetchRow()->toArray();
		$right_name = M('Coltypes')->select('name,english')->where('id='.$data['right_id'])->fetchRow()->toArray();


		if($credit > $this->user[$left_name['english']]){
			throw new App_Exception("输入的数字大于您所拥有的", 1001);
		}
		if($credit % floor(($data['l_digital'])) != 0){
			throw new App_Exception("不是".floor(($data['l_digital']))."的整数倍", 1001);
		}
		$credit_coin = $credit * ($data['r_digital']/$data['l_digital']);
		/*M('User')->updateById('credit = credit - '.(int)$credit.',credit_coin = credit_coin + '.$credit_coin
			, (int)$this->user['id']);*/
		$user = M('User')->getById((int)$this->user['id']);
		$user->$left_name['english'] = $user->$left_name['english'] - (int)$credit;
		//$user->$right_name['english'] = $user->$right_name['english'] + (int)$credit_coin;
		$user->save();
		$glod_id = 0;
		$desc = '以【'.$data['l_digital'].':'.$data['r_digital'].'】的比例进行【'.$left_name['name'].'转换成'.$right_name['name'].'】';
		switch($right_name['english']) {
			case 'credit':
				$user->credit($credit_coin,$desc);
				break;
			case 'credit_happy':
				$user->creditHappy($credit_coin,$desc);
				break;
			case 'credit_coin':
				$user->creditCoin($credit_coin,$desc);
				break;
			case 'vouchers':
				$user->vouchers($credit_coin,$desc);
				break;
			case 'worth_gold':
				$extra = array(
					'uid' => $this->user->id,
					'privilege' => $credit_coin,
					'code' => $this->doRandStr(),
					'status' => 3,
				);
				$glod_id = M('Worthglod')->insert($extra);
				$user->worthGold($credit_coin,$desc);
				break;
		}
		//$user->creditCoin($credit_coin,'积分转换成积分币');
		echo json_encode(array('status'=>1,'msg'=>'转换成功','glod_id'=>$glod_id));
		return ;
	}
	//转换
	public function doConversionList() {
		$datas = M('Proportion')->select()->where('type=9')->fetchRows()->toArray();
		foreach($datas as &$data) {
			$left_name = M('Coltypes')->select('name')->where('id='.$data['left_id'])->fetchRow()->toArray();
			$right_name = M('Coltypes')->select('name')->where('id='.$data['right_id'])->fetchRow()->toArray();
			$data['left_name'] = $left_name['name'];
			$data['right_name'] = $right_name['name'];
		}
		$view = $this->_initView();
		$view->datalist = $datas;
		$view->render('views/numerical_list.php');
	}
	function doRandStr($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
	{
		$chars_length = (strlen($chars) - 1);
		$string = $chars{rand(0, $chars_length)};
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			$r = $chars{rand(0, $chars_length)};
			if ($r != $string{$i - 1}) $string .=  $r;
		}
		return $string;
	}
}