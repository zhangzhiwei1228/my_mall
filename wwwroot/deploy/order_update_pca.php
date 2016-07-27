<?php

require_once '../runtime.php';
require_once '../func.php';

$orders = M('Order')->fetchRows();

foreach($orders as $row) {
	$area = M('Region')->getById((int)$row->area_id);
	$city = $area->getParent();
	$province = $city->getParent();

	$row->city_id = $city->id;
	$row->province_id = $province->id;
	$row->save();
}

echo Suco_Db::dump();