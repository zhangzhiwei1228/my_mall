<?php

abstract class Abstract_Tree extends Abstract_Model
{
	const ID			 = 'id';
	const PARENT_ID	 = 'parent_id';
	const CAPTION		= 'name';
	const PATH			= 'path_ids';
	const LEVEL			= 'level';
	const CHILDS_COUNT	= 'childs_num';
	const CHILDNOTES	= 'childnotes';

	protected $_referenceMap = array(
		self::CHILDNOTES => array(
			'class' => __CLASS__,
			'type' => 'hasmany',
			'target' => self::PARENT_ID
		)
	);

	/**
	 * 返回不含自己及子类的分类
	 * @param int $id 分类ID
	 * @return Suco_Db_Table_Rowset
	*/
	public function getItemsNoSelfChild($row)
	{
		if (!$row instanceof Suco_Object) {
			$row = $this->getById((int)$row);
		}
		$path = $row['path'].','.$row['id'];

		return $this->select()
			->where(self::PATH.' != ? AND '.self::PATH.' NOT LIKE ? AND id != ?', array($path, $path, $row['id']))
			->order('rank ASC, name ASC, id ASC')
			->fetchRows();
	}

	/**
	 * 返回顶级分类
	 * @param bool $hidden 是否隐藏被禁用的分类
	 * @return Suco_Db_Table_Rowset
	*/
	public function getTops($cid = 0, $hidden = 0)
	{
		return $this->select()
			->where(self::PARENT_ID.' = ?'.($hidden ? ' AND is_enabled = 1' : ''), $cid)
			->order('rank ASC, name ASC, id ASC')
			->fetchRows();
	}

	/**
	 * 返回指定分类的子类
	 * @param int $id 分类ID
	 * @param int $level 限制层级
	 * @param bool $hidden 是否隐藏被禁用的分类
	 * @return Suco_Db_Table_Rowset
	*/
	public function getChilds($row = 0, $level = 0, $hidden = 0)
	{
		if (!$row) {
			$select = $this->select()->order('rank ASC, id ASC');
			if ($level) {
				$select->where(self::LEVEL.' <= '.$level);
			}
			if ($hidden) {
				$select->where('is_enabled = 1');
			}
			return $select->fetchRows();
		}
		if (!$row instanceof Suco_Object) {
			$row = $this->getById((int)$row);
		}

		$select = $this->select()->order('rank ASC, id ASC');
		$select->where('('.self::PATH.' LIKE ? OR '.self::PATH.' = ?)',
			array($row[self::PATH].','.(int)$row[self::ID].',%', $row[self::PATH].','.(int)$row[self::ID], (int)$row[self::ID]));
		if ($level) {
			$select->where(self::LEVEL.' <= '.($level+$row[self::LEVEL]));
		}
		if ($hidden) {
			$select->where('is_enabled = 1');
		}
		return $select->fetchRows();
	}

	/**
	 * 返回指定分类的子类ID
	 * @param Suco_Object|int $row
	 * @return string
	*/
	public function getChildIds($row)
	{
		if (!$row instanceof Suco_Object) {
			$row = $this->getById((int)$row);
		}
		$ids = $this->select(self::ID)
					->where(self::PATH.' LIKE ? OR '.self::PATH.' = ? OR '.self::ID.' = ?',
						array($row[self::PATH].','.(int)$row[self::ID].',%', $row[self::PATH].','.(int)$row[self::ID], (int)$row[self::ID]))
					->fetchCols(self::ID);
		return implode(',', $ids);
	}

	/**
	 * 返回指定分类的兄弟类
	 * @param Suco_Object|int $row
	 * @return Suco_Db_Table_Rowset
	*/
	public function getSiblings($row, $hidden = 0)
	{
		if (!$row instanceof Suco_Object) {
			$row = $this->getById((int)$row);
		}
		
		$select = $this->select()
			->where(self::PARENT_ID.' = ?', $row[self::PARENT_ID])
			->order('rank ASC, id ASC');
		if ($hidden) {
			$select->where('is_enabled = 1');
		}

		return $select->fetchRows();
	}

	/**
	 * 返回指定分类的父类
	 * @param Suco_Object|int $row
	 * @return Suco_Db_Table_Rowset
	*/
	public function getParent($row)
	{
		if (!$row instanceof Suco_Object) {
			return $this->getById((int)$row);
		} else {
			return $this->getById($row[self::PARENT_ID]);
		}
	}

	/**
	 * 返回根目录
	 * @param Suco_Object|int $row
	 * @return Suco_Db_Table_Rowset
	*/
	public function getRoot($row)
	{
		if ($row[self::LEVEL] > 1) {
			$ids = explode(',', $row[self::PATH]);
			return $this->getById((int)$ids[1]);
		} else {
			return $row;
		}
	}

	/**
	 * 返回指定分类的路径ID
	 * @param Suco_Object|int $row
	 * @return string
	*/
	public function getPathIds($row)
	{
		if (!$row instanceof Suco_Object) {
			$row = $this->getById((int)$row);
		}
		if ($row->exists()) {
			return $row[self::PATH] . ',' . $row[self::ID];
		} else {
			return 0;
		}
	}

	/**
	 * 返回指定分类的路径
	 * @param int $id 分类ID
	 * @return Suco_Db_Table_Rowset
	*/
	public function getPath($row)
	{
		$ids = $this->getPathIds($row);
		return $this->select()
			->where(self::ID.' IN ('.$ids.')')
			->order(self::PATH)
			->fetchOnKey(self::ID);
	}

	public function insert($data)
	{
		//计算路径
		$data[self::PARENT_ID] = (int)@$data[self::PARENT_ID] < 0 ? 0 : @$data[self::PARENT_ID];
		$data[self::PATH] = $this->getPathIds(@$data[self::PARENT_ID]);
		$data[self::LEVEL] = substr_count($data[self::PATH], ',') + 1;
		$id = parent::insert($data);

		//更新子类数量
		parent::update(self::CHILDS_COUNT.' = '.self::CHILDS_COUNT.' + 1', self::ID.' IN ('.($data[self::PATH] ? $data[self::PATH] : 0).')');
		return $id;
	}

	public function updateById($data, $id)
	{
		if (is_array($data)) {
			if (isset($data[self::PARENT_ID])) {
				//计算路径
				$data[self::PARENT_ID] = (int)@$data[self::PARENT_ID] < 0 ? 0 : @$data[self::PARENT_ID];
				$data[self::PATH] = $this->getPathIds($data[self::PARENT_ID]);
				$data[self::LEVEL] = substr_count($data[self::PATH], ',') + 1;

				//替换所有子类
				$current = $this->getById((int)$id);
				$this->update(
					self::PATH.' = REPLACE('.self::PATH.', \''.$current[self::PATH].','.$current[self::ID].'\', \''.$data[self::PATH].','.$current[self::ID].'\'), '.
					self::LEVEL.' = CHAR_LENGTH('.self::PATH.') - CHAR_LENGTH(REPLACE('.self::PATH.', \',\' , \'\')) + 1',
					self::PATH.' LIKE \''.$current[self::PATH].','.$current[self::ID].',%\' OR '.self::PARENT_ID.' = '.$current[self::ID]
				);

				//更新子类数量
				$category = $this->getById((int)$current[self::PARENT_ID]);
				parent::update(self::CHILDS_COUNT.' = '.self::CHILDS_COUNT.' - ('.$current[self::CHILDS_COUNT].'+1)', self::ID.' IN ('.($current[self::PATH] ? $current[self::PATH] : 0).')');
				$category = $this->getById((int)$data[self::PARENT_ID]);
				parent::update(self::CHILDS_COUNT.' = '.self::CHILDS_COUNT.' + ('.$current[self::CHILDS_COUNT].'+1)', self::ID.' IN ('.($data[self::PATH] ? $data[self::PATH] : 0).')');
			}
		}

		return parent::updateById($data, $id);
	}

	public function deleteById($id)
	{
		$current = $this->getById((int)$id);
		if ($current->exists()) {
			//删除所有子类
			parent::delete(self::PATH.' LIKE \''.$current[self::PATH].','.$current[self::ID].',%\' OR '.self::PARENT_ID.' = '.$current[self::ID]);
			//更新子类数量
			parent::update(self::CHILDS_COUNT.' = '.self::CHILDS_COUNT.' - ('.$current[self::CHILDS_COUNT].'+1)', self::ID.' IN ('.($current[self::PATH] ? $current[self::PATH] : 0).')');
		}
		return parent::deleteById($id);
	}

	public function toTreeList($data, $parentId = 0, $lv = 0)
	{
		if ($data instanceof Suco_Db_Table_Rowset) {
			$data = $data->toArray();
		}
		if (!$data) return false;
		$lv++; $tree = array();
		foreach ($data as $row) {
			if ($row[self::PARENT_ID] == $parentId) {
				$row[self::LEVEL] = $lv - 1;
				$tree[] = $this->outputFilter($row);
				if ( $children = $this->toTreeList($data, $row[self::ID], $lv)) {
					$tree = array_merge($tree, $children);
				}
			}
		}

		return $tree;
	}

	public function toTree($data)
	{
		if (!$data) return false;

		if ($data instanceof Suco_Db_Table_Rowset) {
			$data = $data->toArray();
		}

		$arr = array();
		foreach ($data as $key => $row) {
			$arr[$row[self::ID]] = $row;
		}
		unset($data);
		$tree = array();
		foreach ((array)$arr as $key => $row) {
			$parentId = $row[self::PARENT_ID];
			if ($parentId && isset($arr[$parentId])) {
				$parent =& $arr[$parentId];
				$parent[self::CHILDNOTES][] =& $arr[$key];
			} else {
				$tree[$row[self::ID]] =& $arr[$key];
			}
		}
		return $tree;
	}

	public function openNote($id)
	{
		$_SESSION[$this->_name]['open'][$id] = true;
	}

	public function closeNote($id)
	{
		unset($_SESSION[$this->_name]['open'][$id]);
	}

	public function setOpenNotes($notes)
	{
		$_SESSION[$this->_name]['open'] = $notes;
	}

	public function getOpenNotes()
	{
		return isset($_SESSION[$this->_name]['open']) ? $_SESSION[$this->_name]['open'] : 0;
	}
}