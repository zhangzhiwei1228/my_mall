<?php

class Goods extends Abstract_Model
{
	protected $_name = 'goods';
	protected $_primary = 'id';
	public $category_name=array();
	protected $_referenceMap = array(
		'category' => array(
			'class' => 'Goods_Category',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'category_id'
		),
		'attributes' => array(
			'class' => 'Goods_Attribute',
			'type' => 'hasmany',
			'target' => 'goods_id',
			'source' => 'id'
		),
		'skus' => array(
			'class' => 'Goods_Sku',
			'type' => 'hasmany',
			'target' => 'goods_id',
			'source' => 'id'
		),
		'seller' => array(
			'class' => 'User',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'seller_id'
		),
	);

	/**
	 * 添加商品后触发
	 * @param array $data
	 * @param int $id
	 * @return void
	 */
	protected function _insertAfter($data, $id)
	{
		//添加商品SKU
		if (isset($data['skus']) && $data['skus']) {
			foreach ((array)$data['skus'] as $item) {
				$item['goods_id'] = $id;
				M('Goods_Sku')->insert($item);
			}
		}

		//添加商品属性
		if (isset($data['attributes'])) {
			foreach ((array)$data['attributes'] as $item) {
				if (!($item['attr_name'] && $item['attr_value'])) continue;
				$item['goods_id'] = $id;
				$item['category_id'] = $data['category_id'];
				$values = (array)$item['attr_value'];
				$colour = (array)$item['attr_color'];

				foreach($values as $k => $val) {
					$item['attr_value'] = $val;
					$item['attr_color'] = $colour[$k];
					M('Goods_Attribute')->insert($item);
				}
			}
		}
	}

	/**
	 * 更新商品后触发
	 * @param array|string $data
	 * @return void
	 */
	protected function _updateByIdAfter($data, $id)
	{
		if (is_array($data)) {

			if(isset($data['sku_change']) && $data['sku_change'] == 1) {
				if (isset($data['skus']) && $data['skus']) {
					$sku_spec_good = M('Goods_Sku')->select('spec,id')->where("goods_id = ".(int)$id)->fetchRows()->toArray();
					foreach ((array)$data['skus'] as $item) {
						$sku_spec = M('Goods_Sku')->select('spec')->where("spec like '%".$item['spec']."%' and goods_id = ".(int)$id)->fetchRow()->toArray();
						if(empty($sku_spec)) {
							$item['goods_id'] = $id;
							M('Goods_Sku')->insert($item);
						}else{
							$item['goods_id'] = $id;
						    M('Goods_Sku')->update($item,"spec like '%".$item['spec']."%' and goods_id = ".(int)$id);
						}
						$dskus1[] = $item['spec'];
					}

					foreach($sku_spec_good as $key=> $sku) {
						$dskus[$sku['id']] = $sku['spec'];
					}
					if(!empty($dskus)&&!empty($dskus1)){					
						// 获取重复数据的数组
						$repeats = array_diff ( $dskus, $dskus1 );
						foreach($repeats as $key=>$repeat) {
							M('Goods_Sku')->delete('goods_id = '.(int)$id." and id =".$key);
						}
					}

				}
				$attr_value = '';
				if (isset($data['attributes'])) {
					foreach ((array)$data['attributes'] as $item) {
						if (!($item['attr_name'] && $item['attr_value'])) continue;
						$item['goods_id'] = $id;
						$item['category_id'] = $data['category_id'];
						$values = (array)$item['attr_value'];
						$colour = (array)$item['attr_color'];

						foreach($values as $k => $val) {
							$item['attr_value'] = $val;
							$item['attr_color'] = $colour[$k];
							$attr_value .= "'".$val."',";
							if(strpos($val,',')) {
								$attr1 = M('Goods_Attribute')->select('id')->where("goods_id = ".(int)$id." and attr_name like '%".$item['attr_name']."%'")->fetchRow()->toArray();
							} else {
								$attr1 = M('Goods_Attribute')->select('id')->where("goods_id = ".(int)$id." and attr_value like '%".$val."%'")->fetchRow()->toArray();
							}
							if($attr1) {
								M('Goods_Attribute')->updateById($item,(int)$attr1['id']);
							} else {
								M('Goods_Attribute')->insert($item);								
							}
						}
					}
					$attr_value = substr($attr_value,0,strlen($attr_value)-1);
					M('Goods_Attribute')->delete('goods_id = '.(int)$id." and attr_value not in (".$attr_value.")");
				}
				
			} elseif(isset($data['sku_change']) && $data['sku_change'] == 3) {
				$sku_total = M('Goods_Sku')->select('count(*) as total')->where('goods_id = '.(int)$id)->fetchRow()->toArray();
				$attr_total = M('Goods_Attribute')->select('count(*) as total')->where('goods_id = '.(int)$id)->fetchRow()->toArray();

				if(count($data['skus']) > (int)$sku_total['total']) {

					foreach($data['skus'] as $sku) {
						$sku_spec = M('Goods_Sku')->select('spec')->where("spec like '%".$sku['spec']."%' and goods_id = ".(int)$id)->fetchRow()->toArray();
						if(!$sku_spec) {
							$sku['goods_id'] = $id;
							M('Goods_Sku')->insert($sku);
						}
					}
					$attr_value = '';
					foreach ((array)$data['attributes'] as $item) {
						if (!($item['attr_name'] && $item['attr_value'])) continue;
						$item['goods_id'] = $id;
						$item['category_id'] = $data['category_id'];
						$values = (array)$item['attr_value'];
						$colour = (array)$item['attr_color'];

						foreach($values as $k => $val) {
							$item['attr_value'] = $val;
							$item['attr_color'] = $colour[$k];
							$attr_value .= "'".$val."',";
							//$attribute = M('Goods_Attribute')->select('attr_name,id,attr_type,attr_value')->where("attr_value like '%".$val."%' and goods_id = ".(int)$id)->fetchRow()->toArray();
							$attribute = M('Goods_Attribute')->select('attr_name,id,attr_type,attr_value')->where("goods_id = ".(int)$id." and attr_value  =  '".$val."'")->fetchRow()->toArray();//lj0914
							if(!$attribute) {
								M('Goods_Attribute')->insert($item);
							}
						}
					}
					$attr_value = substr($attr_value,0,strlen($attr_value)-1);
					M('Goods_Attribute')->delete('goods_id = '.(int)$id." and attr_value not in (".$attr_value.")");
				} elseif(count($data['skus']) < (int)$sku_total['total']) {
					$skus = M('Goods_Sku')->select('spec,id')->where('goods_id = '.(int)$id)->fetchRows()->toArray();
					foreach($data['skus'] as $dsku) {
						$dskus1[] = $dsku['spec'];
					}
					foreach($skus as $key=> $sku) {
						$dskus[$sku['id']] = $sku['spec'];
					}

					// 获取重复数据的数组
					$repeats = array_diff ( $dskus, $dskus1 );
					foreach($repeats as $key=>$repeat) {
						M('Goods_Sku')->delete('goods_id = '.(int)$id." and id =".$key);
					}

					$attr_value = '';
					foreach ((array)$data['attributes'] as $item) {
						if (!($item['attr_name'] && $item['attr_value'])) continue;
						$item['goods_id'] = $id;
						$item['category_id'] = $data['category_id'];
						$values = (array)$item['attr_value'];
						$colour = (array)$item['attr_color'];

						foreach($values as $k => $val) {
							$attr_value .= "'".$val."',";
							$item['attr_value'] = $val;
							$item['attr_color'] = $colour[$k];
						}
					}
					$attr_value = substr($attr_value,0,strlen($attr_value)-1);
					M('Goods_Attribute')->delete('goods_id = '.(int)$id." and attr_value not in (".$attr_value.")");
				} else {
					foreach ((array)$data['skus'] as $item) {
						$item['goods_id'] = $id;
						M('Goods_Sku')->update($item,"spec like '%".$item['spec']."%' and goods_id = ".(int)$id);
					}
				}

			} else {
				//清空商品SKU
				if (isset($data['skus'])){
					M('Goods_Sku')->delete('goods_id = '.(int)$id);
				}
				//清空商品属性
				if (isset($data['attributes'])) {
					M('Goods_Attribute')->delete('goods_id = '.(int)$id);
				}
				$this->_insertAfter($data, $id);
			}
		}
	}

	/**
	 * 删除商品后触发
	 * @param int $cond
	 * @return void
	 */
	protected function _deleteByIdAfter($id)
	{
		M('Goods_Comment')->delete('goods_id = '.(int)$id);
		M('Goods_Attribute')->delete('goods_id = '.(int)$id);
		M('Goods_Sku')->delete('goods_id = '.(int)$id);
		M('Goods_Promotion')->delete('goods_id = '.(int)$id);
	}

	/**
	 * 添加商品
	 * 若未添加商品货号系统自动生成一个
	 * @return string
	 */
	public function insert($data)
	{
		$data['code'] = $data['code'] ? $data['code'] : $this->getCode();
		$data['score_avg'] = 5;
		return parent::insert($data);
	}

	/**
	 * 输入数据前过滤
	 * @return string
	 */
	public function inputFilter($data)
	{
		//获取第一张作为缩略图
		if ($data['ref_img']) {
			//重新排序
			foreach($data['ref_img'] as $item) {
				$arr[] = $item;
			}
			$data['ref_img'] = $arr;

			$img = current($data['ref_img']);
			$data['thumb'] = M('Image')->getById($img['id'])->src;
			$data['ref_img'] = json_encode($data['ref_img']);
		}
		if (isset($data['is_checked']) && $data['is_checked'] < 2) {
			$data['is_selling'] = 0;
		}
		if (isset($data['is_selling']) && $data['is_selling'] == 1) {
			$data['is_checked'] = 2;
		}
		if (isset($data['expiry_time'])) {
			$data['expiry_time'] = strtotime($data['expiry_time']);
		}
		if (isset($data['sup'])) {
			$data['sup'] = json_encode($data['sup']);
		}

		//统计库存
		if (isset($data['skus'])) {
			$qty = 0;
			foreach($data['skus'] as $item) {
				$qty += $item['quantity'];
			}
			$data['quantity'] = $qty;
		}

		return parent::inputFilter($data);
	}

	/**
	 * 输出数据前过滤
	 * @return string
	 */
	public function outputFilter($data)
	{
		$data['price'] = $this->formatPrice($data['min_price'], $data['max_price']);
		$data['unit'] = $data['package_quantity'] ? $data['package_lot_unit'] : $data['package_unit'];

		if (isset($data['sup']) && !is_array($data['sup'])) {
			$data['sup'] = @json_decode($data['sup'], 1);
		}

		return $data;
	}

	/**
	 * 生成货号
	 * @return string
	 */
	public function getCode()
	{
		$maxId = $this->select('MAX(id) AS result')->fetchCol('result');
		$id = $maxId + 1000000;
		return 'GD-'.$id;
	}

	/**
	 * 返回SKU选项
	 * @return object Suco_Db_Table_Rowset
	 */
	public function getSkuOpts($row)
	{
		$attrs = M('Goods_Attribute')->select()
			->where('goods_id = ? AND is_sku = 1', $row['id'])
			->order('id ASC')
			->fetchRows();

		foreach($attrs as $k => $row) {
			$n = $row['attr_name'];
			$opts[$n]['name'] = $row['attr_name'];
			$opts[$n]['type'] = $row['attr_type'];
			$opts[$n]['values'][$k] = $row['attr_value'];
			$opts[$n]['colour'][$k] = $row['attr_color'];
		}

		return $opts;
	}

	/**
	 * 返回商品标签
	 * @return object Suco_Db_Table_Rowset
	 */
	public function getTags($row)
	{
		return M('Goods_Tag')->select('tag_name')
			->where('goods_id = ?', (int)$row['id'])
			->order('id ASC')
			->fetchCols('tag_name');
	}

	/**
	 * 返回商品属性
	 * @return object Suco_Db_Table_Rowset
	 */
	public function getAttrs($row)
	{
		$list = M('Goods_Attribute')->select()
			->where('is_sku = 0 AND goods_id = ?', (int)$row['id'])
			->order('id ASC')
			->fetchRows()
			->toArray();

		foreach($list as $item) {
			$k = $item['attr_name'];
			$attrs[$k]['attr_name'] = $item['attr_name'];
			$attrs[$k]['attr_value'][] = $item['attr_value'];
		}
		return (array)$attrs;
	}

	/**
	 * 格式化价格
	 * @return string
	 */
	public function formatPrice($minPrice, $maxPrice)
	{
		if ($minPrice == $maxPrice) {
			return number_format($minPrice,2);
		} else {
			return number_format($minPrice,2).' - '.number_format($maxPrice,2);
		}
	}
	

	/**
	 * 返回商品促销信息
	 * @return object Suco_Db_Table_Rowset
	 */
	public function hasonePromotion($row)
	{
		$promotion = M('Goods_Promotion')->select()
			->where('is_enabled AND goods_id = ?', $row['id'])
			->where('(start_time = 0 OR start_time <= ?) AND (end_time = 0 OR end_time >= ?)', time())
			->fetchRow();

		if ($promotion->exists() && $promotion->activity_type == 'discount') {
			//计算价格
			$minPrice = $row['min_price'] * ($promotion->discount/10);
			$maxPrice = $row['max_price'] * ($promotion->discount/10);

			$row->price_label = $promotion->price_label;
			$row->discount = $promotion->discount;
			$row->qty_limit = $promotion->qty_limit;
			$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
			$row->is_promotion = 1;
		} elseif ($promotion->exists() && $promotion->activity_type == 'kill') {
			//计算价格
			$minPrice = $promotion->kill_price;
			$maxPrice = $promotion->kill_price;

			$row->price_label = $promotion->price_label;
			$row->kill_price = $promotion->kill_price;
			$row->qty_limit = $promotion->qty_limit;
			$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
			$row->is_promotion = 1;
		} elseif ($_SESSION['vip_discount']) {
			$row->is_promotion = 1;
			$row->price_label = 'VIP折扣';
			$row->discount = $_SESSION['vip_discount']*10;
			$row->activity_id = 0;

			$minPrice = $row->min_price * $_SESSION['vip_discount'];
			$maxPrice = $row->max_price * $_SESSION['vip_discount'];
			$row->qty_limit = $promotion->qty_limit;
			$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
		}

		return $row;
	}

	/**
	 * 装载促销信息
	 * @return object Suco_Db_Table_Rowset
	 */
	public function hasmanyPromotions($rows)
	{
		$ids = $rows->getColumns('id');
		$ids = $ids ? implode(',', $ids) : 0;

		$promotions = M('Goods_Promotion')->select()
			->where('is_enabled AND goods_id IN ('.$ids.')')
			->where('(start_time = 0 OR start_time <= ?) AND (end_time = 0 OR end_time >= ?)', time())
			->fetchOnKey('goods_id')
			->toArray();

		foreach($rows as $k => $row) {
			$id = $row['id'];
			if (isset($promotions[$id])) {
				$row->is_promotion = 1;
				$row->price_label = $promotions[$id]['price_label'];
				$row->activity_id = $promotions[$id]['activity_id'];
				$row->activity_type = $promotions[$id]['activity_type'];

				if ($row->activity_type == 'kill') {
					$row->kill_price = $promotions[$id]['kill_price'];
					$minPrice = $row->kill_price;
					$maxPrice = $row->kill_price;
					$row->qty_limit = $promotions[$id]['qty_limit'];
					$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
				} elseif ($row->activity_type == 'discount') {
					$row->discount = $promotions[$id]['discount'];
					$minPrice = $row->min_price * ($promotions[$id]['discount']/10);
					$maxPrice = $row->max_price * ($promotions[$id]['discount']/10);
					$row->qty_limit = $promotions[$id]['qty_limit'];
					$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
				}
				
				$rows->set($k, $row->toArray());
			} elseif ($_SESSION['vip_discount']) {
				$row->is_promotion = 1;
				$row->price_label = 'VIP折扣';
				$row->discount = $_SESSION['vip_discount']*10;
				$row->activity_id = 0;

				$minPrice = $row->min_price * $_SESSION['vip_discount'];
				$maxPrice = $row->max_price * $_SESSION['vip_discount'];
				$row->qty_limit = $promotions[$id]['qty_limit'];
				$row->promotion_price = $this->formatPrice($minPrice, $maxPrice);
				
				$rows->set($k, $row->toArray());
			}
		}

		return $rows;
	}
	public function hasmanyCategoryParentId($parent_id) {
		$parent = M('Goods_Category')->select()
			->where('id = ?', (int)$parent_id)->fetchRows()->toArray();
		if($parent[0]['name']) {
			$this->category_name []= $parent[0]['name'];
			$this->hasmanyCategoryParentId($parent[0]['parent_id']);
		}

	}
	public function hasmanyCategory($rows)
	{
		$cateCols = $rows->getColumns('category_id');
		if (!$cateCols) return $rows;

		$cache = Suco_Cache::factory('file');
		$cates = $cache->load('all_cates');
		//$cates = M('Goods_Category')->fetchOnKey('id');

		foreach($rows as $k => $row) {
			$row->category_name = $cates[$row['category_id']]['path_text'];
			$this->hasmanyCategoryParentId($row->category_id);
			if($this->category_name){
			$this->category_name = array_reverse($this->category_name);
			$row->category_name = implode(">",$this->category_name);
			}
			unset($this->category_name);
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}

	public function hasmanySku($rows)
	{
		$ids = $rows->getColumns('id');
		if (!$ids) return $rows;

		//print_r($ids);
		$arrs = M('Goods_Sku')->select()
			->where('goods_id IN ('.($ids ? implode(',', $ids) : 0).')')
			->fetchRows()
			->toArray();

		foreach($arrs as $sku) {
			$sku['exts'] = json_decode($sku['exts'], 1);
			$skus[$sku['goods_id']][] = $sku;
		}

		foreach($rows as $k => $row) {
			$row->skus = $skus[$row['id']];
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}

	/**
	 * @param $rows
	 * @return mixed
	 * app的sku组合
	 */
	public function AppHasManySku($rows) {
		$ids = $rows->getColumns('id');
		if (!$ids) return $rows;
		$arrs = M('Goods_Sku')->select('market_price,point1,point2,point3,point4,point5,exts')
			->where('goods_id IN ('.($ids ? implode(',', $ids) : 0).')')
			->fetchRow()
			->toArray();
		foreach($rows as $k => $row) {
			$row->market_price = $arrs['market_price'];
			$row->point1 = $arrs['point1'];
			$row->point2 = $arrs['point2'];
			$row->point3 = $arrs['point3'];
			$row->point4 = $arrs['point4'];
			$row->point5 = $arrs['point5'];
			$row->exts = $arrs['exts'];
			$rows->set($k, $row->toArray());
		}

		return $rows;
	}

	/**
	 * @param $row
	 * @return mixed
	 * app sku选项
	 */
	public function AppGetSkuOpts($row)
	{
		$attrs = M('Goods_Attribute')->select()
			->where('goods_id = ? AND is_sku = 1', $row['id'])
			->order('id ASC')
			->fetchRows()->toArray();
		$i = 0;
		foreach($attrs as $k => $row) {
			$n = $row['attr_name'];
			$opts[$n]['name'] = $row['attr_name'];
			//$opts[$n]['type'] = $row['attr_type'];
			if(isset($row['attr_value']) && $row['attr_value']) {
				$opts[$n]['values'][]['name'] = $row['attr_value'];
			}
			if(isset($row['attr_color']) && $row['attr_color']) {
				$opts[$n]['colour'][]['name'] = $row['attr_color'];
			}
			$i++;
		}
		$values = array_values($opts);
		return $values;
	}
}