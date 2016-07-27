<?php

class Admin extends User
{
	protected $_login_timeout = 3600;
	protected $_cookie_name = __CLASS__;

	protected $_referenceMap = array(
		'group' => array(
			'class' => 'Admin_Group',
			'type' => 'hasone',
			'source' => 'group_id',
			'target' => 'id'
		)
	);

	public function insert($data)
	{
		$data['is_admin'] = 1;
		$data['role'] = 'admin';
		return parent::insert($data);
	}

	public function login($user, $pass)
	{
		$user = parent::login($user, $pass);
		if ($user['is_admin'] != 1) {
			parent::logout();
			throw new App_Exception('ERR_LOGIN_FAIL');
		}

		return $user;
	}
}