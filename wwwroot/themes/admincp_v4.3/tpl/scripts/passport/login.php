<?php if(!defined('APP_KEY')) { exit('Access Denied'); }?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?=$this->head()
		->setTitle($this->setting['sitename'])
		->addMeta(null, 'text/html; charset=utf-8', 'Content-Type')
		->addLink(array('./css/all.min.css'), 'text/css')
		->addLink('./js/jquery-1.10.2.min.js', 'text/javascript');
	?>
<script src="./js/seajs/sea.js" data-config="./js/seajs/config.js"></script>
</head>
<body style="background: radial-gradient(ellipse at center, rgba(223,229,233,1) 2%,rgba(223,229,233,1) 2%,rgba(178,192,202,1) 100%);">
	<form role="controls" method="post" class="form sui-login-form">
		<div class="sui-login-logo">
			<img src="./img/logo-b.png" />
			<big>『系统管理员登录』</big>
		</div>
		<div class="input-group">
			<input type="text" name="username" autocomplete="off" disableautocomplete class="form-control" placeholder="用户名" />
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
		</div>
		<div class="input-group">
			<input type="password" name="password" class="form-control" placeholder="登录密码" />
			<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
		</div>
		<?php if ($this->_request->error) { ?>
		<div class="alert alert-danger" style="padding:0 10px; line-height:30px">
		<?=$this->_request->error?>
		</div>
		<?php } ?>
		<button type="submit" class="btn btn-primary btn-block">立即登陆</button>
		<div class="help-block">
			<strong>为保证您的帐户安全请注意以下事项</strong>
			<ol>
				<li>请尽量避免在公共计算机上登陆本系统。</li>
				<li>离开系统时，请选择退出按键，切勿直接关闭浏览器。</li>
			</ol>
		</div>
	</form>
</body>
</html>