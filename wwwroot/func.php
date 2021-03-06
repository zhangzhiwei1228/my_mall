<?php

function H($name)
{
	$args = func_get_args(); array_shift($args);
	$helper = Suco_Helper::factory($name);
	return call_user_func_array(array($helper, 'callback'), array($args));
}

function M($model)
{
	return Suco_Model::factory($model);
}

function T($key)
{
	return Suco_Locale::instance()->tranlate($key);
}

function redirect($url)
{
	return '<script>window.location = "'.$url.'"</script>';
}

/**
 * 返回指定尺寸的图片路径
 * @param string $src 原图路径
 * @param string $size 大小尺寸
 * @return string
 */
function getImage($src, $size = null)
{
	if (!$src) { return './img/nopic.png';	}

	$rewrite = M('Setting')->get('rewrite_enabled');

	$ext = pathinfo($src);
	$ext = strtolower($ext['extension']);

	$imgSrc = str_replace('.'.$ext, '', $src).'_'.$size.'.'.$ext;

	if ($rewrite) {
		return $imgSrc;
	} else {
		return '/image.php?url='.urlencode($imgSrc);
	}
}

/**
 * utf8字符转Unicode字符
 * @param string $char 要转换的单字符
 * @return void
 */
function utf8ToUnicode($char)
{
	$char = strtolower($char);
	switch(strlen($char))
	{
		case 1:
			return ord($char);
		case 2:
			$n = (ord($char[0]) & 0x3f) << 6;
			$n += ord($char[1]) & 0x3f;
			return $n;
		case 3:
			$n = (ord($char[0]) & 0x1f) << 12;
			$n += (ord($char[1]) & 0x3f) << 6;
			$n += ord($char[2]) & 0x3f;
			return $n;
		case 4:
			$n = (ord($char[0]) & 0x0f) << 18;
			$n += (ord($char[1]) & 0x3f) << 12;
			$n += (ord($char[2]) & 0x3f) << 6;
			$n += ord($char[3]) & 0x3f;
			return $n;
	}
}

/**
 * utf8字符串分隔为unicode字符串
 * @param string $str 要转换的字符串
 * @param string $pre 前缀
 * @return string
 */
function segment($str,$pre = '')
{
	$arr = array();
	$str_len = mb_strlen($str,'UTF-8');
	for($i = 0;$i < $str_len; $i++)
	{
		$s = mb_substr($str,$i,1,'UTF-8');
		if($s != ' ' && $s != '　')
		{
			$arr[] = $pre.'ux'.utf8ToUnicode($s);
		}
	}

	$arr = array_unique($arr);

	return implode(' ',$arr);
}

/**
 * 单位自动转换
 * @param float $size 要转换数值 byte
 * @return string
 */
function convert($size)
{
	$unit=array('b','kb','mb','gb','tb','pb');
	return @round($size/pow(1024,($i=floor(log($size,1024)))),4).' '.$unit[$i]; 
}

function decodeUnicode($str)
{
	return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
		create_function(
			'$matches',
			'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
		), $str);
}

function getUploadFileSize()
{
	//获取服务器上传限制
	$fileSizeLimit = ini_get('upload_max_filesize');
	$size = (int)$fileSizeLimit;
	$unit = str_replace($size,'',$fileSizeLimit);
	switch($unit) {
		case 'G':
			$fileSizeLimit = $fileSizeLimit * 1024 * 1024 * 1024;
			break;
		case 'M':
			$fileSizeLimit = $fileSizeLimit * 1024 * 1024;
			break;
		case 'K':
			$fileSizeLimit = $fileSizeLimit * 1024;
			break;
	}

	return $fileSizeLimit;
}

function getMicrotime()
{
	list($usec, $sec) = explode(' ', microtime()); 
	return ((float)$usec + (float)$sec);
}

/**
 * 返回一组GUID编码（唯一）
 * @param float $size 要转换数值 byte
 * @return string
 */
function getGuid() {
	$charid = strtoupper(md5(uniqid(mt_rand(), true)));
	$hyphen = chr(45);// "-"
	$uuid = substr($charid, 0, 8).$hyphen
	.substr($charid, 8, 4).$hyphen
	.substr($charid,12, 4).$hyphen
	.substr($charid,16, 4).$hyphen
	.substr($charid,20,12);
	return $uuid;
}

function cny($num){ 
	$c1 = "零壹贰叁肆伍陆柒捌玖"; 
	$c2 = "分角元拾佰仟万拾佰仟亿"; 
	$num = round($num, 2); 
	$num = $num * 100; 
	if (strlen($num) > 10) { 
		return "数据太长，没有这么大的钱吧，检查下"; 
	} 
	$i = 0; 
	$c = ""; 
	while (1) { 
		if ($i == 0) { 
			$n = substr($num, strlen($num)-1, 1); 
		} else { 
			$n = $num % 10; 
		} 
		$p1 = substr($c1, 3 * $n, 3); 
		$p2 = substr($c2, 3 * $i, 3); 
		if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) { 
			$c = $p1 . $p2 . $c; 
		} else { 
			$c = $p1 . $c; 
		} 
		$i = $i + 1; 
		$num = $num / 10; 
		$num = (int)$num; 
		if ($num == 0) { 
			break; 
		} 
	} 
	$j = 0; 
	$slen = strlen($c); 
	while ($j < $slen) { 
		$m = substr($c, $j, 6); 
		if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') { 
			$left = substr($c, 0, $j); 
			$right = substr($c, $j + 3); 
			$c = $left . $right; 
			$j = $j-3; 
			$slen = $slen-3; 
		} 
		$j = $j + 3; 
	} 

	if (substr($c, strlen($c)-3, 3) == '零') { 
		$c = substr($c, 0, strlen($c)-3); 
	} 
	if (empty($c)) { 
		return "零元整"; 
	}else{ 
		return $c . "整"; 
	}
}
/**
 * 验证邮箱
 * @param  string  $email 邮箱地址
 * @return boolean
 */
function is_mail($email) {
	// filter_var ($email, FILTER_VALIDATE_EMAIL ) ?  FALSE : TRUE;
	return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $email)) ? FALSE : TRUE;
}

/**
 * 是否是手机号码
 * @param string $phone 手机号码
 * @return boolean
 */
function is_mobile($phone) {
	if (strlen($phone) !== 11) {
		return FALSE;
	}
	return (bool) (!preg_match('/^[(86)|0]?(13\d{9})|(15\d{9})|(17\d{9})|(18\d{9})$/', $phone)) ? FALSE : TRUE;
}

/**
 * @param $addr
 * @return array
 * 根据地址获取经纬度
 */
function get_lng_lat($addr) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'http://api.map.baidu.com/geocoder/v2/');
	$get_data = array(
		'address'=>$addr,
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
	$shop_addr = json_decode($output);
	$lng = $lat = '';
	if(!$shop_addr->status) {
		$lng = $shop_addr->result->location->lng;
		$lat = $shop_addr->result->location->lat;
	}

	return $lng && $lat ? array('lng'=>$lng,'lat'=>$lat): '';
}

/**
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @return float
 * 根据经纬度获取距离
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{
	$earthRadius = 6367000; //approximate radius of earth in meters
	$lat1 = ($lat1 * pi() ) / 180;
	$lng1 = ($lng1 * pi() ) / 180;

	$lat2 = ($lat2 * pi() ) / 180;
	$lng2 = ($lng2 * pi() ) / 180;
	$calcLongitude = $lng2 - $lng1;
	$calcLatitude = $lat2 - $lat1;
	$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	$calculatedDistance = $earthRadius * $stepTwo;
	$distance = round($calculatedDistance);
	if($distance < 1000)
		return $distance.'m';
	return number_format($distance/pow(10,3),1) . 'Km';
	//return round($calculatedDistance);
}
/**
 * 获取ip
 */
function get_ip()
{
	$cip = (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != "") ? $_SERVER['HTTP_CLIENT_IP'] : FALSE;
	$rip = (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != "") ? $_SERVER['REMOTE_ADDR'] : FALSE;
	$fip = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != "") ? $_SERVER['HTTP_X_FORWARDED_FOR'] : FALSE;

	if ($cip && $rip)	$IP = $cip;
	elseif ($rip)		$IP = $rip;
	elseif ($cip)		$IP = $cip;
	elseif ($fip)		$IP = $fip;

	if (strpos($IP, ',') !== FALSE)
	{
		$x = explode(',', $IP);
		$IP = end($x);
	}

	if ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $IP))
	{
		$IP = '0.0.0.0';
	}

	unset($cip);
	unset($rip);
	unset($fip);

	return $IP;
}

/**
 * @param $data
 * @return string
 * 拼接sql
 */
function get_sql($data) {
	$query = '';
	$count = count($data);
	$i = 0;
	foreach($data as $k => $v){
		if($i < $count -1) {
			$query .= $k.' = '.$v.' and ';
		} else {
			$query .= $k.' = '.$v;
		}
		$i++;
	}
	return $query;
}
