<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-payway">
	<div class="n-personal-center-tit">
		<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		我要提现
	</div>
	<div class="n-payway-tit">
		<p>选择支付方式 :</p>
	</div>
	<div class="n-payway-list">
		<ul>
			<li><a href=""><img src="<?php echo static_file('mobile/img/img-42.png'); ?> " alt=""></a><a class="n-two-b" href=""></a></li>
			<li><a href=""><img src="<?php echo static_file('mobile/img/img-43.png'); ?> " alt=""></a><a class="n-two-b" href=""></a></li>
		</ul>
	</div>
</div>
</body>
<script>
	$('.payway').height($(window).height()-0);
</script>
</html>