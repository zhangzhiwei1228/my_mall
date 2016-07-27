<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-personal-center">
	<div class="n-personal-center-tit">
		<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		个人中心
	</div>
	<div class="n-pic">
		<div class="n-head-pic"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
		<span>我的昵称</span>
		<p>手机号：12568964321</p>
	</div>
	<div class="n-personal-center-list">
		<div class="n-h44 end-n-h44"><a href="">解绑手机</a></div>
		<ul class="clear">
			<li>
				<span class="sspan"><a href="javascript:;">解绑手机</a></span>
				<input class="gg-input" type="text">
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">验证码</a></span>
				<input style="width:35%" placeholder="输入验证码" class="gg-input" type="text">
				<input value="发送验证码" class="b-phone-sub" type="submit">
			</li>
		</ul>
	</div>
	<div style="height:50px;width:100%;overflow: hidden;" class="h-50"></div>
</div>
<div class="tt-end">保 存</div>
</body>
</html>