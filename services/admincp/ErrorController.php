<?php

class Admincp_ErrorController extends Admincp_Controller_Action
{
	public function doDefault()
	{
		Suco_Locale::instance()->addPackage('error');

		$e = $this->getParam('error_handle');

		if ($e instanceof App_Exception) {
			$data = array(
				'type' => 'warning',
				'title' => $e->getMessage(),
				'message' => DEBUG ? '<pre>'.$e->getTraceAsString().'</pre>' : '',
				'links' => array(
					array(T('BACK_PREV_PAGE'), $_SERVER['HTTP_REFERER']),
				)
			);
			if ($this->_request->isAjax()) {
				echo json_encode($data);
			} else {
				$view = $this->_initView();
				$view->render('notice.php', $data);
			}
		} elseif ($e instanceof Suco_Controller_Dispatcher_Exception) {
			$this->_response->setStatus(404);
			if (DEBUG) {
				$data = array(
					'type' => 'error',
					'title' => $e->getMessage(),
					'message' => '<pre>'.$e->getTraceAsString().'</pre>',
					'links' => array(
						array(T('TRY_AGAIN'), '&'),
					)
				);
				$view = $this->_initView();
				$view->render('notice.php', $data);
			} else {
				$view = $this->_initView();
				$view->render('404.php');
			}
		} elseif ($e instanceof Suco_Exception) {
			$this->_response->setStatus(500);
			$data = array(
				'type' => 'error',
				'title' => DEBUG ? $e->getMessage() : T('ERR_SYSTEM_EXCEPTION'),
				'message' => DEBUG ? '<pre>'.$e->getTraceAsString().'</pre>' : T('ERR_SYSTEM_EXCEPTION_MESSAGE'),
				'links' => array(
					array(T('TRY_AGAIN'), '&'),
					array(sprintf(T('BACK_HOME_PAGE'), $_SERVER['app_cfg']['site']['title']), 'controller=index')
				)
			);
			$view = $this->_initView();
			$view->render('notice.php', $data);

			//系统异常日志
			$content = "[".date('Y/m/d H:i:s')."] - {$this->_request->getClientIp()}\r\n{$e->getMessage()}\r\n{$e->getTraceAsString()}\r\n\r\n";
			Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', $content, 'a');
		}
	}
}