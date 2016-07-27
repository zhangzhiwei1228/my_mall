<?php

class Special extends Abstract_Model
{
	protected $_name = 'special';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if (isset($data['attachment']) && !$data['attachment']['error']) {
			$data['thumb'] = Suco_File::upload($data['attachment']);
		}
		return parent::inputFilter($data);
	}
}