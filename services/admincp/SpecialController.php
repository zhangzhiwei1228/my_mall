<?php

class Admincp_SpecialController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}
}