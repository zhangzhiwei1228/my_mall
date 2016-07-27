<?php

class Admincp_GoodsActivityController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	/**
	 * 添加活动页面
	 * @return void;
	 */
	public function doAdd()
	{
		if ($this->_request->isPost()) {
			$id = M('Goods_Activity')->insert($this->_request->getPosts());
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list&cid=' . $this->_request->getPost('category_id'));
		}

		$view = $this->_initView();
		$view->types = M('Goods_Activity')->getTypes();
		$view->render('goods/activity/input.php');
	}

	/**
	 * 编辑活动页面
	 * @return void;
	 */
	public function doEdit()
	{
		$data = M('Goods_Activity')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			M('Goods_Activity')->updateById($this->_request->getPosts(), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->types = M('Goods_Activity')->getTypes();
		$view->render('goods/activity/input.php');
	}

	/**
	 * 启用禁用活动
	 * @return void;
	 */
	public function doEnabled()
	{
		$data = M('Goods_Activity')->getById((int)$this->_request->id);
		$data->is_enabled = abs($data->is_enabled - 1);
		$data->save();

		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	/**
	 * 活动设置页面 (Ajax加载)
	 * @return void;
	 */
	public function doSetting()
	{
		if (!$this->_request->type) {
			return;
		}
		$data = M('Goods_Activity')->getById((int)$this->_request->activity_id);
		$view = $this->_initView();
		$view->setLayout(false);
		$view->setting = $data['setting'];
		$view->render('goods/activity/setting/'.$this->_request->type.'.php');
	}

	/**
	 * 可选活动商品 (Ajax加载)
	 * @return void;
	 */
	public function doGoodsSelectable()
	{
		$select = M('Goods')->alias('g')
			->where('g.is_selling = 1')
			->order('g.id DESC')
			->paginator(6, $this->_request->selectable_page);

		//已经选中的商品
		$ids = $this->_request->setting['goods_ids'];

		//过滤ID
		if ($ids) {
			$select->where('g.id NOT IN ('.($ids ? $ids : 0).')');
		}

		//按分类搜索
		if ($this->_request->cid) {
			$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
			$select->where('g.category_id IN ('.($ids ? $ids : 0).')');
		}

		//关键字搜索
		if ($this->_request->q) {
			$select->where('(g.code LIKE ? OR g.title LIKE ? OR g.tags LIKE ?)', '%'.$this->_request->q.'%');
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->datalist = $select->fetchRows()
			->hasmanyPromotions();
		$view->render('goods/activity/goods/selectable.php');
	}

	/**
	 * 已选定的活动商品 (Ajax加载)
	 * @return void;
	 */
	public function doGoodsSelected()
	{
		$data = M('Goods_Activity')->getById((int)$this->_request->activity_id);
		$ids = $this->_request->setting['goods_ids'];

		$select = M('Goods')->alias('g')
			->columns('g.*')
			->where('g.id IN ('.($ids ? $ids : 0).')')
			->order(array('substring_index(\''.$ids.'\',g.id,1) DESC'));

		if ($this->_request->q2) {
			$select->where('(g.code LIKE ? OR g.title LIKE ? OR g.tags LIKE ?)', '%'.$this->_request->q2.'%');
		}

		$datalist = $select->fetchRows();
		$setting = $data['setting'];
		//合并活动设置的数据
		foreach ($datalist as $i => $row) {
			$id = $row['id'];
			if (isset($setting['goods'][$id])) {
				$row = array_merge($row->toArray(), $setting['goods'][$id]);
			}
			$datalist->set($i, $row);
		}

		$view = $this->_initView();
		$view->setLayout(false);
		$view->datalist = $datalist;
		$view->render('goods/activity/goods/selected.php');
	}
}