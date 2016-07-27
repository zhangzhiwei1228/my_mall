<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		员工列表
	</div>
	<div class="n-h5"></div>
	<!-- <div class="n-h5"></div> -->
	<div class="n-dealer-list">
		<ul class="clear">
			<li>
				<span class="n-dealer-span1">员工账号：</span>
				<span class="n-dealer-span2"><?=$this->data['id']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">员工名称：</span>
				<span class="n-dealer-span3"><?=$this->data['username']?></span>	
			</li>
			<li>
				<span class="n-dealer-span1">本月发展的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last1']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月激活的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last1']['vip']?></span>
			</li>


			<li>
				<span class="n-dealer-span1">历史发展的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history1']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史激活的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history1']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月发展的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last2']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月激活的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last2']['vip']?></span>
			</li>


			<li>
				<span class="n-dealer-span1">历史发展的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history2']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史激活的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history2']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">一级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin1']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">二级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin2']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">
				<p style="line-height:25px;">发展的商家的一级会员本月</p>
				<p style="line-height:25px;">消费积分币：</p>
				</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin3']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">
				<p style="line-height:25px;">发展的商家的二级会员本月</p>
				<p style="line-height:25px;">消费积分币：</p>
				</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin4']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">发展的商家本月使用免费积分：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['seller']['credit']['total']?></span>
			</li>
		</ul>
	</div>
	<!-- <div class="n-h5"></div> -->
	
	<div class="n-dealer-end">
		<div class="n-dealer-end-top">本月收益</div>
		<div class="n-dealer-end-down">
			<p style="margin-top:18px;" class="n-dealer-end-down-p1"><?=(float)$this->bonus['amount']?></p>
			<p class="n-dealer-end-down-p2">元</p>
		</div>
	</div>
	
	<div class="n-h56"></div>
	<?php if($this->user['role'] == 'seller') {?>
		<?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
	<?php } else {?>
		<?php include_once VIEWS.'inc/footer_fourstar.php'; ?>
	<?php }?>
	
	
</body>
</html>