<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<form class="n-password" method="post">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			修改密码
		</div>
		<div class="n-password-box">
			<input type="password" name="old_pass" placeholder="原始密码" type="text">
			<input type="password" name="new_pass" placeholder="新密码" type="text">
			<input type="password" name="chk_pass" placeholder="确认密码" type="text">
		</div>
		<div class="errmsg" style="padding: 15px; color:red"></div>
		<div class="n-password-submit">
			<input value="确认修改" type="submit">
		</div>
	</form>

	<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
	<script>
	seajs.use('/assets/js/validator/validator.sea.js', function(validator){
		validator('.n-password', {
			showContainer: '.errmsg',
			skipErr: false,
			rules: {
				'[name=old_pass]': { valid: 'required', errorText: '请输入当前密码' },
				'[name=new_pass]': { valid: 'required|strlen', minlen:6, maxlen:16, errorText: '请填写新密码|密码必须是由6至16位的字母、数字或符号组合' },
				'[name=chk_pass]': { valid: 'required|equal', compare: '[name=new_pass]', errorText: '请再次输入密码|两次输入密码不一致' }
			}
		});
	});
	</script>
</body>
</html>