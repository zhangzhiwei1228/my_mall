<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="zh-CN" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="author" content="杭州博采网络科技股份有限公司-高端网站建设-http://www.bocweb.cn" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>一升二APP</title>
<link href="<?php echo GLOBAL_URL ?>favicon.ico" rel="shortcut icon">
<script>
	var STATIC_URL = "<?php echo STATIC_URL ?>";
	var GLOBAL_URL = "<?php echo GLOBAL_URL ?>";
	var UPLOAD_URL = "<?php echo UPLOAD_URL ?>";
	var SITE_URL   = "<?php echo site_url() ?>";
</script>
<?php
	echo static_file('app/css/reset.css');
	echo static_file('app/css/style.css');

	echo static_file('app/js/jquery-1.11.3.js');
	//echo static_file('jquery.easing.1.3.js');
	//echo static_file('jquery.transit.js');
	//echo static_file('prefixfree.min.js');
	//echo static_file('html5.js');
	echo static_file('app/js/bocfe.js');
	//echo static_file('plug.preload.js');
	
	echo static_file('app/js/touch.js');
	//
	echo static_file('app/js/adaptive-version2.js');//erm js
?>
<script type="text/javascript">
    window['adaptive'].desinWidth = 750;
    window['adaptive'].init();
</script>