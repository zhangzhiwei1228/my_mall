<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header_login.php'; ?>
    <div style="background:url(<?php echo static_file('web/img/img-43.jpg'); ?> ) no-repeat center" class="log-in">
	    <div class="w1190">
	    	<div class="log-in-box">
	    		<div class="log-in-boxn">
	    			<h2>会员登录</h2>
	    			<form action="<?php echo site_url('log_in/log_in_after'); ?> ">
	    				<input placeholder="用户名" style="background:url(<?php echo static_file('web/img/img-21.png'); ?> ) no-repeat 10px center;" class="input-a" type="text">
		    			<input placeholder="密&nbsp;&nbsp;&nbsp;码" style="background:url(<?php echo static_file('web/img/img-22.png'); ?> ) no-repeat 12px center;" class="input-a"type="password">
		    			<input value="登     录" class="input-b" type="submit">
		    			<div class="two-a">
		    				<a href="<?php echo site_url('log_in/reg'); ?> ">免费注册</a>
		    				<a href="">忘记密码</a>
		    			</div>
	    			</form>
	    		</div>
	    	</div>
	    </div>
    </div>
    <?php include_once VIEWS.'inc/footer_login.php'; ?>
</body>
