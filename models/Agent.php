<?php

class Agent extends User
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
		$data['role'] = 'agent';
		return parent::insert($data);
	}

	public function login($user, $pass)
	{
		$user = parent::login($user, $pass);
		if ($user['role'] != 'agent' 
			&& $user['role'] != 'seller'
				&& $user['role'] != 'resale'
					&& $user['role'] != 'staff' ) {
			parent::logout();
			throw new App_Exception('登录失败，用户名或密码不正确');
		}

		return $user;
	}
}