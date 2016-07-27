<?php

class Page extends Abstract_Model
{
	protected $_name = 'page';
	protected $_primary = 'id';

	public function getByCode($code)
	{
		return $this->select()
			->where('code = ?', $code)
			->fetchRow();
	}
}