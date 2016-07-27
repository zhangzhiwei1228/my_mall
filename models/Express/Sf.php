<?php

class Express_Sf extends Suco_Model implements Express_Interface
{
	protected $_gateway = 'http://syt.sf-express.com/css/newmobile/queryBillInfo.action';

	public function tracking($code) {
		$data = json_decode(file_get_contents($this->_gateway.'?delivery_id='.$code), 1);
		return new Suco_Object($data);
	}
}