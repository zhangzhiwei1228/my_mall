<?php

class User_Address extends Abstract_Model
{
	protected $_name = 'user_address';
	protected $_primary = 'id';
	public function is_def($uid,$aid){
		M('User_Address')->update(array('is_def'=>0), 'user_id = '.(int)$uid);
		M('User_Address')->updateById(array('is_def'=>1), (int)$aid);
	}
}