<?php

class User_Collect extends Abstract_Model
{
	protected $_name = 'user_collect';
	protected $_primary = 'id';

	/**
	 * 添加后触发
	 * @param array $data
	 * @param int $id
	 * @return void
	 */
	protected function _insertAfter($data, $id)
	{
		switch($data['ref_type']) {
			case 'report':
				$fieldName = 'like_num';
				$report = M('Try_Report')->getById((int)$data['ref_id']);
				$report->$fieldName += 1;
				$report->save();
				break;
			case 'photo':
				$fieldName = 'like_num';
				$report = M('Photo')->getById((int)$data['ref_id']);
				$report->$fieldName += 1;
				$report->save();
				break;
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
		$data = $this->getById((int)$id);
		switch($data['ref_type']) {
			case 'report':
				$fieldName = 'like_num';
				$report = M('Try_Report')->getById((int)$data['ref_id']);
				$report->$fieldName -= 1;
				$report->save();
				break;
			case 'photo':
				$fieldName = 'like_num';
				$report = M('Photo')->getById((int)$data['ref_id']);
				$report->$fieldName -= 1;
				$report->save();
				break;
		}
	}
}