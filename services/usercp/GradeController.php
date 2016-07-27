<?php

class Usercp_GradeController extends Usercp_Controller_Action
{
	public function init()
	{
		parent::init();
		$this->user = $this->_auth();
	}

	public function doDefault()
	{
		$view = $this->_initView();
		$view->render('views/member.php');
	}

	public function doLevel()
	{
		$view = $this->_initView();
		switch($this->_request->t) {
			case 1:
				$tpl = 'views/memberone.php';
				break;
			case 2:
				$tpl = 'views/membertwo.php';
				break;
			case 3:
				$tpl = 'views/memberthr.php';
				break;
			case 4:
				$tpl = 'views/memberfour.php';
				break;
		}
		$view->render($tpl);
	}
}