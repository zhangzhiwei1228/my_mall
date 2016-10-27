<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; $time = time();?>
</head>
<body style="background:#ebebeb">
	<div class="n-allorders">
		<div class="n-personal-center-tit">
			<a href="<?=$this->url('')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			订单详情
		</div>
		<div class="n-allorders-box">
			<ul class="n-allorders-box-li clear">
				<li><a href="<?=$this->url('action=list&t=')?>">全部</a></li>
				<li><a href="<?=$this->url('action=list&t=awaiting_payment')?>">未付款</a></li>
				<li><a href="<?=$this->url('action=list&t=shiped')?>">待发货</a></li>
				<li><a href="<?=$this->url('action=list&t=pending_receipt')?>">待确认</a></li>
				<li><a href="<?=$this->url('action=list&t=completed')?>">已完成</a></li>
				<!-- <li><a href="<?=$this->url('action=list&t=closed')?>">已关闭</a></li> -->
			</ul>
			<div class="n-h5"></div>
			<?php if (!count($this->datalist)) { ?>
			<p class="notfound">没有找到符合条件的信息。</p>
			<?php } else { ?>
			<?php foreach ($this->datalist as $row) { ?>
			<div class="n-money-pro">
					<div class="n-money-pro-top">
						<span>价格：<?=$row['total_amount']?>元 + <?=$row['total_credit']?>免费积分 + <?=$row['total_credit_happy']?>快乐积分 + <?=$row['total_credit_coin']?>积分币+ <?=$row['total_vouchers']?>抵用券</span>

					</div>
					<?php
						$i = 0; 
						$rowspan = $row->goods->total(); 
						foreach ($row->goods as $col) { $i += 1;
					?>
					<div class="n-money-pro-m">
						
						<div class="n-shopping-box-down" onclick="window.location = '<?=$this->url('./detail?id='.$row['id'])?>'">
							<div class="n-shopping-down-img">
								<img src="<?=$this->baseUrl($col['thumb'])?>" alt="">
							</div>
							<div class="n-shopping-down-te">
								<a href=""><p class="n-shopping-down-te1"><?=$col['title']?></p></a>
								<p class="n-shopping-down-te2">x <?=$col['purchase_quantity']?></p>
								<!-- <p class="n-shopping-down-te3">2015-09-09</p> -->
							</div>
						</div>
					</div>
					<?php if ($row['status'] == 1 && $row['expiry_time'] > time()) { ?>
						<div class="n-all-m">
							<input value="立即支付" type="button"  onclick="window.location = '<?=$this->url('/default/cart/pay/?id='.$row['id'])?>'">
						</div>
					<?php } elseif($row['status'] == 5 && $row['expiry_time'] < time()) { ?>
						<div class="n-all-m">
							<input value="已过期" type="button" style="background-color: #c7bcb5" disabled>
						</div>
					<?php }?>
					<?php if ($row['status'] == 3) {?>
					<div class="n-all-m">
						<input value="查看物流" type="button" onclick="window.location = '<?=$this->url('./express/?id='.$row['id'])?>'">
						<input value="确认收货" type="button" onclick="window.location = '<?=$this->url('./confirm/?id='.$row['id'])?>'">
					</div>
					<?php } elseif ($row['status'] == 4 && $row['total_earn_points']) { ?>
					<div class="n-all-m">
						<?php if($row['is_receive'] == 1) {?>
							<input value="已领取" type="button" style="background-color: #c7bcb5">
						<?php } else {?>
							<input value="领取红包" type="button" onclick="window.location = '<?=$this->url('./receive/?id='.$row['id'])?>'">
						<?php }?>

					</div>
					<?php } ?>
					
					<?php } ?>
			</div>
			<?php } ?>
			<!-- <div class="text-center">
				<ul class="pagination"><?=$this->paginator($this->datalist)?></ul>
			</div> -->
			<?php } ?>
		</div>
	</div>
</body>
<script>
	<?php 
	$n = array('awaiting_payment'=>1, 'shiped'=>2, 'pending_receipt'=>3, 'completed'=>4);
	$i = isset($n[$this->_request->t]) ? $n[$this->_request->t] : 0;
	?>
	$(".n-allorders-box-li li").eq(<?=$i?>).css("height","48px");
	$(".n-allorders-box-li li").eq(<?=$i?>).css("border-bottom","1px solid #b40000");
</script>
</html>