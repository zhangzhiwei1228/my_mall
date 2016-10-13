<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="z-jifenheader bgcolor<">
		<div class="header-merchants">
			<div class="main"><a href="<?=$this->url('index')?>"><img src="<?php echo static_file('m/img/icon18.png'); ?> "></a>
			<?php if ($this->user['role'] == 'agent') { ?>
			创业代理商管理员
			<?php } elseif ($this->user['role'] == 'seller') { ?>
			商家管理员收益
			<?php } ?>
			</div>
		</div>
	</div>
	<div class="admin-box bgwhite">
		<div class="merc-admin w90">
			<?php if ($this->user['role'] == 'seller') { ?>
				<a href="<?php echo $this->url('/agent/credit/cancel')?>" class="f-top" >核销记录</a>
			<?php } ?>

			<div class="pic fl">
<!--				<img src="--><?php //echo static_file('m/img/pic17.png'); ?><!-- ">-->
				<img src="<?php echo $this->baseUrl('uploads/avatar/6.png'); ?> ">
			</div>
			<div class="intro fl">
				<p class="admin-p"><?php if ($this->user['role'] == 'agent') { ?>
			创业代理商管理员
			<?php } elseif ($this->user['role'] == 'seller') { ?>
			商家管理员收益
			<?php } ?></p>
				<p class="admin-name"><?=$this->user['nickname']?></p>
				<p class="login-info">
					<?php if ($this->user['role'] == 'seller') { ?>
						<a href="<?php echo $this->url('/agent/credit/verification')?>">核销抵用金</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php } ?>
					<a href="<?=$this->url('passport/logout')?>">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a>
				</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php if ($this->user['role'] == 'agent') { ?>
	<div class="staff-jifen bgwhite"><p class="w90">我代理地区商家本月使用免费积分：<span class="fr"><em><?=(float)$this->bonus['area']['seller']['t_credit']?></em>&nbsp;分</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我代理地区会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['area']['member']['t_coin']?></em>&nbsp;币</span></p></div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
	</div>
	<?php } elseif ($this->user['role'] == 'seller') { ?>
	<div class="staff-jifen bgwhite"><p class="w90">我员工发展的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我员工发展的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
	</div>
	<?php } ?>

	<?php if ($this->user['role'] == 'seller') { ?>
	<div style="margin-bottom:71px;" class="member-rank bgwhite">
		<a href="<?php echo $this->url('agent/logs/employ')?>" ><p class="w90">商家本日赠送免费积分：<span style="font-size: 20px;color: #b40000;"><?php echo $this->employ['total'] ? $this->employ['total'] : 0 ?></span>（查看详情）</p></a>
		<a href="<?php echo $this->url('agent/logs/recharge')?>" ><p class="w90">商家本日充值免费积分：<span style="font-size: 20px;color: #b40000;"><?php echo $this->recharge['total'] ? $this->recharge['total'] : 0?></span>（查看详情）</p></a>
		<p class="w90">商家本月赠送免费积分数：<span style="font-size: 20px;color: #b40000;"><?php echo $this->Memploy['total'] ? $this->Memploy['total'] : 0?></span></p>
		<!--<p class="w90">一级会员：我直接邀请注册的会员</p>
		<p class="w90">二级会员：我的一级会员邀请注册的会员</p>-->
	</div>
	<?php } ?>
	<?php if($this->user['role'] == 'seller') {?>
		<?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
	<?php } else {?>
		<?php include_once VIEWS.'inc/footer_fourstar.php'; ?>
	<?php }?>

    
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>


