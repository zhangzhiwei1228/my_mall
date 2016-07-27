<?php

class User_Follow extends Abstract_Model
{
	protected $_name = 'user_follow';
	protected $_primary = 'id';

	/**
	 * 添加后触发
	 * @param array $data
	 * @param int $id
	 * @return void
	 */
	protected function _insertAfter($data, $id)
	{
		list($refType, $refId) = explode('-', $data['code']);
		switch($refType) {
			case 'report':
				$fieldName = $data['action'].'_num';
				$report = M('Try_Report')->getById((int)$refId);
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
		list($refType, $refId) = explode('-', $data['code']);
		switch($refType) {
			case 'report':
				$fieldName = $data['action'].'_num';
				$report = M('Try_Report')->getById((int)$refId);
				$report->$fieldName -= 1;
				$report->save();
				break;
		}
	}
}