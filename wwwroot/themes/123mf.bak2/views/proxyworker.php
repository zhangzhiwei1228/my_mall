<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body style="background:#ebebeb;">
<div class="n-proxy">
	<div class="n-personal-center-tit">
			<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			<?php if ($this->parent['role'] == 'agent') { ?>
			代理商员工
			<?php } elseif ($this->parent['role'] == 'seller') { ?>
			商家员工
			<?php } elseif ($this->parent['role'] == 'resale') { ?>
			创业四星分销商员工
			<?php } ?>
	</div>
	<div style="background:#fff;" class="n-proxy-pic clear">
			<div class="n-dealer-head">
				<div class="n-dealer-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
			</div>
			<?php if ($this->parent['role'] == 'agent') { ?>
			<span>创业代理商员工</span>
			<?php } elseif ($this->parent['role'] == 'seller') { ?>
			<span>创业商家员工</span>
			<?php } elseif ($this->parent['role'] == 'resale') { ?>
			<span>创业四星分销商员工</span>
			<?php } ?>
			<p><?=$this->user['username']?></p>
			<div class="n-proxy-sp">
				<a class="n-proxy-spa" href="<?=$this->url('passport/logout')?>">退出</a>
				<a class="n-proxy-spa1" href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a>
			</div>
			
	</div>
	<div style="background:#fff;" class="n-dealer-list">
		<ul class="clear">
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
				<span class="n-dealer-span1">我的一级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin1']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的二级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin2']['credit_coin']['total']?></span>
			</li>
			<?php if ($this->parent['role'] == 'agent' || $this->parent['role'] == 'resale') { ?>
			<li>
				<span class="n-dealer-span1">我的商家的一级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin3']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的商家的二级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin4']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1"><a href="<?php echo site_url('shoplist'); ?> ">我发展的商家本月使用免费积分：</a></span>
				<span class="n-dealer-span3">分</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin5']['credit']['total']?></span>
			</li>
			<?php } ?>
		</ul>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的本月收益</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1"><?=$this->bonus['amount']?></p>
				<p class="n-dealer-end-down-p2">元</p>
			</div>
		</div>
		<div class="n-proworker">
			<p style="color:#333;line-height:28px;">一级会员：我直接邀请注册的会员</p>
			<p style="color:#333;line-height:28px;">二级会员：我的一级会员邀请注册的会员</p>
		</div>
	</div>
</div>

</body>
</html>