<?php

class Admincp_UserCertifyController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doList()
	{
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;

		$select = M('User_Certify')->alias('ua')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = ua.user_id')
			->columns('ua.*, u.username, u.nickname')
			->order('ua.id DESC')
			->paginator($pageSize, $currentPage);

		switch ($this->_request->t) {
			case 'pending':
				$select->where('ua.status = 0');
				break;
			case 'yes':
				$select->where('ua.status = 1');
				break;
			case 'no':
				$select->where('ua.status = -1');
				break;
		}

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('user/certify/list.php');
	}

	public function doVerify()
	{
		if ($this->_request->isPost()) {

			$data = M('User_Certify')->getById((int)$this->_request->id);
			$data->status = $_POST['status'];
			$data->feedback = $_POST['feedback'];
			$data->save();

			switch($data['type']) {
				case 'name': $type = '实名认证'; break;	
				case 'mobile': $type = '手机认证'; break;	
				case 'email': $type = '邮箱认证'; break;	
				case 'vip': $type = 'VIP认证'; break;	
				case 'staff': $type = '集团员工认证'; break;	
				case 'enterprise': $type = '企业认证'; break;	
			}

			switch($_POST['status']) {
				case 1:
					$data->user->getRemind()->send('auth', 
						'恭喜您！您申请的'.$type.'已通过审核。');
					break;
				case -1:
					$data->user->getRemind()->send('auth', 
						'很遗憾！您申请的'.$type.'未通过审核。原因：'.$_POST['feedback']);
					break;
			}
			
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list&t=pending');
		}
	}
}