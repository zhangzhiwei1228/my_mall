<?php

class User_Like extends Abstract_Model
{
	protected $_name = 'user_like';
	protected $_primary = 'id';

	/**
	 * 添加后触发
	 * @param array $data
	 * @param int $id
	 * @return void
	 */
	protected function _insertAfter($data, $id)
	{
		$data = M($data['ref_type'])->getById((int)$data['ref_id']);
		if ($data->exists()) {
			$data->like_num += 1;
			$data->save();
		}
	}

	/**
	 * 删除前触发
	 * @param array $data
	 * @param int $id
	 * @return void
	 */
	protected function _deleteByIdBefore($id)
	{
		$cmt = $this->getById((int)$id);
		$data = M($cmt['ref_type'])->getById((int)$cmt['ref_id']);
		if ($data->exists()) {
			$data->like_num -= 1;
			$data->save();
		}
	}
}