<?php

class Admincp_CacheController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doClear()
	{
		Suco_File_Folder::clear(CACHE_DIR);
		return $this->_notice(array(
			'title' => '缓存清除成功！',
			'links' => array(
				array('返回上一页', $_SERVER['HTTP_REFERER'])
			),
			'autoback' => array('自动返回上一页', $_SERVER['HTTP_REFERER']),
		), 'success');
	}
}