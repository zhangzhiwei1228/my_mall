<?php 

// include 'config.php';

# 全局URL路径
// 主域名 保留最后的 / 
define('GLOBAL_URL'  , 'http://'.$_SERVER['HTTP_HOST'].'/jifen/');   // 修改bocms-views 为自己的项目名称
define('STATIC_URL'  , GLOBAL_URL.'static/');
define('UPLOAD_URL'  , GLOBAL_URL.'upload/');
define('ADMINER_URL' , GLOBAL_URL.'adminer/');
define('MOBILE_URL'  , GLOBAL_URL.'mobile/');

// // 快捷提供给JS
define('IMG_URL'     , STATIC_URL.'img/');

# 引用绝对路径PATH定义
define('ROOT'        , dirname(__FILE__).'/');
define('CI_PATH'     , ROOT.'ci/');
define('STATIC_PATH' , ROOT.'static/');
define('UPLOAD_PATH' , ROOT.'upload/');

/*---------------------end config--------------------------*/

define("BASEPATH", dirname(__FILE__));
define('ENVIRONMENT', 'development');
define('EXT', '.php');

// 根据此文件的位置修改模板路径问题
define('VIEWS', dirname(__FILE__).'/views_m/'); 
$path_info = '';
if ( isset($_SERVER['PATH_INFO'])) {
	$path_info = $_SERVER['PATH_INFO'];
}else if(isset($_SERVER['ORIG_PATH_INFO'])){
	$path_info = $_SERVER['ORIG_PATH_INFO'];
}

$url = trim(rstr_trim($path_info,'/index.php'),'/');
$uri = explode('/', $url);

# 路由机制，参考文档

$reg = array();
if (!$url) {
	$view_file = 'welcome.php';
	$url_base = '/';
}else{

	// 数字之后皆为参数
	// 参数容器
	$reg = array();
	// 数字的位置
	$num_index = 0;
	foreach ($uri as $i=> $v) {
		if (is_numeric($v)) {
			$num_index = $i;
			break;
		}
	}
	if ($num_index) {
		$reg = array_slice($uri, $num_index,count($uri)+1);
		$uri = array_slice($uri,0,$num_index);
	}

	$view_file = implode('/',$uri).EXT;
	$url_base = implode('/',$uri);
}

if ($url == 'index') {
	$view_file = 'welcome.php';
}

if ($view_file) {
	if (!file_exists(VIEWS.$view_file)) {
		$view_file = "404.php";
	}
}

$header =array(
	'title'=>'标题',
	'tags'=> '标签',
	'description' => '简介'
	);

// 输出 page,url_base,view_file
include_once VIEWS.$view_file;

?>

<?php # 函数支持

// 静态文件支持
function static_file($file,$rurl=false){

	if (!$file) {
		return FALSE;
	}

		$type = false;
		if (strrpos($file,'.js')) {
			$filemin = str_replace('.js','.min.js',$file);
			$type = 'js/';
		}else if(strrpos($file,'.css')){
			$filemin = str_replace('.css','.min.css',$file);
			$type = 'css/';
		}

		$url = false;

		if (is_file(STATIC_PATH.$file)) {
			$url = STATIC_URL.$file;
		}

		if ($type != false) {
			if (file_exists(STATIC_PATH.$type.$file)) {
				$url  = STATIC_URL.$type.$file; 
			}
			// $url  = file_exists(STATIC_PATH.$type.$file) ? STATIC_URL.$type.$file : '';
			if (defined('ENVIRONMENT') and ENVIRONMENT != "development" ) {
				if (file_exists(STATIC_PATH.$type.$filemin)) {
					$url = STATIC_URL.$type.$filemin; 
				}
				if (!$url and file_exists(STATIC_PATH.$file)) {
					$url = STATIC_URL.$file;
				}
			}
		}

		if (!$url) {
			return '<!-- static file error: '.$file.' findout. --><script>console.error("'.$file.' is not here!")</script>';
		}else{
			if ( defined('STATIC_V') and STATIC_V) {
				$url.='?v='.STATIC_V;
			}
			if ( defined('ENVIRONMENT') and ENVIRONMENT == "development" ) {
				$url.='?t='.time();
			}
		}

		if ($rurl === true or $rurl and  !in_array($rurl,array('screen','print'))) {
			return $url;
		}else{
			if ($type == 'js/') {
				return '<script src="'.$url.'" type="text/javascript" charset="utf-8"></script>';
			}else if($type == 'css/'){
				$media = in_array($rurl,array('screen','print'))? $rurl:'screen';
				return '<link rel="stylesheet" href="'.$url.'" type="text/css" media="'.$media.'" charset="utf-8">';
			}else{
				return $url;
			}
		}
}	
// FOR 模板开发
function site_url($uri = ''){
	return $uri = $_SERVER['SCRIPT_NAME'].'/'. trim($uri,'/');
}

/**
 * @brief json数据输出
 * @param $data array / string
 * @return echo  
 */
function json_echo($data) 
{
	header('Vary: Accept');
	if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
		header('Content-type: application/json');
	} else {
		header('Content-type: text/plain');
	}
	// header('Content-type: application/json');
	if (is_array($data)) {
		echo json_encode($data);
	}else{
		echo $data;
	}
}

// 从右侧清除预定义字符
function rstr_trim($str, $remove=null) 
{ 
    $str    = (string)$str; 
    $remove = (string)$remove;    

    if(empty($remove)) 
    { 
        return rtrim($str); 
    } 

    $len = strlen($remove); 
    $offset = strlen($str)-$len; 
    while($offset > 0 && $offset == strpos($str, $remove, $offset)) 
    { 
        $str = substr($str, 0, $offset); 
        $offset = strlen($str)-$len; 
    } 

    return rtrim($str); 
} 

?>