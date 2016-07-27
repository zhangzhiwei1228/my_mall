<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shopping.php'; ?>
    <p class="register-title">会员注册</p>
    <form class="register-form w90" action="#" method="get">
		<input class="input" type="text" placeholder="手机号码"><br />
		<div class="code-box"><input class="code" type="text" placeholder="请输入验证码"><input class="send" type="submit" value="发送验证码"></div>
		<input class="input" type="text" placeholder=" 请输入6-12位密码"><br />
		<input class="input" type="text" placeholder="请再次输入密码"><br />
		<input  style="margin-bottom:0;" class="input" type="text" placeholder=" 请输入邀请人号码"><br />
		<p class="gain">（填写获得20会员积分）</p>

		<div class="register-intro">
			<p>
				邀请码说明(<span>分2种情况</span>)：
				A；无邀请码注册，会员激活后获<span>10</span>积分。
				<br>
				B；有邀请码注册，会员激活后获<span>20</span>积分，同时邀请码推荐人也获<span>20</span>积分（邀请码就是你推荐人的手机号，你注册成功后，用你的邀请码推荐朋友，注册成功激活后，你就可以得到<span>20</span>积分，你朋友也得<span>20</span>积分，以此类推）。
			</p>
		</div>
		<input class="submit" type="submit" value=" 注     册">
	</form>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	// $('.register-form input:last').css('marginBottom','0');
</script>
</body>
</html>







