<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-payway">
	<div class="n-personal-center-tit">
		<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		在线支付
	</div>
	<div class="n-payway-tit">
		<p>选择支付方式 :</p>
	</div>
	<div class="n-payway-list">
		<ul>
			<?php foreach($this->payments as $row) { ?>
			<li><a href="javascript:;" data-code="<?=$row['code']?>" class="choose-payment"><img src="<?=$this->baseUrl($row['logo'])?>" alt="<?=$row['name']?>"></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<form method="post" action="<?=$this->url('usercp/money/pay')?>" class="pay-form">
	<input type="hidden" name="return_url" value="<?=$_POST['return_url']?>">
	<input type="hidden" name="type" value="<?=$_POST['type']?>">
	<input type="hidden" name="amount" value="<?=$_POST['amount']?>">
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