<?php

class Advert_Element extends Abstract_Model
{
	protected $_name = 'advert_element';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'advert' => array(
			'class' => 'Advert',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'advert_id'
		)
	);
	
	public function filter($data)
	{
		//上传图片
		if (isset($data['upload']) && !$data['upload']['error']) {
			$data['source'] = Suco_File::upload($data['upload']);
		}
		if (isset($data['start_time']) && $data['start_time']) {
			$data['start_time'] = strtotime($data['start_time']);
		}
		if (isset($data['end_time']) && $data['end_time']) {
			$data['end_time'] = strtotime($data['end_time']);
		}
		return parent::filter($data);
	}
}