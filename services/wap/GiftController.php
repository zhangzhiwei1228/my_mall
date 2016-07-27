<?php

class GiftController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$this->redirect('action=list');
	}

	public function doList()
	{
		$select = M('Gift')->select()
			->order('id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('gift/list.php');
	}

	public function doDetail()
	{
		$data = M('Gift')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('gift/detail.php');
	}

	public function doApply()
	{
		$user = $this->_auth();
		if ($this->_request->isPost()) {
			$gift = M('Gift')->getById((int)$_POST['gift_id']);
			if (!$gift->exists()) {
				throw new App_Exception('未找到相关商品');
			}

			//滤重
			$apply = M('Gift_Apply')->select()
				->where('user_id = ? AND gift_id = ?', array($user['id'], $_POST['gift_id']))
				->fetchRow();
			if ($apply->exists()) {
				throw new App_Exception('错误，您已兑换过此商品，请勿重复申请');
			}
			//扣积分
			$user->credit($gift['points']*-1, '兑换商品【GF-'.$gift['id'].'】');

			M('Gift_Apply')->insert(array_merge($_POST, array(
				'user_id' => $user['id'],
				'gift_id' => $_POST['gift_id'],
				'points' => $gift['points']
			)));
			
			return $this->_notice(array(
				'title' => '您已成功提交申请',
				'links' => array(
					array('返回上一页', $_SERVER['HTTP_REFERER'])
				),
				'autoback' => array('自动返回上一页', '&action=goods_detail&id='.$_POST['gift_id']),
			), 'success');
		}

		$view = $this->_initView();
		$view->datalist = M('User_Address')->select()
			->where('user_id = ?', $user['id'])
			->order('id DESC')
			->fetchRows();
		$view->selected = $_SESSION['addr_id'];
		$view->gift = M('Gift')->getById((int)$this->_request->id);
		$view->render('gift/apply.php');
	}
}