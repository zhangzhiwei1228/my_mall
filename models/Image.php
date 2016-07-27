<?php

class Image extends Abstract_Model
{
	protected $_name = 'image';
	protected $_primary = 'id';

	public function deleteById($id)
	{
		$data = $this->getById($id);
		$conf = Suco_Config::factory(CONF_DIR.'image.conf.php');

		foreach($conf->img_allow_sizes as $size) {
			$url = getImage($data['src'], $size);
			Suco_File::delete(WWW_DIR.$data['src']);
			Suco_File::delete(WWW_DIR.$url);
		}
		
		$data->remove();
	}
}