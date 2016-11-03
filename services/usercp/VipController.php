<?php

class Usercp_VipController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		//$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$view = $this->_initView();
		$view->render('views/member.php');
	}

	public function doLevel()
	{
		$view = $this->_initView();
		$view->render('views/member_upgrade.php');
	}

	public function doActive()
	{
		$this->user = $this->_auth();
		if ($this->_request->isPost()) {
			$view = $this->_initView();
			try{
				if ( $this->user['credit'] < 20) {
					throw new App_Exception("激活失败，您的帮帮币不足", 101);
				} else {
					$this->user->credit(20 * -1,'使用20帮帮币激活会员');
					$this->user->is_vip = 1;
					$this->user->save();
					$this->user->activateAddCredit((int)$this->user->id);
					$this->redirect('/usercp/vip');
				}
			} catch(App_Exception $e) {
				$view->message = $e->getMessage();
				$view->code = $e->getCode();
				$view->render('views/shopping/no_enough.php');
				return;
			}
			die();
			$cfg = new Suco_Config_Php();
			$setting = $cfg->load(CONF_DIR.'vip.conf.php');
			$_POST['amount'] = $setting[$_POST['type']];


			$view->payments = M('Payment')->select()
				->where('is_enabled = 1')
				->order('rank ASC, id ASC')
				->fetchRows();
			$view->render('views/payway.php');
			die;
		}
	}

	public function doApply()
	{
		if ($this->_request->isPost()) {
			$_POST['grade'] = $this->_request->vip;
			M('Resale_Apply')->insert($_POST);

			$url = H('Url', 'module=usercp');
			echo '<script>alert(\'申请成功（您的申请，经公司申核的时间为1至2个工作日，到时会有工作人员与你联系。欢迎您的加入，一定会让您有很大的收获，谢谢！）\'); window.location = \''.$url.'\'</script>';
			return;
		}

		$view = $this->_initView();
		$view->render('views/member_act.php');
	}
}