<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div style="background:#d5d5d5;" class="recharge">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		申请升级分销商确认
	</div>

	<div class="n-recharge-pic clear">
		<div class="n-recharge-head">
			<div class="n-recharge-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
		</div>
		<span>我的昵称</span>
		<p>手机号：12568964321</p>
	</div>

	<div class="n-recharge-sp ssj">
		<p>现有级别</p>		
		<a href=""></a>
		<span>会员</span>
	</div>
	<div class="n-recharge-sp  ssj">
		<p>升级级别</p>		
		<a href=""></a>
		<span>一星</span>
	</div>
	<div class="n-recharge-sp  ssj">
		<p>性别</p>		
		<a href=""></a>
		<span>男</span>
	</div>
	<div class="n-h5"></div>
	
	<div class="ssj-input">
		<p>输入手机号</p>
		<input type="text">
	</div>
	<div class="ssj-input">
		<p>输入验证码</p>
		<input type="text">
	</div>
	<div class="ssj-big-inp">
		<input value="立即生成" type="submit">
		<input value="取消" type="reset">
	</div>

</div>
</body>
<script>
$(function(){
	$(".ssj-big-inp input").click(function(){
		$(this).css("background","#ff6600");
	});
})
</script>
</html>