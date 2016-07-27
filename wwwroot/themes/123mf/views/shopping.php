<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<?php echo static_file('js/base.js'); ?>

<body>
<?php if ($this->_request->isAjax()) { $this->fragmentStart(); } ?>
<form class="n-shopping my-cart" method="post" action="<?=$this->url('./checkout')?>" style="margin-bottom: 60px;">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		购物车
	</div>
	<div class="n-h5"></div>
	<?php foreach ($this->items as $key => $row) { ?>
	<input type="hidden" name="cart[<?=$key?>][id]" value="<?=$row['id']?>" />
	<input type="hidden" name="cart[<?=$key?>][skuId]" value="<?=$row['skuId']?>" />
	<input type="hidden" name="cart[<?=$key?>][priceType]" value="<?=$row['priceType']?>" />
	<input type="hidden" name="cart[<?=$key?>][qty]" value="<?=$row['qty']?>" />
	<input type="hidden" name="cart[<?=$key?>][shipping_id]" value="<?=$row['shipping_id']?>" />
	<div class="n-shopping-box">
		<div class="n-shopping-box-top">
			<input type="checkbox" name="cart[<?=$key?>][checkout]" role="chk-item" value="1" <?=$row['checkout']?'checked':''?> <?=$row['goods']['quantity']==0?'disabled':''?> data-credit="<?=$row['subtotal_credit']?>" data-credit-happy="<?=$row['subtotal_credit_happy']?>" data-credit-coin="<?=$row['subtotal_credit_coin']?>" data-amount="<?=$row['subtotal_amount']?>" /> 
			<span>价格：<?=$row['goods']['price_text']?></span>
			<a class="n-shopping-hide" href="javascript:;" onclick="$.removeCart('<?=$key?>')"><img src="<?php echo static_file('mobile/img/img-46.png'); ?> " alt=""></a>
		</div>
		<div class="n-shopping-box-down">
			<div class="n-shopping-down-img">
				<img src="<?=$this->baseUrl($row['goods']['thumb'])?>" alt="<?=$row['goods']['title']?>">
			</div>
			<div class="n-shopping-down-te">
				<a href="<?=$this->url("item/").$row['id'].'.html'?>"><p class="n-shopping-down-te1"><?=$row['goods']['title']?></p></a>
				<p class="n-shopping-down-te2">x <?=$row['qty']?></p>
				<!-- <p class="n-shopping-down-te3">2015-09-09</p> -->
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="n-shopping-end">
		<div class="n-shopping-end-l">
			<input type="checkbox" role="chk-all" name="checkall" value="1" />
			<span>全选 总计：</span>
		</div>
		<div class="n-shopping-end-r">
			<?php if ($this->status['total_credit_happy']) { ?>
			<p><?=$this->status['total_credit_happy']?>快乐积分</p>
			<?php } ?>
			<?php if ($this->status['total_credit']) { ?>
			<p><?=$this->status['total_credit']?>免费积分</p>
			<?php } ?>
			<?php if ($this->status['total_credit_coin']) { ?>
			<p><?=$this->status['total_credit_coin']?>积分币</p>
			<?php } ?>
			<?php if ($this->status['total_amount']) { ?>
			<p><?=$this->status['total_amount']?>现金</p>
			<?php } ?>
		</div>
	</div>
	<input value="立即结算" class="n-shopping-end-sub" type="submit">
	<div class="n-h5"></div>
</form>


<?php if ($this->_request->isAjax()) { $this->fragmentEnd(); } ?>
<script>
	//初始化复选框
	var chk = true;
	$('[role=chk-item]', '.my-cart').each(function(i){
		if (!$(this).is(":checked")) chk = false;
	});
	$('[role=chk-all]', '.my-cart').prop('checked', chk);
	
	$('[role="chk-item"]').on('change', function(){
		var chk = true;
		$('[role=chk-item]', '.my-cart').each(function(){
			if (!$(this).is(":checked")) chk = false;
		});
		$('[role=chk-all]', '.my-cart').prop('checked', chk);
		$.updateCart($('.my-cart').serialize());
	});
	
	$('[role="chk-all"]').on('change', function(){
		var chk = $(this).is(":checked") ? true : false;
		$('[role=chk-item]', '.my-cart').prop('checked', chk);
		$.updateCart($('.my-cart').serialize());
	});	
</script>
<div class="n-h56"></div>
<?php include_once VIEWS.'inc/footer01.php'; ?>
<?php echo static_file('mobile/js/all.js'); ?>
</body>
<script>
	$(function(){
		$(".n-shopping-hide").click(function(){
			$(this).parents(".n-shopping-box").remove();
		})
	})
</script>
</html>