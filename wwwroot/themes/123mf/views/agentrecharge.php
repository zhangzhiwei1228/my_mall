<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<form class="recharge" method="post">
	<input type="hidden" name="type" value="<?=$this->_request->t?>">
	<input type="hidden" name="amount" value="0">
	<input type="hidden" name="return_url" value="<?php echo $this->url('agent')?>">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		在线充值
	</div>
	<div class="n-recharge-pic clear">
		<div class="n-recharge-head">
			<div class="n-recharge-head-info"><img src="<?=$this->baseUrl($row['avatar'])?>" alt=""></div>
		</div>
		<span><?=$this->user['nickname']?></span>
		<p>会员账号：<?=$this->user['mobile']?></p>
	</div>
	<?php if ($this->_request->t == 'credit_happy') { ?>
	<div class="n-recharge-pic-te">
		<p style="color:#b40000;font-size:14px;">快乐充值说明：</p>
		<p style="color:#555;">1元=<?php echo $this->data['r_digital']?>快乐积分（快乐积分只能在兑购商品时使用的）</p>
	</div>
	<div class="n-h5"></div>
	<div class="n-recharge-sp">
		<p>输入要充值的快乐积分 </p>		
		<a href=""></a>
		<input type="text" name="point">
	</div>
	<div class="n-recharge-end">
		<span>支付金额：</span>
		<p class="amount-text">0</p>
		<span>RMB</span>
	</div>
	<script type="text/javascript">
		var rate = <?php echo $this->data['r_digital']?>;
		$('[name=point]').on('change', function(){
			var num = parseFloat($(this).val());
			//console.log(num/rate);
			$('[name=amount]').val((num/rate).toFixed(2));
			$('.amount-text').text((num/rate).toFixed(2));
		});
	</script>
	<?php } elseif ($this->_request->t == 'credit_coin') { ?>
	<div class="n-recharge-pic-te">
		<p style="color:#b40000;font-size:14px;">积分币充值说明：</p>
		<p style="color:#555;">1元=<?php echo $this->data['r_digital']?>积分币（积分币只能在兑购商品时使用的）</p>
	</div>
	<div class="n-h5"></div>
	<div class="n-recharge-sp">
		<p>输入要充值的积分币 </p>		
		<a href=""></a>
		<input type="text" name="point">
	</div>
	<div class="n-recharge-end">
		<span>支付金额：</span>
		<p class="amount-text">0</p>
		<span>RMB</span>
	</div>
	<script type="text/javascript">
		var rate = <?php echo $this->data['r_digital']?>;
		$('[name=point]').on('change', function(){
			var num = parseFloat($(this).val());
			//console.log(num/rate);
			$('[name=amount]').val((num/rate).toFixed(2));
			$('.amount-text').text((num/rate).toFixed(2));
		});
	</script>
	<?php } elseif ($this->_request->t == 'credit') { ?>
	<div class="n-recharge-pic-te">
		<p style="color:#b40000;font-size:14px;">帮帮币充值说明：</p>
		<p style="color:#555;">1元=<?php echo $this->data['r_digital']?>帮帮币（帮帮币只能在兑购商品时使用的）</p>
	</div>
	<div class="n-h5"></div>
	<div class="n-recharge-sp">
		<p>输入要充值的帮帮币 </p>
		<a href=""></a>
		<input type="text" name="point">
	</div>
	<div class="n-recharge-end">
		<span>支付金额：</span>
		<p class="amount-text">0</p>
		<span>RMB</span>
	</div>
	<script type="text/javascript">
		var rate = <?php echo $this->data['r_digital']?>;
		$('[name=point]').on('change', function(){
			var num = parseFloat($(this).val());
			//console.log(num/rate);
			$('[name=amount]').val((num/rate).toFixed(2));
			$('.amount-text').text((num/rate).toFixed(2));
		});
	</script>
	<?php } elseif ($this->_request->t == 'vouchers') { ?>
		<div class="n-recharge-pic-te">
			<p style="color:#b40000;font-size:14px;">充值抵用券赠送活动说明：</p>
			<p style="color:#555;">会员线下消费后，商家赠送抵用券，抵用券可在帮帮网商城中兑换商品使用</p>
			<p style="color:#b40000;font-size:14px;">抵用券充值说明：</p>
			<p style="color:#555;">1元=<?php echo $this->data['r_digital']?>抵用券（抵用券可转换为帮帮币、积分币等）</p>
		</div>
		<div class="n-h5"></div>
		<div class="n-recharge-sp">
			<p>输入要充值的抵用券 </p>
			<a href=""></a>
			<input type="text" name="vouchers">
		</div>
		<div class="n-recharge-end">
			<span>支付金额：</span>
			<p class="amount-text">0</p>
			<span>RMB</span>
		</div>
		<script type="text/javascript">
			var rate = <?php echo $this->data['r_digital']?>;
			$('[name=vouchers]').on('change', function(){
				var num = parseFloat($(this).val());
				//console.log(num/rate);
				$('[name=amount]').val((num/rate).toFixed(2));
				$('.amount-text').text((num/rate).toFixed(2));
			});
		</script>
	<?php } else { ?>
	<div class="n-recharge-pic-te">
		<p style="color:#b40000;font-size:14px;">在线充值说明：</p>
		<!-- <p style="color:#555;">1元=2积分币（积分币只能在兑购商品时使用的）</p> -->
	</div>
	<div class="n-h5"></div>
	<div class="n-recharge-sp">
		<p>输入要充值的金额 </p>		
		<a href=""></a>
		<input type="text" name="point">
	</div>
	<div class="n-recharge-end">
		<span>支付金额：</span>
		<p class="amount-text">0</p>
		<span>RMB</span>
	</div>
	<script type="text/javascript">
		$('[name=point]').on('change', function(){
			var num = parseFloat($(this).val());
			$('[name=amount]').val(num);
			$('.amount-text').text(num);
		});
	</script>
	<?php } ?>
	<div class="n-recharge-sub">
		<input class="n-recharge-end-sub" value="确认" type="submit">
	</div>
</form>
</body>
</html>