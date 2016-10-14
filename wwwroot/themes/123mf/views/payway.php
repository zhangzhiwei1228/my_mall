<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
<style>
	.payment {
		background-size: auto 100%;
	}
	.payment a img {
		display: block;
		height: 60px;

	}
</style>
</head>

<body>
<div class="n-payway">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		在线支付
	</div>
	<div class="n-payway-tit">

		<p style="margin-top:10px;">各位会员：因阿里支付宝与腾讯微信支付在相关的支付方面有着相互抵制，在打开平台的入口不一样，支付的工具不一样，所以当你用其中的一个无法支付时，请你用另外一个支付，让你带来不便，敬请凉解。</p>

	</div>
	<?php if(isset($this->data) && $this->data['type'] == 'hybrid' || $this->data['type'] == 'cash' || $this->data['type'] == 'single' ) {?>
		<div class=" bgwhite" style="margin-top: 40px;height: 80px;padding-top: 20px">
			<p style="font-size: 18px">总计 :</p>
			<p style="font-size: 14px">
				<?php if($this->data['type'] == 'single') {?>
					<span><?php echo  $this->data['amount'].$this->data['pay_name']?> + <?php echo $this->data['pay_amount'].' RMB服务费'?></span>
				<?php } else {?>
					<span><?php echo  $this->data['amount'].$this->data['pay_name']?> + <?php echo $this->data['money'].'RMB +'.($this->data['pay_amount'] - ($this->data['money'] ? $this->data['money'] :$this->data['amount'])).' RMB服务费'?></span>
				<?php }?>

			</p>
		</div>
	<?php }?>
	<div class="n-payway-tit">
		<p style="padding: 5px 0px;margin-top:10px;">选择支付方式 :</p>
	</div>
	<div class="n-payway-list" style="border-bottom: 2px #ebebeb solid">
		<ul>
			<?php foreach($this->payments as $row) { ?>
			<li class="payment"><a href="javascript:;" data-code="<?=$row['code']?>" class="choose-payment"><img src="<?=$this->baseUrl($row['logo'])?>" alt="<?=$row['name']?>" style="margin-top: 0px"></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<form method="post" action="<?=$this->url('usercp/money/pay')?>" class="pay-form">
	<input type="hidden" name="return_url" value="<?=$_POST['return_url'] ? $_POST['return_url'] : $this->url($this->data['return_url']) ?>">
	<input type="hidden" name="type" value="<?=$_POST['type'] ? $_POST['type'] : $this->data['type'] ?>">
	<input type="hidden" name="amount" value="<?=$_POST['amount'] ? $_POST['amount'] : ($this->data['type'] == 'hybrid' || $this->data['type'] == 'cash' || $this->data['type'] == 'single' ? $this->data['pay_amount'] :$this->data['amount'])?>">
	<?php if(isset($this->data) && $this->data['type'] == 'hybrid' || $this->data['type'] == 'cash' || $this->data['type'] == 'single') {?>
		<input type="hidden" name="glod_id" value="<?php echo $this->data['glod_id']?>">
	<?php }?>
	<input type="hidden" name="payment">
</form>
</body>

<script>
	$('.payway').height($(window).height()-0);
	$('.choose-payment').on('click', function(){
		var code = $(this).data('code');
		$('[name=payment]').val(code);
		$('.pay-form').submit();
	});
</script>
</html>