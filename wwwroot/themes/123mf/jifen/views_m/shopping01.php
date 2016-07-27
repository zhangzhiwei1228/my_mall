<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header_shopping.php'; ?>
    <p class="shop-login">会员登录</p>
    <form  class="w90 login-form" action="#" method="get">
    	<div class="account"><input type="text" placeholder="手机号码" /></div>
    	<div class="password"><input type="text" placeholder="登录密码" /></div>
    	<input class="submit" type="submit" value="立即登录" />
    </form>
    <div class="other-login-info"><a class="fl" href="#">注   册</a><a class="fr" href="#">忘记密码？</a><div class="clear"></div></div>.
    <div class="shop-login-footer">
    	<p class="other-intro"><a href="#">一升二首页</a><a href="#">帮助中心</a><a href="#">法律声明</a></p>
    	<p class="copyright">Copyright © 2015 浙江一升二网络服务有限公司   版权所有</p>
    </div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>

              
