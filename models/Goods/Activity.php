<?php

class Goods_Activity extends Abstract_Model
{
	protected $_name = 'goods_activity';
	protected $_primary = 'id';

	protected function _insertAfter($data, $id)
	{
		if ($data['setting'] && ($data['type'] == 'discount' || $data['type'] == 'kill')) {
			foreach((array)$data['setting']['goods'] as $gid => $row) {
				M('Goods_Promotion')->insert(array_merge($row, array(
					'goods_id' => $gid,
					'activity_id' => $id,
					'activity_type' => $data['type'],
					'start_time' => $data['start_time'],
					'end_time' => $data['end_time'],
					'is_enabled' => $data['is_enabled']
				)));
			}
		}
	}

	protected function _updateByIdAfter($data, $id)
	{
		if ($data['setting']) {
			M('Goods_Promotion')->delete('activity_id = ?', $id);
			$this->_insertAfter($data, $id);
		}

		if (isset($data['is_enabled'])) {
			M('Goods_Promotion')->update(array(
				'is_enabled' => $data['is_enabled']
			), 'activity_id = '.$id);
		}
	}

	protected function _deleteByIdAfter($id)
	{
		M('Goods_Promotion')->delete('activity_id = ?', $id);
	}

	public function inputFilter($data)
	{
		if (isset($data['start_time']) && !is_numeric($data['start_time'])) {
			$data['start_time'] = strtotime($data['start_time']);
		}
		if (isset($data['end_time']) && !is_numeric($data['end_time'])) {
			$data['end_time'] = strtotime($data['end_time']);
		}
		if (isset($data['setting'])) {
			$data['setting'] = serialize($data['setting']);
		}

		return parent::inputFilter($data);
	}

	public function outputFilter($data)
	{
		if (isset($data['setting'])) {
			$data['setting'] = unserialize($data['setting']);
		}

		return parent::outputFilter($data);
	}

	public function getAvailableActivity()
	{
		return M('Goods_Activity')->select()
			->where('(start_time <= ? OR start_time = 0) AND (end_time >= ? OR end_time = 0) AND is_enabled = 1', time())
			->order('priority DESC')
			->fetchRows();
	}

	public function getTypes()
	{
		return array(
			'discount' => '限时折扣',
			'groupon' => '团购',
			'freeshipping' => '包邮',
			'reduce' => '满就减',
			'gift' => '满就送',
			'package' => '套餐组合',
			'kill' => '秒杀'
		);
	}

	public function getType($row)
	{
		$types = $this->getTypes();
		return $types[$row['type']];
	}
}