<?php

class Admincp_ToolController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doQuery()
	{
		if ($this->_request->isPost()) {
			$sql = $_POST['sql'];
			//SQL安全检测
			$sec = array('delete ', 'drop ', 'truncate ');
			foreach ($sec as $v) {
				if (stristr($sql, $v)) {
					throw new App_Exception('系统禁止了执行此条查询');
				}
			}
			$adapter = @Table::getAdapter();
			$result = $adapter->execute($sql)->fetchRows();
			$affected = $adapter->getAffectedRows();

			if ($result) {
				$fields = array_keys($result[0]);
			}
		}

		$view = $this->_initView();
		$view->result = @$result;
		$view->fields = @$fields;
		$view->affected = @$affected;
		$view->render('tool/query.php');
	}

	public function doDbChk()
	{
		$adapter = @Table::getAdapter();
		$tables = $adapter->getTableList();

		if ($this->_request->isPost()) {
			switch($_POST['act']) {
				case 'repair':
					foreach($_POST['tbs'] as $tb) {
						$result[] = $adapter->execute('REPAIR TABLE '.$tb)->fetchRow();
					}
					break;
				case 'analyze':
					foreach($_POST['tbs'] as $tb) {
						$result[] = $adapter->execute('ANALYZE TABLE '.$tb)->fetchRow();
					}
					break;
			}
		}
		
		foreach($tables as $tb) {
			$tbsChk[] = $adapter->execute('CHECK TABLE '.$tb)->fetchRow();
		}

		$view = $this->_initView();
		$view->result = $result;
		$view->tbsChk = $tbsChk;
		$view->render('tool/repair.php');
	}

	public function doLog()
	{
		$view = $this->_initView();
		$view->logs = Suco_File_Folder::read(LOG_DIR);
		$view->file = Suco_File::read(LOG_DIR.DS.$this->_request->filename);
		$view->render('tool/log.php');
	}
}