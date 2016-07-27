<?php

class User_Extend extends Abstract_Model
{
	protected $_name = 'user_extend';
	protected $_primary = 'id';
	
	// public function filter($data)
	// {
	// 	if (isset($data['face_upload']) && !$data['face_upload']['error']) {
	// 		$data['face'] = Suco_File::upload($data['face_upload']);
	// 	}
	// 	return parent::filter($data);
	// }
}