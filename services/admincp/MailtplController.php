<?php

class Admincp_MailtplController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doDefault()
	{
		if ($this->_request->success) {
			return $this->_notice(array(
				'title' => '保存成功，系统已更新您的设置！',
				'links' => array(
					array('返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success')
				),
				'autoback' => array('自动返回上一页', isset($this->_request->ref) ? base64_decode($this->_request->ref) : '&success'),
			), 'success');
		}

		if ($this->_request->isPost() && $this->_request->url) {
			$url = base64_decode($this->_request->url);
			$body = '<title>'.$this->_request->getPost('subject').'</title>';
			$body.= '<body>'.$this->_request->getPost('content').'</body>';
			Suco_File::write($url, $body, 'w+');
			$this->redirect('&success=1');
		}
		$files = Suco_File_Folder::read(MTPL_DIR, 'file');
		if ($this->_request->url) {
			$url = base64_decode($this->_request->url);
			$filecontent = Suco_File::read($url);
			preg_match('#<title>(.*)<\/title>#', $filecontent, $s1);
			preg_match('#<body>(.*)<\/body>#', $filecontent, $s2);
			$subject = @$s1[1]; $content = @$s2[1];
		}

		$view = $this->_initView();
		$view->files = $files;
		$view->subject = $subject;
		$view->content = $content;
		$view->render('mailtpl/index.php');
	}
}