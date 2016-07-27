<?php

class User_Comment extends Abstract_Model
{
	protected $_name = 'user_comment';
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
			$data->comment_num += 1;
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
			$data->comment_num -= 1;
			$data->save();
		}
	}

	public function hasmanyUser($rows)
	{
		$ids1 = $rows->getColumns('user_id');
		$ids2 = $rows->getColumns('to_uid');
		$ids = @array_merge($ids1, $ids2);

		$ids = $ids ? implode(',', $ids) : 0;

		$users = M('User')->select('id, nickname, avatar, username, email')
			->where('id IN ('.$ids.')')
			->fetchOnKey('id');

		foreach($rows as $i => $row) {
			$user = $users[$row['user_id']];
			$replyuser = $users[$row['to_uid']];
			$row->nickname = $user->nickname;
			$row->avatar = $user->avatar;
			$row->touser = $replyuser->nickname;
			$rows->set($i, $row->toArray());
		}

		return $rows;
	}
}