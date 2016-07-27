<?php

class Mail
{
	
	public function send($to, $from, $subject, $body, $type = 'TXT', 
		$charset = 'utf-8', $cc = null, $bcc = null, $attch = null)
	{
		$from = $from ? $from : M('Setting')->smtp_user;
		try {
			$mail = new Suco_Socket_Mail_Smtp(
				M('Setting')->smtp_host, 
				M('Setting')->smtp_port, 
				M('Setting')->smtp_user, 
				M('Setting')->smtp_pass
			);

			$mail->send($to, $from, $subject, $body, $type, $charset, $cc, $bcc, $attch);
			$send = 1;
		} catch (Suco_Socket_Exception $e) {
			$send = $e->getMessage();
		}

		return $send;
	}

	public function sendTpl($to, $form, $tpl, $data = array())
	{
		$from = $from ? $from : M('Setting')->smtp_user;

		$tpl = $this->parseTpl($tpl, $data);
		$this->send($to, $from, $tpl[0], $tpl[1], 'HTML');
	}

	public function parseTpl($tpl, $data)
	{
		$body = Suco_File::read(MTPL_DIR.$tpl);

		preg_match_all('#\{\$(.*?)\}#', $body, $vars, PREG_SET_ORDER);
		foreach ($vars as $val) {
			$body = str_replace($val[0], $data[$val[1]], $body);
		}
		preg_match('#<title>(.*)<\/title>#', $body, $s1);
		preg_match('#<body>(.*)<\/body>#', $body, $s2);

		return array($s1[1], $s2[1]);
	}
}