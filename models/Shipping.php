<?php

class Shipping extends Abstract_Model
{
	protected $_name = 'shipping';
	protected $_primary = 'id';

	public function inputFilter($data)
	{
		if (isset($data['attachment']) && !$data['attachment']['error']) {
			$data['logo'] = Suco_File::upload($data['attachment']);
		}

		return parent::inputFilter($data);
	}
}