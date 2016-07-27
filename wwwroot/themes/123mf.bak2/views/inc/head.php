<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="author" content="杭州博采网络科技股份有限公司-高端网站建设-http://www.bocweb.cn" />
<meta name="renderer" content="webkit">
<link href="<?php echo GLOBAL_URL ?>favicon.ico" rel="shortcut icon">
<script>
	var STATIC_URL = "<?php echo STATIC_URL ?>";
	var GLOBAL_URL = "<?php echo GLOBAL_URL ?>";
	var UPLOAD_URL = "<?php echo UPLOAD_URL ?>" ;
</script>
<?php
	
	echo static_file('jQuery.js');
	echo static_file('jquery.easing.1.3.js');
	echo static_file('prefixfree.min.js');
	echo static_file('jquery.SuperSlide.2.1.1.js');
	echo static_file('jquery.scrollLoading.js');
	echo static_file('m/css/swiper.min.css');
	echo static_file('m/css/swiper.min.css');
	echo static_file('m/css/reset.css');
	echo static_file('m/js/swiper.min.js'); 
	
	//web
	echo static_file('mobile/css/normalize.css');
	echo static_file('mobile/css/mystyle.css');
	echo static_file('mobile/css/zxx.lib.css');
	echo static_file('mobile/css/style.css');
	echo static_file('mobile/css/reset.css');
	
?>
<!--[if IE 6]>
	<?php
		echo static_file('IE6PNG.js');
	?>
	<script type="text/javascript">
		IE6PNG.fix('*,.png:hover,.png');
	</script>
<![endif]-->