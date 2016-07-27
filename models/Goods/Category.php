<?php

class Goods_Category extends Abstract_Tree
{
	protected $_name = 'goods_category';
	protected $_primary = 'id';
	
	protected $_referenceMap = array(
		self::CHILDNOTES => array(
			'class' => __CLASS__,
			'type' => 'hasmany',
			'target' => self::PARENT_ID
		),
		'type' => array(
			'class' => 'Goods_Type',
			'type' => 'hasone',
			'target' => 'id',
			'source' => 'type_id'
		)
	);

	public function hasmanyGoods($rows, $limit)
	{
		foreach($rows as $i => $row) {
			$cIds = M('Goods_Category')->getChildIds((int)$row['id']);
			$cIds = $cIds ? trim($cIds, ',') : 0;

			$goods = M('Goods')->select()
				->where('category_id IN ('.$cIds.') AND is_rec = 1')
				->order('id DESC')
				//->limit($limit)
				->fetchRows()
				->hasmanySku()
				->toArray();

			$row->goods = $goods;
			$rows->set($i, $row);
		}
		
		return $rows;
	}

	public function getSearchItems($row, $ft = '')
	{
		//初始化筛选项
		$i = 0;
		foreach((array)$row->type['attr_setting'] as $item) { if ($item['is_search']) $i++; }
		$ft = $ft ? $ft : ltrim(str_repeat('_0', $i), '_');
		$ft = explode('_', @$ft);

		function lk($k, $v, $ft) {
			$ft[$k] = $v;
			return '&page=&ft='.implode('_', $ft);
		}

		$k1 = 0;
		foreach((array)$row->type['attr_setting'] as $item) {
			if ($item['is_search']) {
				$values = array();
				$tmp = explode("\r\n", $item['attr_values']);
				$k2 = 0;
				foreach($tmp as $v) { $k2++;
					$values[$k2] = array(
						'id' => $k2,
						'key' => md5(trim($item['attr_name']).trim($v)),
						'name' => $item['attr_name'],
						'value' => $v,
						'link' => '&page=&ft='.lk($k1, $k2, $ft),
					);
					if ($ft[$k1] == $k2) {
						$selected[$k1] = array(
							'id' => $k2,
							'key' => md5(trim($item['attr_name']).trim($v)),
							'name' => $item['attr_name'],
							'value' => $v,
							'reset' => '&page=&ft='.lk($k1, 0, $ft),
						);
					}
				}
				$searchItems[$k1] = array(
					'name' => $item['attr_name'], 
					'values' => $values,
					'reset' => '&page=&ft='.lk($k1, 0, $ft),
				);
				$k1++;
			}
		}

		return array(
			'selected' => (array)$selected,
			'items' => (array)$searchItems
		);
	}
}