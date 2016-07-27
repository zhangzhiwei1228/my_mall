<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="payrecord">
		<div class="n-personal-center-tit">
			<a href="<?=$this->url('/usercp')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			积分转换
		</div>
		<div class="n-recharge-pic clear">
			<div class="n-recharge-head">
				<div class="n-recharge-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
			</div>
			<span><?=$this->user['nickname']?></span>
			<p>手机号：<?=$this->user['mobile']?></p>
		</div>
		<div class="n-recharge-pic-te">
			<p style="color:#b40000;font-size:14px;font-weight:bold;">转换说明：</p>
			<p style="color:#555;">积分可按<font style="color:#b40000;">3 : 1</font>转换成积分币，<font style="color:#b40000;">10000</font>积分起转按<font style="color:#b40000;">1000</font>整数倍增加。例<font style="color:#b40000;">12000</font>积分可转换成<font style="color:#b40000;">6000</font>积分币。</p>
		</div>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的免费积分余额</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1">10000</p>
				<p class="n-dealer-end-down-p3">分</p>
			</div>
		</div>
		<div class="n-h5"></div>
		<div class="n-recharge-sp">
			<p>输入转换免费积分</p>		
			<a href=""></a>
			<input type="text">
		</div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">获得积分币</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1">500</p>
				<p class="n-dealer-end-down-p3">币</p>
			</div>
		</div>
		<div style="margin-top:42px;" class="n-recharge-sub">
		<input class="n-recharge-end-sub" value="我要转换" type="submit">
	</div>
	</div>
</body>
</html>