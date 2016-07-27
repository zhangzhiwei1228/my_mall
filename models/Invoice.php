<?php

class Invoice extends Abstract_Model
{
	protected $_name = 'invoice';
	protected $_primary = 'id';

	public function getTypes()
	{
		return array(
			1 => '增值税普通发票',
			2 => '增值税专用发票'
		);
	}
}