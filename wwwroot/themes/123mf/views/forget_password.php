<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<style>

	.forget-list ul{
		float: none;
	}
	.forget-list li{
		height: 45px;
		border-top: 1px solid #cdcdcd;
		border-bottom: 1px solid #cdcdcd;
		width: 94%;
		padding: 0px 3%;
		margin-top: 8px;
		background: #fff;
	}
	.forget-list li span{
		display: block;
		float: left;
		width: 76px;
		line-height: 45px;
		font-size: 14px;
	}
	.forget-list li input.text{
		float: left;
		height: 39px;
		padding: 3px;
		border: 0px none;
		font-size: 14px;
		width: 140px;
	}
	.forget-list li input.password{
		float: left;
		height: 39px;
		padding: 3px;
		border: 0px none;
		font-size: 14px;
	}
	.forget-list li button.send{
		padding: 0px 5px;
		height: 28px;
		float: right;
		background: #cdcdcd;
		border-radius: 3px;
		overflow: hidden;
		color: #666;
		text-align: center;
		line-height: 28px;
		margin-top: 8px;
	}


	.z-submit input{
		width: 94%;
		height: 50px;
		background: #b40000;
		color: #fff;
		border: 0px none;
		font-size: 16px;
		margin-left: 3%;
		margin-top: 10px;
	}
</style>
<body style="background:#f8f8f8;">
    <div class="forget-password">
    	<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			重置密码
		</div>
		<div class="errmsg"></div>
		<form class="forget-pwd" method="post">
		<div class="forget-list">
			<ul>
				<li>
					<span>*手机号</span>
					<input class="text" name="mobile" type="text">
				</li>
				<li>
					<span>*验证码</span>
					<input class="text" type="text" name="sms_code">
					<button type="button" class="send">获取验证码</button>
				</li>
				<li>
					<span>*密码</span>
					<input class="password" name="new_pass" type="password">
				</li>
				<li>
					<span>*确认密码</span>
					<input class="password" name="repwd" type="password">
				</li>
			</ul>
			<div class="z-submit">
				<input type="hidden" name="m" value="mobile" />
				<input value="确认" type="submit">
			</div>
		</div>
		</form>
    </div>
</body>
<?php
echo static_file('web/js/main.js');
?>
<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
<script type="text/javascript">
	seajs.use('/assets/js/validator/validator.sea.js', function(validator){
		var vd = validator('.forget-pwd', {
			showContainer: '.errmsg',
			skipErr: false,
			rules: {
				'[name=mobile]': { valid: 'required|mobile', errorText: '请输入您的手机号码|号码格式不正确' },
				'[name=sms_code]': { valid: 'required', errorText: '请输入短信验证码' },
				'[name=new_pass]': { valid: 'required|strlen', minlen:6, maxlen:16, errorText: '请填写新密码|密码必须是由6至16位的字母、数字或符号组合' },
				'[name=repwd]': { valid: 'required|equal', compare: '[name=new_pass]', errorText: '请再次输入密码|两次密码输入不一致' }
			}
		});
	});
</script>
<script>
	$('.send').on('click', function(){
		var el = $(this);
		var i = 30;
		var m = $('[name=mobile]').val();
		if (!m) {
			alert('请输入手机号码');
			return;
		}
		var patrn=/^(1|01)\d{10}$/;
		if( !patrn.test(m)){
			alert('手机号码格式不正确');
			return;
		}

		$.post('/misc.php?act=sms&m='+m+'&token=<?=md5('tts_'.date('YmdH'))?>');
		$(el).prop('disabled', true)
			.addClass('btn-disabled')
			.html('<span class="second" style="text-align: center;line-height: 0">'+i+'秒</span>');
		var intervalid = setInterval(function() {
			i--;
			$('.second').text(i+'秒');
			if (i == 0) {
				$(el).prop('disabled', false)
					.removeClass('btn-disabled')
					.html('重新获取验证码');
				clearInterval(intervalid);
			}
		}, 1000);
	});

</script>
</html>