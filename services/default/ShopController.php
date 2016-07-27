<?php

class ShopController extends Controller_Action
{
	public $areas = array();
	public $provinces_id = array();//省
	public $cities_id = array();//市
	public $areas_id = array();//区
	public $provinces = array();//省
	public $cities = array();//市
	public $area = array();//区


	public $pro_city_region = array();
	public $city_region = array();
	public $region = array();

	public $new_array = array();
	public function init()
	{
		parent::init();
	}

	public function doSearch()
	{
		$this->doList();
	}

	public function doList()
	{
		$category = M('Shop_Category')->getById((int)$this->_request->cid);
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;
		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('sc.name AS cate_name, s.*')
			->order('s.is_rec DESC, s.id DESC')
			->paginator($pageSize, $currentPage);

		//按分类查找
		if ($this->_request->cid) {
			$ids = M('Shop_Category')->getChildIds((int)$this->_request->cid);
			$select->where('s.category_id IN ('.($ids ? $ids : $this->_request->cid).')');
		}
		//按关键词查找
		if ($this->_request->q) {
			$select->where('s.name LIKE ?', '%'.$this->_request->q.'%');
		}
		//按关键词查找
		if ($this->_request->area_id) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$select->where('s.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}
		//按照特殊性查找
		if(!$this->_request->area_id && !$this->_request->cid && !$this->_request->q) {
			$is_special = !$this->_request->is_special ? 's.is_special = 0 OR s.is_special = 1' : ($this->_request->is_special == 1 ? 's.is_special = 1' : 's.is_special = 0');
			$select->where($is_special);
		}

		$view = $this->_initView();
		$view->category = $category;
		$view->datalist = $select->fetchRows();
		$view->render('views/shopping/shop_list.php');
	}

	public function doGetshoplist() {
		$category = M('Shop_Category')->getById((int)$this->_request->cid);
		$pageSize = 20;
		$currentPage = isset($this->_request->page) ? $this->_request->page : $this->_request->page;
		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('sc.name AS cate_name, s.*')
			->order('s.is_rec DESC, s.id DESC')
			->paginator($pageSize, $currentPage);

		//按分类查找
		if ($this->_request->cid) {
			$ids = M('Shop_Category')->getChildIds((int)$this->_request->cid);
			$select->where('s.category_id IN ('.($ids ? $ids : $this->_request->cid).')');
		}
		//按关键词查找
		if ($this->_request->q) {
			$select->where('s.name LIKE ?', '%'.$this->_request->q.'%');
		}
		//按关键词查找
		if ($this->_request->area_id) {
			$ids = M('Region')->getChildIds((int)$this->_request->area_id);
			$select->where('s.area_id IN ('.($ids ? $ids : $this->_request->area_id).')');
		}
		//按照特殊性查找
		if(!$this->_request->area_id && !$this->_request->cid && !$this->_request->q) {
			$is_special = !$this->_request->is_special ? 's.is_special = 0 OR s.is_special = 1' : ($this->_request->is_special == 1 ? 's.is_special = 1' : 's.is_special = 0');
			$select->where($is_special);
		}
		$view = $this->_initView();
		$view->category = $category;
		$view->datalist = $select->fetchRows();
		$view->render('views/shopping/shop_list1.php');
	}

	public function doDetail()
	{
		$data = M('Shop')->getById((int)$this->_request->id);
		if (!$data->exists()) {
			throw new Suco_Controller_Dispatcher_Exception('Not found.');
		}

		$view = $this->_initView();
		$view->data = $data;
		$view->render('views/shopping/merchant_info.php');
	}
	//地图
	public function doMap() {
		$data = M('Shop')->getById((int)$this->_request->id);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'http://api.map.baidu.com/geocoder/v2/');
		$get_data = array(
			'address'=>$data['addr'],
			'output'=>'json',
			'ak'=>'ag0kleDQ9ytCYfEi2OBmlgDhHU1Oau9b',
			'callback'=>'showLocation'
		);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $get_data);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);

		$view = $this->_initView();
		$view->output = json_decode($output);
		$view->address = $data['addr'];
		$view->render('views/shopping/map.php');

	}
	//递归函数，用来得到用户添加的省市区
	public function hasmanyRegion($parent_id) {
		$area_ids =  M('Region')->select('id, parent_id, name, level')
			->where('id = ?', $parent_id)
			->order('id ASC, rank ASC');
		$area_id = $area_ids->fetchRows()->toArray();
		if($area_id[0]['name']) {
			$this->areas[] = $area_id[0];
			$this->hasmanyRegion($area_id[0]['parent_id']);
		}
	}
	public function doGetJson()
	{
		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('distinct(s.area_id), sc.name AS cate_name')//distinct去掉重复的字段
			->order('s.is_rec DESC, s.id DESC');
		$areas = $select->fetchRows();
		foreach($areas as $key=>$area) {
			if($area['area_id'] == '1') continue;
			$this->hasmanyRegion($area['area_id']);
			$this->doGetArrayID($this->areas[2]['id'],$this->areas[1]['id'],$this->areas[0]['id']);
			unset($this->areas);
		}
		$this->provinces_id = array_unique($this->provinces_id);
		$this->cities_id = array_unique($this->cities_id);
		foreach($this->provinces_id as $key=> $pro_id) {
			$pro = $this->hasOneRegion($pro_id);
			$this->doGetArrayPro($pro,$key);
		}
		$this->pro_city_region['pro'] = array_merge($this->pro_city_region['pro']);
		echo json_encode(array_merge($this->pro_city_region));


	}
	public function doGetJsonCity() {
		$pro_id  = $this->_request->pro_id;
		$cities = $this->hasManyRegionParents($pro_id);
		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('distinct(s.area_id), sc.name AS cate_name')//distinct去掉重复的字段
			->order('s.is_rec DESC, s.id DESC');
		$areas = $select->fetchRows();
		foreach($areas as $key=>$area) {
			if($area['area_id'] == '1') continue;
			$this->hasmanyRegion($area['area_id']);
			$this->doGetArrayID($this->areas[2]['id'],$this->areas[1]['id'],$this->areas[0]['id']);
			unset($this->areas);
		}
		$this->provinces_id = array_unique($this->provinces_id);
		$this->cities_id = array_unique($this->cities_id);
		foreach($cities as $key=>$city) {
			if(in_array($city['id'],$this->cities_id)) {
				$this->doGetArrayCity($city,$key);
			}
		}
		$this->city_region['city'] = array_merge($this->city_region['city']);
		echo json_encode($this->city_region);
	}
	public function doGetJsonRegion() {
		$city_id  = $this->_request->city_id;
		$region = $this->hasManyRegionParents($city_id);
		$select = M('Shop')->alias('s')
			->leftJoin(M('Shop_Category')->getTableName().' AS sc', 's.category_id = sc.id')
			->columns('distinct(s.area_id), sc.name AS cate_name')//distinct去掉重复的字段
			->order('s.is_rec DESC, s.id DESC');
		$areas = $select->fetchRows();
		foreach($areas as $key=>$area) {
			if($area['area_id'] == '1') continue;
			$this->hasmanyRegion($area['area_id']);
			$this->doGetArrayID($this->areas[2]['id'],$this->areas[1]['id'],$this->areas[0]['id']);
			unset($this->areas);
		}
		$this->provinces_id = array_unique($this->provinces_id);
		$this->cities_id = array_unique($this->cities_id);
		foreach($region as $key=>$reg) {
			if(in_array($reg['id'],$this->areas_id)) {
				$this->doGetArrayRegion($reg,$key);
			}
		}
		$this->region['region'] = array_merge($this->region['region']);
		echo json_encode($this->region);
	}
	public function doGetArrayPro($pro,$key) {
		$this->pro_city_region['pro'][$key]['id'][] = $pro[0]['id'];
		$this->pro_city_region['pro'][$key]['name'][] = $pro[0]['name'];
	}
	public function doGetArrayCity($city,$key) {
		$this->city_region['city'][$key]['id'][] = $city['id'];
		$this->city_region['city'][$key]['name'][] = $city['name'];
	}
	public function doGetArrayRegion($region,$key) {
		$this->region['region'][$key]['id'][] = $region['id'];
		$this->region['region'][$key]['name'][] = $region['name'];
	}
	public function doGetArrayID($provinces_id,$cities_id,$areas_id) {
		$this->provinces_id[] = $provinces_id;
		$this->cities_id[] = $cities_id;
		$this->areas_id[] = $areas_id;
	}
	public function doGetArray($province_name,$city,$province_id) {
		foreach($city as $citie_id) {
			if(in_array($citie_id['id'],$this->cities_id)) {
				$this->provinces[$province_name][$citie_id['id']] = $citie_id['name'];
				$this->provinces[$province_name][$citie_id['name']] = $citie_id['id'];
			}
		}
		$this->provinces[$province_name]['pro_id'] = $province_id;
	}
	public function doGetArrayArea($cities) {
		foreach($cities as $city) {
			if(in_array($city['id'],$this->areas_id)) {
				$this->cities[$city['parent_id']][$city['id']] = $city['name'];
			}
		}
	}
	//组合省市
	public function doProCityArea($parent_id,$area_id) {
		$province=  M('Region')->select('id, parent_id, name, level')
			->where('id = ?', $parent_id);
		$provinces = $province->fetchRows()->toArray();

		$cities =  M('Region')->select('id, parent_id, name, level')
			->where('parent_id = ?', $parent_id);
		$array_cities = $cities->fetchRows()->toArray();

		$this->doGetArray($provinces[0]['name'],$array_cities,$provinces[0]['id']);
		return $array_cities;
	}
	//组合区
	public function doGetArea($city_id) {
		$area=  M('Region')->select('id, parent_id, name, level')
			->where('id = ?', $city_id);
		$areas = $area->fetchRows()->toArray();
		$city=  M('Region')->select('id, parent_id, name, level')
			->where('parent_id = ?', $city_id);
		$cities = $city->fetchRows()->toArray();
		$this->doGetArrayArea($cities);
	}
	public function hasOneRegion($id) {
		$area_ids =  M('Region')->select('id, parent_id, name, level')
			->where('id = ?', $id)
			->order('id ASC, rank ASC');
		$area_id = $area_ids->fetchRows()->toArray();
		return $area_id;
	}
	public function hasManyRegionParents($parent_id) {
		$area_ids =  M('Region')->select('id, parent_id, name, level')
			->where('parent_id = ?', $parent_id)
			->order('id ASC, rank ASC');
		$area_id = $area_ids->fetchRows()->toArray();
		return $area_id;
	}
}