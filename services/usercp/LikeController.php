<?php

class Usercp_LikeController extends Usercp_Controller_Action
{
	public function init()
	{
		$this->user = $this->_auth();
	}

	public function doAdd()
	{
		//滤重
		if ($this->_request->isAjax()) {
			$like = M('User_Like')->select()
				->where('user_id = ? AND ref_type = ? AND ref_id = ?', array(
					$this->user['id'], 
					$this->_request->ref_type,
					$this->_request->ref_id 
				))->fetchRow();

			if ($like->exists()) {
				M('User_Like')->deleteById((int)$like['id']);
				$ct = M('User_Like')->count('ref_type = ? AND ref_id = ?', array($this->_request->ref_type, $this->_request->ref_id));
				echo json_encode(array(
					'status' => 0,
					'message' => 'REPEAT',
					'total' => $ct
				));
			} else {
				M('User_Like')->insert(array(
					'user_id' => $this->user['id'],
					'ref_type' => $this->_request->ref_type,
					'ref_id' => $this->_request->ref_id,
				));
				$ct = M('User_Like')->count('ref_type = ? AND ref_id = ?', array($this->_request->ref_type, $this->_request->ref_id));
				echo json_encode(array(
					'status' => 1,
					'message' => 'SUCCESS',
					'total' => $ct
				));
			}
		}
	}

	public function doDelete()
	{
		M('User_Like')->deleteById((int)$this->_request->id);
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}
}