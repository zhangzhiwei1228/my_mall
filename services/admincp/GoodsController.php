<?php

class Admincp_GoodsController extends Admincp_Controller_Action
{
	public function init()
	{
		$this->_auth();
	}

	public function doSearch()
	{
		$view = $this->_initView();
		$view->render('goods/search.php');
	}

	public function doList()
	{

		/*$categorytest = M('Goods_Category')->select()
						->where('goods_num = ?', 0)
						->fetchRows();*/
		$categorytest = M('Goods_Category')->select()
		->where('level = ?', 4)
		->fetchRows();
		$arrid='';
		foreach ($categorytest as $key => $r) {
			$arrid.=$r['id'].',';

		}
		$arrid = substr($arrid,0,strlen($arrid)-1);//去掉最后一个逗号
		$shippingtest = M('Shipping')->select()
						->fetchRows();
		$sarrid='';
		foreach ($shippingtest as $key => $r) {
			$sarrid .= $r['id'].',';
		}
		$sarrid = substr($sarrid,0,strlen($sarrid)-1);
		$select = M('Goods')->alias('g')
			->columns('g.*, (g.quantity-g.quantity_warning) as diff_quantify')
			->paginator(20, $this->_request->page);

		switch ($this->_request->t) {
			case 'quantity_warning':
				$select->where('g.is_selling = 1 AND (g.quantity = 0 or (g.quantity - g.quantity_warning <= 0))')->order('g.update_time desc, g.quantity ASC');
				break;
			case 'approval_pending':
				$select->where('g.is_checked = 0');
				break;
			case 'not_approved':
				$select->where('g.is_checked = 1');
				break;
			case 'onsale':
				$select->where('g.is_selling = 1 AND g.is_checked = 2 AND (g.expiry_time = 0 OR g.expiry_time > ?)', time());
				break;
			case 'offsale':
				$select->where('(g.is_selling = 0 AND g.is_checked = 2 OR (g.expiry_time != 0 AND g.expiry_time < ?))', time());
				break;
			case 'promotion':
				$select->where('(g.is_selling = 1 AND g.is_checked = 2 AND g.is_promotion = 1)');
				break;
		}

		if ($this->_request->cid) {
			if($this->_request->cid==-1 && !empty($arrid)){
				$select->where('g.category_id NOT IN ('.($arrid ? $arrid : 0).')');
			} else if ($this->_request->cid==-2&&!empty($sarrid)){
				$select->where('g.shipping_id NOT IN ('.($sarrid ? $sarrid : 0).')');
			} else {
				$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
				$select->where('g.category_id IN ('.($ids ? $ids : 0).')');
			}
		}
		
		if ($this->_request->pid) {
			$select->where('g.id = ?', (int)$this->_request->pid);
		}
		if ($this->_request->code) {
			$select->where('g.code = ?', (string)$this->_request->code);
		}
		if ($this->_request->start_time) {
			$select->where('g.create_time >= ?', strtotime($this->_request->start_time));
		}
		if ($this->_request->end_time) {
			$select->where('g.create_time <= ?', strtotime($this->_request->end_time));
		}
		if ($this->_request->q) {
			//全文索引
			/*
			$keywords = segment($this->_request->q);
			$keywords = explode(' ', $keywords);
			foreach ((array)$keywords as $i => $val) {
				$keywords[$i] = '+'.$val.'*';
			}
			$keyword = implode(' ', (array)$keywords);

			$select->rightJoin(M('Goods_Match')->getTableName().' AS gm', 'gm.goods_id = g.id')
				->match('gm.title', $keyword, 'IN BOOLEAN MODE')
				->columns('g.*');*/
			$select->where('(g.code LIKE ? OR g.title LIKE ? OR g.tags LIKE ?)', '%'.$this->_request->q.'%');
		}
		$select->order('g.create_time DESC');

		$view = $this->_initView();
		$view->category = M('Goods_Category')->getById((int)$this->_request->cid);
		$view->datalist = $select->fetchRows()
			->hasmanyPromotions()
			->hasmanyCategory()
			->hasmanySku();
		
		

		$view->render('goods/list.php');
	}
	/**
	 * 编辑
	 * sku_change 1表示不改变，2表示先清空再重新录入，3表示单个改变
	 */
	public function doEdit()
	{
		$data = M($this->_formatModelName())->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		if ($this->_request->isPost()) {
			$skus = $this->_request->getPosts();
			/*if(count($skus['skus']) > 1) {
				foreach($skus['skus'] as $key => &$exts) {
					if($key == 0) continue;
					$exts['market_price'] = $skus['skus'][0]['market_price'];
					$exts['point1'] = $skus['skus'][0]['point1'];
					$exts['point2'] = $skus['skus'][0]['point2'];
					$exts['exts'] = $skus['skus'][0]['exts'];
				}
			}*/

			M($this->_formatModelName())->updateById(array_merge($skus, $this->_request->getFiles()), (int)$this->_request->id);
			$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/input.php');
	}
	public function doSum($id=0){
		$category=M("Goods_Category")->select()->where("parent_id = ".$id)->fetchRows();
		$arr=array();
		if(!empty($category)){
			foreach($category as $k=>$v){
				var_dump($k);
				$arr[$v["id"]]["son"]=$this->doSum($v["id"]);
			}
		}
		return $arr;
	}
	
	

	public function doCopy()
	{
		$data = M('Goods')->getById((int)$this->_request->id)->toArray();
		$attr = M('Goods_Attribute')->select()
			->where('goods_id = ?', $data['id'])
			->fetchRows()
			->toArray();
		$skus = M('Goods_Sku')->select()
			->where('goods_id = ?', $data['id'])
			->fetchRows()
			->toArray();

		unset($data['id']);
		$data['title'] .= ' - 副本';

		$data['ref_img'] = json_decode($data['ref_img'], 1);
		
		foreach($attr as $row) {
			unset($row['id'], $row['goods_id']);
			$data['attributes'][] = $row;
		}

		foreach($skus as $row) {
			unset($row['id'], $row['goods_id']);
			$data['skus'][] = $row;
		}

		$gid = M('Goods')->insert($data);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}

	public function doRecycle()
	{
		$select = M('Goods')->select()
			->paginator(20, $this->_request->page)
			->where('is_trash = 1');

		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/recycle.php');
	}

	public function doSku()
	{
		$select = M('Goods')->alias('g')
			->rightJoin(M('Goods_Sku')->getTableName().' AS gs', 'g.id = gs.goods_id')
			->columns('gs.*, gs.thumb, (gs.quantity-gs.quantity_warning) as diff_quantify, g.code AS goods_code, g.thumb AS goods_thumb, g.title, g.package_unit, g.package_lot_unit, g.package_quantity')
			->order('(gs.quantity-gs.quantity_warning) asc')
			->paginator(20, $this->_request->page);
		if ($this->_request->q) {
			$select->where('(gs.code LIKE ? OR g.code LIKE ? OR g.title LIKE ?)', '%'.$this->_request->q.'%');
		}
		if ($this->_request->cid) {
			$ids = M('Goods_Category')->getChildIds((int)$this->_request->cid);
			$select->where('g.category_id IN ('.($ids ? $ids : 0).')');
		}

		switch($this->_request->sortby) {
			case 'cost_price_asc';
				$select->order('cost_price ASC');
				break;
			default:
				$select->order('goods_id DESC, id DESC');
				break;
		}


		$view = $this->_initView();
		$view->datalist = $select->fetchRows();
		$view->render('goods/sku.php');
	}

	public function doDetail()
	{
		$data = M('Goods')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('goods/detail.php');
	}

	public function doToggleStatus()
	{
		$fields = array('is_new', 'is_hot', 'is_rec', 'is_selling');
		if (in_array($this->_request->t, $fields)) {
			$field = $this->_request->t;
			$data[$field] = abs($this->_request->v - 1);
			M('Goods')->updateById($data, (int)$this->_request->id);
		} elseif ($this->_request->t == 'is_checked') {
			$data['is_checked'] = $this->_request->v;
			M('Goods')->updateById($data, (int)$this->_request->id);
		}
		$this->redirect(isset($this->_request->ref) ? base64_decode($this->_request->ref) : 'action=list');
	}

	public function doBatch()
	{
		switch($_POST['act']) {
			case 'delete':
				foreach ((array)$_POST['ids'] as $id) {
					M('Goods')->deleteById((int)$id);
				}
				break;
			case 'move':
				if ($_POST['cid']) {
					foreach ((array)$_POST['ids'] as $id) {
						M('Goods')->updateById('category_id = '.(int)$_POST['cid'], (int)$id);
					}
				}
				break;
			case 'onsale':
				foreach ((array)$_POST['ids'] as $id) {
					M('Goods')->updateById('is_selling = 1, is_checked = 2', (int)$id);
				}
				break;
			case 'offsale':
				foreach ((array)$_POST['ids'] as $id) {
					M('Goods')->updateById('is_selling = 0', (int)$id);
				}
				break;
			case 'update_sku':
				foreach ((array)$_POST['data'] as $id => $data) { $i++;
					M('Goods_Sku')->updateById($data, (int)$id);
					$updateGoods[] = $data['goods_id'];
				}

				$updateGoods = array_unique($updateGoods);
				foreach($updateGoods as $goodsId) {
					$sku = M('Goods_Sku')->select('
						MIN(selling_price) min_price, 
						MAX(selling_price) AS max_price,
						SUM(quantity) AS qty')
						->where('goods_id = ?', $goodsId)
						->fetchRow()
						->toArray();
					
					M('Goods')->updateById(array(
						'min_price' => $sku['min_price'],
						'max_price' => $sku['max_price'],
						'quantity' => $sku['qty']
					),(int)$goodsId);
				}
				break;
		}
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
	//商品销售明显
	public function doSales() {
		$goods_id = $this->_request->id;
		$sd = $this->_request->sd;
		$status = $this->_request->status;
		$order_good = M('Order_Goods')->alias('og')
			->columns("sum(o.total_quantity) as o_total_quantity,sum(o.total_amount) as o_total_amount,  FROM_UNIXTIME(og.create_time,'%Y%m%d') as otime")
			->leftJoin(M('Order')->getTableName().' AS o', 'og.order_id = o.id')
			->where('og.goods_id='.(int)$goods_id)
			->group('otime');
		$time_where = '';
		if(!empty($sd)) {
			$otime = explode('-',$sd);
			$time_where .= "FROM_UNIXTIME(og.create_time,'%m')=".$otime[1].' and ';
		}
		if(!empty($status)) {
			$time_where .= $status == 5 ? 'o.status < '.$status : 'o.status='.$status;
		}
		if(!empty($time_where)){
			$order_good->where($time_where);
		}

		$view = $this->_initView();
		$view->order_goods = $order_good->fetchRows()->toArray();
		$view->render('goods/sales.php');
	}
}
