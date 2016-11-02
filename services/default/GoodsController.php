<?php

class GoodsController extends Controller_Action
{
	public function init()
	{
		parent::init();
	}

	public function doDefault()
	{
		$goodsCates = M('Goods_Category')->select()
			->where('parent_id = 0 AND is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();

		$shopCates = M('Shop_Category')->select()
			->where('parent_id = 0 AND is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();

		$view = $this->_initView();
		$view->goodsCates = $goodsCates;
		$view->shopCates = $shopCates;
		$view->render('views/shopping/full_search.php');
	}

	public function doPage()
	{
		$goodsCates = M('Goods_Category')->select()
			->where('parent_id = 0 AND is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();

		$shopCates = M('Shop_Category')->select()
			->where('parent_id = 0 AND is_enabled = 1')
			->order('rank ASC, id ASC')
			->fetchRows();

		$view = $this->_initView();
		$view->goodsCates = $goodsCates;
		$view->shopCates = $shopCates;
		switch($this->_request->t) {
			case 'banr':
				$view->render('views/ban_r.php');
				break;
			case 'banl':
				$view->render('views/ban_l.php');
				break;
			case 'jump':
				$view->render('views/jump.php');
				break;
		}
	}

	public function doNav()
	{
		$view = $this->_initView();
		$view->render('views/welcomew.php');
	}

	public function doSearch()
	{
		$this->doList();
	}

	public function doChannel()
	{
		if ($this->_request->isAjax()) {
			$cates = M('Goods_Category')->select()
				->where('parent_id = '. $this->_request->cid.' and is_enabled<>0')
				->order('rank ASC, id ASC')
				->fetchRows();

			$view = $this->_initView();
			$view->cates = $cates;
			$view->render('views/ajax/good_ajax.php');

			return;
		}

		$view = $this->_initView();
		$cates = M('Goods_Category')->select()
			->where('parent_id = '.$this->_request->cid.' and is_enabled<>0')
			->order('rank ASC, id ASC')
			->fetchRows();
		foreach($cates as $cate) {
			$cates_cids[] = $cate['id'];
		}
		$search_cid = $_COOKIE['search_cid'] ? $_COOKIE['search_cid'] : 0;
		$flag = false;
		if(in_array($search_cid,$cates_cids)) {
			$flag = true;
		}
		//品牌墙
		$brand = M('Brand')->select('*')->where('category_id='.(int)$this->_request->cid)->fetchRow()->toArray();

		$view->cates = $cates;
		$view->flag = $flag;
		$view->brand = $brand;
		$view->render('views/shopping/search_good.php');
	}
	public function doBrand() {
		if ($this->_request->isAjax()) {
			$brand = M('Brand')->select()
				->where('category_id='.(int)$this->_request->cid)
				->order('id ASC')
				->fetchRows();

			$view = $this->_initView();
			$view->brand = $brand;
			$view->render('views/ajax/brand_ajax.php');

			return;
		}
	}
	public function doList()
	{
		//载入分类
		$category = M('Goods_Category')->getById((int)$this->_request->cid);


		$relateCates = $category->getRoot()->getChilds()->toTree();

		$relateAttrs = $category->getSearchItems($this->_request->ft);

		$select = M('Goods')->alias('g')
			->columns('g.id, g.title, g.thumb, g.sup, g.min_price, g.max_price, 
				g.thumb, g.ref_img, g.comments_num, g.follows_num,g.is_selling,g.is_checked')
			->where('g.is_selling = 1 AND g.is_checked = 2 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
			->paginator(6, $this->_request->page);

		/*//按关键词搜索
		if ($this->_request->q) {
			//搜索分类
			$cate = M('Goods_Category')->select()
				->where('name = ?', $this->_request->q)
				->fetchRow();
			if ($cate->exists()) {
				$ids = $cate->getChildIds();
				$select->where('(g.category_id IN ('.($ids ? $ids : 0).') OR reverse(g.title) LIKE '. 'reverse("%'.$this->_request->q.'%")'.' OR reverse(g.tags) like '. 'reverse("%'.$this->_request->q.'%"))');
			} else {
				$select->where('reverse(g.title) LIKE '. 'reverse("%'.$this->_request->q.'%")');
			}
		}

		//按分类搜索
		if ($this->_request->cid) {
			$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
			$select->where('g.category_id IN ('.($ids ? $ids : 0).')');
		}

		//按价格搜索
		if ($this->_request->min_price) {
			$select->where('g.min_price >= ?', $this->_request->min_price);
		}

		if ($this->_request->max_price) {
			$select->where('g.max_price <= ?', $this->_request->max_price);
		}

		//按属性筛选
		if ($this->_request->ft) {
			$ft = explode('_', $this->_request->ft);
			$ids = array();
			foreach($ft as $k1 => $k2) {
				if (!$k2) { continue; }
				$str = trim($relateAttrs['selected'][$k1]['name']);
				$str.= trim($relateAttrs['selected'][$k1]['value']);
				$key = md5($str);

				$gIds = M('Goods_Attribute')->select('goods_id')
					->where('attr_key = ?'.($ftIds ? ' AND goods_id IN ('.implode(',', $ftIds).')' : ''), $key)
					->fetchCols('goods_id');

				$ftIds = @array_merge($ftIds, $gIds);
				$attribute[] = $gIds;
				$filter = 1;
			}

			$ids = array();
			foreach ((array)$attribute as $item) { if (!$item) { $ids = 0; break; }
				$ids = $ids ? array_intersect($ids, $item) : $item;
			}
			if ($filter) {
				$select->where('g.id IN ('.($ids ? implode(',',$ids) : 0).')');
			}
		}

		//排序方式
		switch ($this->_request->sortby) {
			case 'price_lowest': $select->order('g.min_price ASC'); break;
			case 'price_highest': $select->order('g.max_price DESC'); break;
			case 'comment': $select->order('g.comments_num DESC'); break;
			case 'date_added': $select->order('g.create_time DESC'); break;
			case 'transaction': $select->order('g.sales_num DESC'); break;
		}*/
		$select->order('g.update_time DESC');

		//查找可筛选属性
		$c1 = clone($select);
		$attrs = $c1->reset(array('columns','paginator','limit','order'))
			->columns('ga.*, COUNT(*) AS num')
			->rightJoin(M('Goods_Attribute')->getTableName().' AS ga', 'ga.goods_id = g.id')
			->group('ga.attr_key')
			->fetchOnKey('ga.attr_key')
			->toArray();

		foreach($relateAttrs['items'] as $k1 => $row) {
			foreach($row['values'] as $k2 => $item) {
				$key = $item['key'];
				$relateAttrs['items'][$k1]['values'][$k2]['num'] = $attrs[$key]['num'];
			}
		}

		if (!count($relateCates)) {
			$relateCates = M('Goods_Category')->getChilds(0, 3)->toTree();
		}

		/*$cp = $category->getParent();
		if ($cp->exists()) {
			$ids = $cp->getChildIds();
			$ids = explode(',',$ids);
			foreach($ids as $id) {
				$gcategory = M('Goods_Category')->select()->where('is_enabled = 1 and id='.(int)$id)->fetchRow()->toArray();
				if(!$gcategory) continue;
				$ids1[] = $gcategory['id'];
			}
			$ids = implode(',',$ids1);
			$relateGoods = M('Goods')->select()
				->where('category_id IN('.($ids ? $ids : 0).') and category_id <> '.(int)$this->_request->cid.' and is_checked = 2')//去掉已选择的，并去掉限制
				->limit(50)
				->fetchRows()
				->hasmanySku()
				->hasmanyPromotions();
		}*/


		$view = $this->_initView();
		/*$view->datalist = $select->fetchRows()
			->hasmanySku()
			->hasmanyPromotions();*/

		$view->category = $category;
		$view->relateCates = $relateCates;
		$view->relateAttrs = $relateAttrs;
//		$view->relateGoods = $relateGoods;

		$view->render('views/shopping/product_list.php');
	}
	public function doGetRelateGoodsList() {
		$category = M('Goods_Category')->getById((int)$this->_request->cid);
		$cp = $category->getParent();
		if ($cp->exists()) {
			$ids = $cp->getChildIds();
			$ids = explode(',',$ids);
			foreach($ids as $id) {
				$gcategory = M('Goods_Category')->select()->where('is_enabled = 1 and id='.(int)$id)->fetchRow()->toArray();
				if(!$gcategory) continue;
				$ids1[] = $gcategory['id'];
			}
			$ids = implode(',',$ids1);
			$relateGoods = M('Goods')->select()
				->where('category_id IN('.($ids ? $ids : 0).') and category_id <> '.(int)$this->_request->cid.' and is_checked = 2')//去掉已选择的，并去掉限制
				->limit(100)
				->fetchRows()
				->hasmanySku()
				->hasmanyPromotions();
		}
		$view = $this->_initView();
		$view->relateGoods = $relateGoods;
		$view->render('views/shopping/relate_ajax_list.php');
	}
	public function doGetgoodlist()
	{
		//载入分类
		$category = M('Goods_Category')->getById((int)$this->_request->cid);
//		$relateCates = $category->getRoot()->getChilds()->toTree();
		$relateAttrs = $category->getSearchItems($this->_request->ft);

		$select = M('Goods')->alias('g')
			->columns('g.id, g.title, g.thumb, g.sup, g.min_price, g.max_price,
				g.thumb, g.ref_img, g.comments_num, g.follows_num,g.is_selling,g.is_checked')
			->where('g.is_selling = 1 AND g.is_checked = 2 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time())
			->paginator(6, $this->_request->page);

		//按关键词搜索
		if ($this->_request->q) {
			//搜索分类
			$cate = M('Goods_Category')->select()
				->where('name = ?', $this->_request->q)
				->fetchRow();
			if ($cate->exists()) {
				$ids = $cate->getChildIds();
				$select->where('(g.category_id IN ('.($ids ? $ids : 0).') OR reverse(g.title) LIKE '. 'reverse("%'.$this->_request->q.'%")'.' OR reverse(g.tags) like '. 'reverse("%'.$this->_request->q.'%"))');
			} else {
				$select->where('reverse(g.title) LIKE '. 'reverse("%'.$this->_request->q.'%")');
			}
		}

		//按分类搜索
		if ($this->_request->cid) {
			$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
			$select->where('g.category_id IN ('.($ids ? $ids : 0).')');
		}

		//按价格搜索
		if ($this->_request->min_price) {
			$select->where('g.min_price >= ?', $this->_request->min_price);
		}

		if ($this->_request->max_price) {
			$select->where('g.max_price <= ?', $this->_request->max_price);
		}

		//按属性筛选
		if ($this->_request->ft) {
			$ft = explode('_', $this->_request->ft);
			$ids = array();
			foreach($ft as $k1 => $k2) {
				if (!$k2) { continue; }
				$str = trim($relateAttrs['selected'][$k1]['name']);
				$str.= trim($relateAttrs['selected'][$k1]['value']);
				$key = md5($str);

				$gIds = M('Goods_Attribute')->select('goods_id')
					->where('attr_key = ?'.($ftIds ? ' AND goods_id IN ('.implode(',', $ftIds).')' : ''), $key)
					->fetchCols('goods_id');

				$ftIds = @array_merge($ftIds, $gIds);
				$attribute[] = $gIds;
				$filter = 1;
			}

			$ids = array();
			foreach ((array)$attribute as $item) { if (!$item) { $ids = 0; break; }
				$ids = $ids ? array_intersect($ids, $item) : $item;
			}
			if ($filter) {
				$select->where('g.id IN ('.($ids ? implode(',',$ids) : 0).')');
			}
		}

		//排序方式
		switch ($this->_request->sortby) {
			case 'price_lowest': $select->order('g.min_price ASC'); break;
			case 'price_highest': $select->order('g.max_price DESC'); break;
			case 'comment': $select->order('g.comments_num DESC'); break;
			case 'date_added': $select->order('g.create_time DESC'); break;
			case 'transaction': $select->order('g.sales_num DESC'); break;
		}
		$select->order('g.update_time DESC');

		//查找可筛选属性
		$c1 = clone($select);
		$attrs = $c1->reset(array('columns','paginator','limit','order'))
			->columns('ga.*, COUNT(*) AS num')
			->rightJoin(M('Goods_Attribute')->getTableName().' AS ga', 'ga.goods_id = g.id')
			->group('ga.attr_key')
			->fetchOnKey('ga.attr_key')
			->toArray();

		foreach($relateAttrs['items'] as $k1 => $row) {
			foreach($row['values'] as $k2 => $item) {
				$key = $item['key'];
				$relateAttrs['items'][$k1]['values'][$k2]['num'] = $attrs[$key]['num'];
			}
		}

		/*if (!count($relateCates)) {
			$relateCates = M('Goods_Category')->getChilds(0, 3)->toTree();
		}*/

		/*$cp = $category->getParent();
		if ($cp->exists()) {
			$ids = $cp->getChildIds();
			$relateGoods = M('Goods')->select()
				->where('category_id IN('.($ids ? $ids : 0).') and category_id <> '.(int)$this->_request->cid.' and is_checked = 2')//去掉已选择的，并去掉限制
				->limit(50)
				->fetchRows()
				->hasmanySku()
				->hasmanyPromotions();
		}*/


		$view = $this->_initView();
		$view->datalist = $select->fetchRows()
			->hasmanySku()
			->hasmanyPromotions();

		/*$view->category = $category;
		$view->relateCates = $relateCates;
		$view->relateAttrs = $relateAttrs;
		$view->relateGoods = $relateGoods;*/

		$view->render('views/shopping/goods_ajax_list.php');
	}

	public function doDetail()
	{
		M('Order')->restore();
		$data = M('Goods')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$sku = M('Goods_Sku')->select()
			->where('goods_id = ?', $data['id'])
			->fetchRow();

		$view = $this->_initView();
		$view->data = $data->hasonePromotion();
		$view->sup = $data['sup'];

		$view->sku = $sku;
		$view->render('views/shopping/good_detail.php');

		if (count($_SESSION['history']) > 10) {
			array_shift($_SESSION['history']);
		}
		$_SESSION['history'][] = $this->_request->id;
		array_unique($_SESSION['history']);
	}

	public function doGetComments()
	{
		$ct = M('Goods_Comment')
			->select('COUNT(*) AS t,
				SUM(CASE WHEN score > 3 THEN 1 ELSE 0 END) AS hp,
				SUM(CASE WHEN score = 3 THEN 1 ELSE 0 END) AS zp,
				SUM(CASE WHEN score < 3 THEN 1 ELSE 0 END) AS cp')
			->where('goods_id = ?', $this->_request->id)
			->fetchRow()
			->toArray();
		
		$select = M('Goods_Comment')->alias('gc')
			->leftJoin(M('User')->getTableName().' AS u', 'u.id = gc.buyer_id')
			->columns('gc.*, u.nickname AS buyer_name, u.avatar AS buyer_avatar')
			->where('goods_id = ?', $this->_request->id)
			->paginator(20, $this->_request->page);

		switch($this->_request->t) {
			case 1:
				$select->where('gc.score > 3');
				break;
			case 2:
				$select->where('gc.score = 3');
				break;
			case 3:
				$select->where('gc.score < 3');
				break;
		}

		$view = $this->_initView();
		$view->ct = $ct;
		$view->datalist = $select->fetchRows();
		$view->render('goods/comments.php');
	}

	public function doGetSkuInfo()
	{
		$data = M('Goods_Sku')->getById((int)$this->_request->sku_id);
		echo $data->hasonePromotion()->toJson();
	}

	public function doGetGoodsLists()
	{
		$select = M('Goods')->select('id, title, min_price, max_price, thumb')
			->limit(10)
			->where('is_selling = 1');
		
		switch($this->_request->t) {
			case 'hot':
				if ($this->_request->opts['cid']) {
					$ids = M('Goods_Category')->getChildIds((int)$this->_request->opts['cid']);
					$select->where('category_id IN ('.($ids ? $ids : 0).')');
				}
				$select->order('sales_num DESC');
				break;
			case 'relate':
				if ($this->_request->opts['cid']) {
					$ids = M('Goods_Category')->getChildIds((int)$this->_request->opts['cid']);
					$select->where('category_id IN ('.($ids ? $ids : 0).')');
				}
				$select->order('sales_num DESC');
				break;
			case 'follow':
				$ids = M('User_Follow')->select()
					->where('ref_type = \'goods\' AND user_id = ?', M('User')->getCurUser()->id)
					->fetchCols('ref_id');
				$ids = $ids ? implode(',', $ids) : 0;
				$select->where('id IN ('.$ids.')');
				break;
			case 'history':
				$ids = $_SESSION['history'] ? implode(',', $_SESSION['history']) : 0;
				$select->where('id IN ('.$ids.')')
					->order(array('substring_index(\''.$ids.'\',id,1)'));
				break;
		}

		if ($this->_request->opts['limit']) {
			$select->limit($this->_request->opts['limit']);
		}

		$goods = $select->fetchRows()
			->hasmanyPromotions();
		foreach($goods as $i => $row) {
			$tmp = $row->toArray();
			$tmp['thumb'] = getImage($row['thumb'], '300x300');
			if ($row['promotion_price']) {
				$tmp['price'] = $row['promotion_price'];
			}

			$tmp['click_url'] = (string)H('Url', 'controller=goods&action=detail&id='.$row['id']);
			$goods->set($i, $tmp);
		}

		echo $goods->toJson();
	}

	public function doGetFollowIds()
	{
		echo json_encode(M('User_Follow')->select('ref_id')
			->where('user_id = ? AND ref_type = ?', array(
				M('User')->getCurUser()->id,
				'goods'
			))->fetchCols('ref_id'));
	}

	/**
	 * 判断规格的库存量
	 */
	public function doGetgoodsku() {
		$param = $this->_request->param;
		$good_id = (int)$this->_request->good_id;
		$quantity = $sku_id = 0;
		$arrs = M('Goods_Sku')->select()
			->where('goods_id = '.$good_id.' and reverse(spec) LIKE '. 'reverse("%'.$param.'%")')
			->fetchRows()->toArray();
		if(count($arrs)) {
			foreach ($arrs as $arr) {
				$quantity += $arr['quantity'];
				$sku_id = $arr['id'];
			}
		} else {
			$quantity = 'error';
		}
		echo json_encode(array('quantity'=>$quantity,'sku_id'=>$sku_id));
	}
}