<?php

class Navigate extends Abstract_Tree
{
	protected $_name = 'navigate';
	protected $_primary = 'id';

	public function getTypes()
	{
		
	}

	public function getTopMenu()
	{
		return M(__CLASS__)->select()
			->where('is_enabled = 1 AND parent_id = 0')
			->order('rank ASC, id ASC')
			->fetchRows();
	}
	
	public function getSubMenu($row)
	{
		if (!$row['top_path']) {
			return false;	
		}
		return M(__CLASS__)->select()
			->where('is_enabled = 1 AND path_ids LIKE ?', $row['top_path'].'%')
			->order('rank ASC, id ASC')
			->fetchRows()
			->toTree();	
	}

	public function setCurNote($row)
	{
		if ($row == -1) {
			unset($_SESSION[$this->_name]);
			return;
		}
		
		if (!$row['parent_id']) {
			$_SESSION[$this->_name]['top_path'] = $row['path_ids'].','.$row['id'];
		}
		$_SESSION[$this->_name]['cur_note'] = $row['id'];
		return $row;
	}
	
	public function getCurNote()
	{
		$menu = $this->getById((int)@$_SESSION[$this->_name]['cur_note']);
		$menu->top_path = @$_SESSION[$this->_name]['top_path'];
		return $menu;
	}
}