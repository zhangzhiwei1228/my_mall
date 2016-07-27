<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="payrecord">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			积分转换
		</div>
		<div class="n-payrecordsuccess">
			<div class="n-payrecordsuccess-head">
				<img src="<?php echo static_file('mobile/img/img-47.png'); ?> " alt="">
			</div>
			<div class="n-payrecordsuccess-te">转换成功</div>
			<div class="n-payrecordsuccess-input1">
				<input value="确定" type="submit">
			</div>
		</div>
	</div>
</body>
<script>
	$(function(){
		$(".n-payrecordsuccess").height($(window).height()-57);
	})
</script>
</html>