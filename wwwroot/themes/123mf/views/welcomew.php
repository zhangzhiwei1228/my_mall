<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
   	<?php include_once VIEWS.'inc/header_shop02.php'; ?>

	<?php
		unset($_SESSION['confirm_login']);;
		unset($_SESSION['confirm_login_url']);
	?>
    <div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=banr')?>';" class="n-recharge-sp  ssj">
		<p>八大 商城商品兑换</p>		
		<?php /*?><a href="<?=$this->url('default/goods')?>"></a>*/?>
		<a href="<?=$this->url('default/goods/page?t=banr')?>"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=banl')?>';" class="n-recharge-sp  ssj">
		<p>合作商家获取帮帮币</p>
		<?php /*?><a href="<?=$this->url('default/goods')?>"></a>*/?>
		<a href="<?=$this->url('default/goods/page?t=banl')?>"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('usercp/passport/login?success=1'); ?>';" class="n-recharge-sp  ssj">
		<p>会员创业财富升级</p>
		<?php /*?><a href="<?=$this->url('usercp/vip/apply')?>"></a>*/?>
		<a href="<?=$this->url('usercp/passport/login?success=1'); ?>"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('usercp/vip')?>';" class="n-recharge-sp  ssj">
		<p>会员激活</p>		
		<a href="<?=$this->url('usercp/vip')?>"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('usercp/vip/level?t=5'); ?>';" class="n-recharge-sp  ssj">
		<p>我是商家想要合作（客户爆满，生意升级）</p>		
		<?php /*<a href="<?=$this->url('usercp/vip/apply')?>"></a>*/?>
		<a href="<?=$this->url('usercp/vip/level?t=5'); ?>"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" class="n-recharge-sp js-share-btn ssj">
		<p>我要推荐会员获取更多积分</p>		
		<a href="javascript:;" class="js-share-btn"></a>
	</div>
	<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=jump')?>';" class="n-recharge-sp  ssj">
		<p>我要充值</p>		
		<a href="<?=$this->url('default/goods/page?t=jump')?>"></a>
	</div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
</body>
</html>