<?php

class Usercp_CollectController extends Usercp_Controller_Action
{
	public function init()
	{
		$this->user = $this->_auth();
	}

	public function doGoods()
	{
		$select = M('Goods')->alias('g')
			->rightJoin(M('User_Collect')->getTableName().' AS uf', 'g.id = uf.ref_id')
			->columns('g.*, uf.id AS fid')
			->where('uf.ref_type = \'goods\' AND uf.user_id = ?', $this->user['id'])
			->order('uf.id DESC')
			->paginator(20, $this->_request->page);

		$view = $this->_initView();
		$view->datalist = $select->fetchRows()
			->hasmanyPromotions();
		$view->render('usercp/follow/goods.php');
	}

	public function doAdd()
	{
		if ($this->_request->isAjax()) {
			$like = M('User_Collect')->select()
				->where('user_id = ? AND ref_type = ? AND ref_id = ?', array(
					$this->user['id'], 
					$this->_request->ref_type,
					$this->_request->ref_id 
				))->fetchRow();

			if ($like->exists()) {
				M('User_Collect')->deleteById((int)$like['id']);
			} else {
				M('User_Collect')->insert(array(
					'user_id' => $this->user['id'],
					'ref_type' => $this->_request->ref_type,
					'ref_id' => $this->_request->ref_id,
				));
			}
			echo json_encode(array(
				'status' => $like->exists() ? 1 : 0
			));
		}
	}

	public function doDelete()
	{
		M('User_Collect')->deleteById((int)$this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}