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
			<div class="n-payrecordfail-head">
				<img src="<?php echo static_file('mobile/img/img-48.png'); ?> " alt="">
			</div>
			<div class="n-payrecordfail-te">免费积分余额不足转换失败</div>
			<div class="n-payrecordfail-input1">
				<input style="float:left" value="确定" type="submit"><input style="float:right" value="确定" type="submit">
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