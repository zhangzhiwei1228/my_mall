<?php

class Article extends Abstract_Model
{
	protected $_name = 'article';
	protected $_primary = 'id';
	
	protected $_referenceMap = array(
		'category' => array(
			'class' => 'Article_Category',
			'type' => 'hasone',
			'source' => 'category_id',
			'target' => 'id'
		)
	);

	public function inputFilter($data)
	{
		if (isset($data['content']) && !$data['summary']) {
			$data['summary'] = H('cutstr', $data['content'], 255, '', 'utf-8', 1);
		}
		if (isset($data['attachment']) && !$data['attachment']['error']) {
			$data['thumb'] = Suco_File::upload($data['attachment']);
		}
		if (isset($data['post_time']) && !is_numeric($data['post_time'])) {
			$data['post_time'] = strtotime($data['post_time']);
		}

		return parent::inputFilter($data);
	}

	public function getBestByCid($cid, $limit = 10, $fields = '*')
	{
		return $this->select($fields)
			->where('cid = ?', $cid)
			->order('create_time DESC')
			->limit($limit)
			->fetchRows();
	}

	public function getTags($row)
	{
		return M('Article_Tag')->select('tag_name')
			->where('aid = ?', (int)$row['id'])
			->order('id ASC')
			->fetchCols('tag_name');
	}

	/**
	 * 装载缩略图
	 * @return object Suco_Db_Table_Rowset
	 */
	public function hasmanyImage($rows)
	{
		$imgCols = $rows->getColumns('img_id');
		if (!$imgCols) return $rows;
		
		$ids = $imgCols ? implode(',',$imgCols) : 0;
		$images = M('Image')->select()->where('id IN ('.$ids.')')->fetchOnKey('id')->toArray();

		foreach($rows as $k => $row) {
			$row->thumb = $images[$row['img_id']]['src'];
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}

	/**
	 * 装载分类
	 * @return object Suco_Db_Table_Rowset
	 */
	public function hasmanyCategory($rows)
	{
		$cateCols = $rows->getColumns('cid');
		if (!$cateCols) return $rows;
		
		$ids = $cateCols ? implode(',',$cateCols) : 0;
		$cates = M('Article_Category')->select()->where('id IN ('.$ids.')')->fetchOnKey('id')->toArray();

		foreach($rows as $k => $row) {
			$row->cate_name = $cates[$row['cid']]['name'];
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}
}
