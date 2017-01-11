<?php

class Advert extends Abstract_Model
{
	protected $_name = 'advert';
	protected $_primary = 'id';

	protected $_referenceMap = array(
		'elements' => array(
			'class' => 'Advert_Element',
			'type' => 'hasmany',
			'source' => 'id',
			'target' => 'advert_id'
		)
	);

	public function getByCode($code, $wrap = '%s')
	{
		$pos = $this->select()->where('code = ?', $code)->fetchRow();
		$cssW = $pos['width'] ? 'width:'.$pos['width'].'px' : 'auto';
		$cssH = $pos['height'] ? 'height:'.$pos['height'].'px' : 'auto';
		$w = $pos['width'] ? 'width="'.$pos['width'].'"' : '';
		$h = $pos['height'] ? 'height="'.$pos['height'].'"' : '';
		
		$elements = M('Advert_Element')->select()
			->where('advert_id = ? AND is_enabled AND start_time <= ? AND (end_time >= ? OR end_time = 0)', array($pos['id'], time(), time()))
			->order('rank ASC')
			->fetchRows();
		if ($elements->total()) {
			foreach ($elements as $row) { $i++; if ($i > $pos['limit']) { break; }
				$url = new Suco_Helper_Url($row['link']);
				$img = new Suco_Helper_BaseUrl($row['source']);

				switch ($row['type']) {
					case 'image':
						if ($url != '') {
							$html .= sprintf($wrap, '<a href="'.$url.'" target="_blank"><img src="'.$img.'" alt="'.$row['description'].'" '.$w.' '.$h.' /></a>');
						} else {
							$html .= sprintf($wrap, '<img src="'.$img.'" alt="'.$row['description'].'" '.$w.' '.$h.' />');
						}
						break;
					case 'text':
						$html .= sprintf($wrap, '<div style="'.$cssW.'; '.$cssH.'; overflow:hidden"><a href="'.$url.'" target="_blank">'.$row['description'].'</a></div>');
						break;
					case 'html':
						$html .= sprintf($wrap, '<div style="'.$cssW.'; '.$cssH.'; overflow:hidden">'.$row['html'].'</div>');
						break;
				}
				M('Advert_Element')->updateById('display_num = display_num + 1', (int)$row['id']);
			}
			return $html;
		} elseif ($pos->exists()) {
			return sprintf($wrap, '<div style="'.$cssW.'; '.$cssH.'; overflow:hidden; background:#e0e0e0; text-align:center">Advert:'.$pos['name'].' ['.$pos['code'].']</div>');
		} else {
			return sprintf($wrap, '<div style="background:#e0e0e0;">Advert:['.$code.']</div>');
		}
	}

	public function getRowsByCode($code)
	{
		$pos = $this->select()->where('code = ?', $code)->fetchRow()->toArray();
		$pos['elements'] = M('Advert_Element')->select()
			->where('advert_id = ? AND is_enabled AND start_time <= ? AND (end_time >= ? OR end_time = 0)', array($pos['id'], time(), time()))
			->order('rank ASC')
			->limit($pos['limit'])
			->fetchRows();
		return $pos;
	}

	public function deleteById($id)
	{
		M("Advert_Element")->delete('advert_id = ?', $id);
		return parent::deleteById($id);
	}

	public function __get($code)
	{
		return $this->getByCode($code);
	}

	/**
	 * @param $code
	 * @return mixed
	 * app广告位
	 */
	public function getAppRowsByCode($code)
	{
		$config = new Suco_Config_Php();
		$app_redirt = $config->load(CONF_DIR.'app.conf.php')->toArray();
		$jump = $app_redirt['advertise'];
		if(is_array($code)) {
			$rows = array();
			$code = implode('\',\'',$code);
			$pos = $this->select()->where('code IN ('."'".$code."'".')')->fetchRows()->toArray();
			foreach($pos as $po) {
				$images = M('Advert_Element')->select('id,advert_id,type,description,link,source,is_enabled,start_time,end_time,jump_id,exts_id')
					->where('advert_id = ? AND is_enabled AND start_time <= ? AND (end_time >= ? OR end_time = 0)', array($po['id'], time(), time()))
					->order('rank ASC')
					->limit($po['limit'])
					->fetchRows()->toArray();
				$rows[$po['code']]['limit'] = $po['limit'];
				foreach($images as $key=>$row) {
					if (!strpos($row['source'],$_SERVER['HTTP_HOST'])) {
						$row['source'] = 'http://'.$_SERVER['HTTP_HOST'].$row['source'];
					}
					$row['jump_name'] = $jump[$row['jump_id']]['name'];
					$rows[$po['code']]['images'][$key] = $row;

				}
			}
		} else {
			$pos = $this->select()->where('code = ?', $code)->fetchRow()->toArray();
			$images = M('Advert_Element')->select('id,advert_id,type,description,link,source,is_enabled,start_time,create_time')
				->where('advert_id = ? AND is_enabled AND start_time <= ? AND (end_time >= ? OR end_time = 0)', array($pos['id'], time(), time()))
				->order('rank ASC')
				->limit($pos['limit'])
				->fetchRows()->toArray();
			$rows = array();
			$rows[$pos['code']]['limit'] = $pos['limit'];
			foreach($images as $key=>$row) {
				$row['source'] = 'http://'.$_SERVER['HTTP_HOST'].$row['source'];
				$rows[$pos['code']]['images'][$key] = $row;
			}

		}
		return $rows;
	}
}