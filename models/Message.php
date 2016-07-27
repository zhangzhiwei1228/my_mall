<?php

class Message extends Abstract_Model
{
	protected $_name = 'message';
	protected $_primary = 'id';

	//$uid为-1所有管理员接收
	public function send($uid, $message) 
	{
		return $this->insert(array(
			'recipient_uid' => $uid,
			'sender_uid' => 0,
			'content' => $message
		));
	}

	public function sendTpl($uid, $tpl, $data = array())
	{
		$tpl = $this->parseTpl($tpl, $data);
		$this->send($uid, $tpl);
	}

	public function parseTpl($tpl, $data)
	{
		$body = Suco_File::read(MTPL_DIR.$tpl);

		preg_match_all('#\{\$(.*?)\}#', $body, $vars, PREG_SET_ORDER);
		foreach ($vars as $val) {
			$body = str_replace($val[0], $data[$val[1]], $body);
		}

		return $body;
	}
}