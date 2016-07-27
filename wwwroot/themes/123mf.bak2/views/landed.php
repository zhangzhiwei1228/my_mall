<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<?php include_once VIEWS.'inc/header01.php'; ?>
	<div class="n-landed">
		<div class="n-landed-tit">
			分销商系统登录
		</div>
		<form class="n-landed-input" action="<?=$this->url('/passport/login')?>" method="post">
			<div class="n-landed-input-box">
				<div class="m-box">
					<img class="n-landed-input-box-img1" src="<?php echo static_file('mobile/img/img-58.png'); ?> " alt="">
				</div>
				<input name="username" value="" placeholder="请输入登录账号" type="text">
				
			</div>
			<div class="n-landed-input-box">
				<div class="m-box">
					<img class="n-landed-input-box-img2" src="<?php echo static_file('mobile/img/img-59.png'); ?> " alt="">
				</div>
				<input name="password" value="" placeholder="登录密码" type="password">
				
				<a href="<?=$this->url('passport/forget_password')?>">忘记密码</a>
			</div>
			<div class="m-two">
				<input type="text" name="verify">
				<img src="/misc.php?act=verify" id="login_verify_code">
			</div>
			<input value="立即登录" class="j-input" type="submit">
		</form>
	</div>
</body>
</html>
