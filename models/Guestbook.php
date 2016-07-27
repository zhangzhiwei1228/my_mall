<?php

class Guestbook extends Abstract_Model
{
	protected $_name = 'guestbook';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'user' => array(
			'class' => 'User',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'user_id'
		)
	);

	public function outputFilter($data)
	{
		if (isset($data['type_id'])) {
			switch($data['type_id']) {
				case 1:
					$data['type'] = '投诉';
					break;
				case 2:
					$data['type'] = '建议';
					break;
			}
		}

		return $data;
	}
}