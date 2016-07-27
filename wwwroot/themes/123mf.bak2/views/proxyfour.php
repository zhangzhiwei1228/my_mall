<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body style="background:#ebebeb;">
<div class="n-proxy">
	<div class="n-personal-center-tit">
			<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			四星分销商管理员
	</div>
	<div style="background:#fff;" class="n-proxy-pic clear">
			<div class="n-dealer-head">
				<div class="n-dealer-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
			</div>
			<span>创业四星分销商管理员</span>
			<p><?=$this->user['username']?></p>
			<div class="n-proxy-sp">
				<a class="n-proxy-spa" href="<?=$this->url('passport/logout')?>">退出</a>
				<a class="n-proxy-spa1" href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a>
			</div>
	</div>
	<div style="background:#fff;" class="n-proxyfour-list">
		<ul class="clear">
			<li>
				<span class="n-dealer-span1">
					<p>我代理地区我下线的</p>
					<p>商家本月使用免费积分：</p>
				</span>
				<span class="n-dealer-span3">分</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['area']['seller']['t_credit']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">
					<p>我代理地区我下线的</p>
					<p>会员本月消费积分币：</p>
				</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['area']['member']['t_coin']?></span>
			</li>
		</ul>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的本月收益</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1"><?=(float)$this->bonus['amount']?></p>
				<p class="n-dealer-end-down-p2">元</p>
			</div>
		</div>
		<div class="n-h5"></div>
		<div class="n-h50-sp">
			下线：通过我发展下去的所有商家或会员
		</div>
	</div>
</div>

<div class="n-h56"></div>
<?php include_once VIEWS.'inc/footer_fourstar.php'; ?>

</body>
</html>