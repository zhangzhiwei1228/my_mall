<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic22.jpg'); ?> " width="100%"></div>
    <div class="jifen-step03 bgwhite">
	    <div class="step03-main ">
	    	<dl>
	    		<dt class="sure"></dt>
	    		<dd class="sure-info">已经给韦小宝(123456898)用户</dd>
	    		<dd class="sure-point">赠送免费积分<span>200</span>点</dd>
	    	</dl>
	    </div>
	</div>
	<div class="jifen-step02">
	    <a href="#" class=" btn sure">确  认</a>
	    <a href="#" class=" btn cancel">取  消</a>
    </div>
    <?php include_once VIEWS.'inc/footer_merchants.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
</script>
</body>
</html>




                       
