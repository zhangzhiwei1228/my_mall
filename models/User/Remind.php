<?php

class User_Remind extends Abstract_Model
{
	protected $_name = 'user_remind';
	protected $_primary = 'id';

	public function send($row, $key, $msg)
	{
		$user = M('User')->getById((int)$row['user_id']);

		//短信通知
		if ($row['sms'][$key] && $user->getAuth('mobile')->status == 1) {
			M('Sms')->send($user->profile['mobile'], '【共享贷】'.$msg.'请登录网站查看详情。');
		}

		//邮件通知
		if ($row['mail'][$key]) {
			M('Mail')->sendTpl($user['email'], '', 'remind.tpl', array(
				'user' => $user['username'],
				'msg' => $msg
			));
		}

		//站内信通知
		if ($row['msg'][$key]) {
			M('User_Msg')->sendTpl($user['id'], 'remind.tpl', array(
				'user' => $user['username'],
				'msg' => $msg
			));
		}
	}
}