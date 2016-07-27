<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="<?=$this->url('usercp')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		会员激活
	</div>
	<div class="n-member">
		<div class="n-member-banner">
			<img src="<?php echo static_file('mobile/img/img-19.jpg'); ?> " alt="">
		</div>
		<div class="n-member-te">
		<br>
			<p style="color:#333;">会员激活后可享受兑换或购买商品服务！</p>
			<br>
			<p style="color:#b40000;font-size:14px;line-height:26px;">激活说明：</p>
			<p style="color:#333;">会员第一次兑换或购买物品必须先激活，成为有效会员（激活系统服务年费为20元，同时获得10-20免费积分）。</p>
		</div>
		<form class="n-member-input" method="post" action="<?=$this->url('./active')?>">
			<input type="hidden" name="type" value="vip0_active">
			<input type="hidden" name="return_url" value="<?=$this->url('&')?>">
			<?php if ($this->user->is_vip) { ?>
			<input value="已激活" class="n-member-input1" type="button">
			<?php } else { ?>
			<input value="立即激活(¥20)" class="n-member-input1" type="submit">
			<?php } ?>
		</form>
	</div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
</body>
</html>
