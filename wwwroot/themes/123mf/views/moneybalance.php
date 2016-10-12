<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body style="background:#ebebeb">
<div class="n-moneybalance">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		我的余额
	</div>
	<div class="n-recharge-pic clear">
		<div class="n-recharge-head">
			<div class="n-recharge-head-info"><img src="<?=$this->baseUrl($this->user['avatar'])?>?> " alt=""></div>
		</div>
		<span><?=$this->user['nickname']?></span>
		<p>手机号：<?=$this->user['mobile']?></p>
	</div>
	<div class="n-balance">
		<ul class="clear">
			<li>
				<img src="<?php echo static_file('mobile/img/img-27.png'); ?> " alt="">
				<span><a href="">我的免费积分:</a></span>
				<a class="n-list-end1" href="">分</a>
				<p><?=$this->user['credit']?></p>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-49.png'); ?> " alt="">
				<span><a href="">我的积分币:</a></span>
				<a class="n-list-end1" href="">币</a>
				<p><?=$this->user['credit_coin']?></p>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-49.png'); ?> " alt="">
				<span><a href="">我的抵用金:</a></span>
				<a class="n-list-end1" href="">金</a>
				<p><?=$this->user['worth_gold']?></p>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-50.png'); */?> " alt="">
				<span><a href="">我的抵用券：</a></span>
				<a class="n-list-end1" href="">券</a>
				<p>0</p>
			</li>
			<li>
				<img src="<?php /*echo static_file('mobile/img/img-51.png'); */?> " alt="">
				<span><a href="">我的商城现金：</a></span>
				<a class="n-list-end1" href="">元</a>
				<p><?/*=$this->user['balance']*/?></p>
			</li>-->
		</ul>
		<div class="n-h5"></div>
		<ul class="clear">
			<li>
				<img src="<?php echo static_file('mobile/img/img-52.png'); ?> " alt="">
				<span><a href="<?=$this->url('./credit?t=credit_coin')?>">我充值积分币记录</a></span>
				<a class="n-list-end" href=""></a>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-53.png'); */?> " alt="">
				<span><a href="<?php /*echo site_url('rechargerecord'); */?> ">我充值抵用券记录</a></span>
				<a class="n-list-end" href=""></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('mobile/img/img-54.png'); ?> " alt="">
				<span><a href="<?=$this->url('./credit?t=credit')?>">获得免费积分记录</a></span>
				<a class="n-list-end" href=""></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-54.png'); ?> " alt="">
				<span><a href="<?=$this->url('./credit?t=worth_gold')?>">抵用金记录</a></span>
				<a class="n-list-end" href=""></a>
			</li>
		</ul>
		<div class="n-h108"></div>
	</div>
</div>
</body>
</html>