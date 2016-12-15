<?php

class Admincp_AdvertElementController extends Admincp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->_auth(__CLASS__);
	}

	public function doList()
	{
		$select = M('Advert_Element')->select()
			->order('rank ASC, id ASC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('advert/element/list.php');
	}

	public function doAdd()
	{
		if ($this->_request->isPost()) {
			M('Advert_Element')->insert(array_merge($this->_request->getPosts(), $this->_request->getFiles()));
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->advert = M('Advert')->fetchRows();
		$view->render('advert/element/input.php');
	}

	public function doEdit()
	{
		if ($this->_request->isPost()) {
			M('Advert_Element')->updateById(array_merge($this->_request->getPosts(), $this->_request->getFiles()), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}
		$config = new Suco_Config_Php();
		$app_redirt = $config->load(CONF_DIR.'app.conf.php')->toArray();
		$view = $this->_initView();
		$view->data = M('Advert_Element')->getById((int)$this->_request->id);
		$view->advert = M('Advert')->fetchRows();
		$view->jump = $app_redirt['advertise'];
		$view->render('advert/element/input.php');
	}
}