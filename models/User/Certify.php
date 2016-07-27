<?php

class User_Certify extends Abstract_Model
{
	protected $_name = 'user_certify';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'user' => array(
			'class' => 'User',
			'type' => 'hasone',
			'source' => 'user_id',
			'target' => 'id'
		),
		'profile' => array(
			'class' => 'User_Profile',
			'type' => 'hasone',
			'source' => 'user_id',
			'target' => 'user_id'
		)
	);
}