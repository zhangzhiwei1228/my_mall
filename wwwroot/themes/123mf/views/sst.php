<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div style="background:#d5d5d5;" class="recharge">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		充值跳转
	</div>
	<div class="sst">
		<div class="sst-box">
			<input value="免费积分转换" type="submit">
			<input value="抵用卷转换" type="submit">
		</div>
	</div>
</div>
</body>
<script>
	$(function(){
		$(".sst").height($(window).height() - 57);
		
		$(".sst-box input").click(function(){
			$(this).css("background","#ff6600");
		});
	})
</script>
</html>