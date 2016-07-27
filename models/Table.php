<?php

class Table extends Suco_Db_Table
{
	protected $_prefix = 'scn_';

	public function init()
	{
		//给除了日志表之外的所有表添加日志触发器
		#if (get_class($this) == 'Log') {
		#	return;
		#}
		//$this->addTrigger(new Log_Trigger());
	}

	public function insert($data)
	{
		if (!isset($data['create_time'])) {
			$data['create_time'] = $data['update_time'] = time();
		}
		return parent::insert($data);
	}

	public function updateById($data, $id)
	{
		if (is_array($data)) {
			$data['update_time'] = time();
		}
		return parent::updateById($data, $id);
	}

	public function deleteById($id)
	{
		return parent::deleteById($id);
	}

	public function getAdapter()
	{
		$conf = Suco_Config::factory(CONF_DIR.'db.conf.php')->toArray();
		$dsn = current($conf);
		return Suco_Db::factory($dsn, __CLASS__);
	}
}
