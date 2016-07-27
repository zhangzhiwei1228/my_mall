<?php

class Agent_ErrorController extends Agent_Controller_Action
{
	public function doDefault()
	{
		Suco_Locale::instance()->addPackage('error');

		$e = $this->getParam('error_handle');

		if ($e instanceof App_Exception) {
			$this->_response->setStatus(202);
			$data = array(
				'type' => 'warning',
				'title' => $e->getMessage(),
				'message' => DEBUG ? $e->getTraceAsString() : '',
				'links' => array(
					array('BACK_PREV_PAGE', $_SERVER['HTTP_REFERER']),
				)
			);
			$view = $this->_initView();
			$view->render('views/notice.php', $data);
		} elseif ($e instanceof Suco_Controller_Dispatcher_Exception) {
			$this->_response->setStatus(404);
			if (DEBUG) {
				$data = array(
					'type' => 'error',
					'title' => $e->getMessage(),
					'message' => $e->getTraceAsString(),
					'links' => array(
						array('TRY_AGAIN', '&'),
					)
				);
				$view = $this->_initView();
				$view->render('views/notice.php', $data);
			} else {
				$view = $this->_initView();
				$view->render('views/404.php');
			}
		} elseif ($e instanceof Suco_Exception) {
			$this->_response->setStatus(202);
			$data = array(
				'type' => 'error',
				'title' => DEBUG ? $e->getMessage() : 'ERR_SYSTEM_EXCEPTION',
				'message' => DEBUG ? $e->getTraceAsString() : 'ERR_SYSTEM_EXCEPTION_MESSAGE',
				'links' => array(
					array('TRY_AGAIN', '&'),
					array(sprintf(t('BACK_HOME_PAGE'), @Setting::get('sitename')), 'controller=index')
				)
			);
			$view = $this->_initView();
			$view->render('views/notice.php', $data);

			//系统异常日志
			$content = "[".date('Y/m/d H:i:s')."] - {$this->_request->getClientIp()}\r\n{$e->getMessage()}\r\n{$e->getTraceAsString()}\r\n\r\n";
			Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', $content, 'a');
		}
	}
}