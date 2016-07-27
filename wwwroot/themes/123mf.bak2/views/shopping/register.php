<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shopping.php'; ?>
    <p class="register-title">会员注册</p>
    <form class="register-form w90" method="post">
		<div class="errmsg"></div>
		<input class="input" type="text" name="mobile" placeholder="手机号码"><br />
		<div class="code-box"><input class="code" name="sms_code" type="text" placeholder="请输入验证码">
			<button type="button" class="send">发送验证码</button></div>
		<input class="input" type="password" name="password" placeholder=" 请输入6-12位密码"><br />
		<input class="input" type="password" name="checkpass" placeholder="请再次输入密码"><br />
		<input  style="margin-bottom:0;" class="input" name="invite_mobile" type="text" placeholder=" 请输入邀请人号码"><br />
		<p class="gain">（填写获得20会员积分）</p>

		<div class="register-intro">
			<p>
				邀请码说明(<span>分2种情况</span>)：
				A；无邀请码注册，会员激活后获<span>10</span>积分。
				<br>
				B；有邀请码注册，会员激活后获<span>20</span>积分，同时邀请码推荐人也获<span>20</span>积分（邀请码就是你推荐人的手机号，你注册成功后，用你的邀请码推荐朋友，注册成功激活后，你就可以得到<span>20</span>积分，你朋友也得<span>20</span>积分，以此类推）。
			</p>
		</div>
		<input class="submit" type="submit" value="注     册">
	</form>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	// $('.register-form input:last').css('marginBottom','0');
</script>
<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
<script type="text/javascript">
seajs.use('/assets/js/validator/validator.sea.js', function(validator){
	var vd = validator('.register-form', {
		showContainer: '.errmsg',
		skipErr: false,
		rules: {
			'[name=mobile]': { valid: 'required|mobile', errorText: '请输入您的手机号码|号码格式不正确' },
			//'[name=sms_code]': { valid: 'required', errorText: '请输入短信验证码' },
			'[name=password]': { valid: 'required|strlen', minlen:6, maxlen:16, errorText: '请填写新密码|密码必须是由6至16位的字母、数字或符号组合' },
			'[name=checkpass]': { valid: 'required|equal', compare: '[name=password]', errorText: '请两次输入密码|两次密码输入不一致' }
		}
	});
});

$('.send').on('click', function(){
	var el = $(this);
	var i = 30;
	var m = $('[name=mobile]').val();
	if (!m) {
		alert('请输入手机号码');
		return;
	}

	$.post('/misc.php?act=sms&m='+m+'&token=<?=md5('tts_'.date('YmdH'))?>');
	$(el).prop('disabled', true)
		.addClass('btn-disabled')
		.html('重新发送 (<span class="second">'+i+'</span>)');
	var intervalid = setInterval(function() {
		i--;
		$('.second').text(i);
		if (i == 0) {
			$(el).prop('disabled', false)
				.removeClass('btn-disabled')
				.html('发送验证码');
			clearInterval(intervalid);  
		}  
	}, 1000);
})

</script>
</body>
</html>




