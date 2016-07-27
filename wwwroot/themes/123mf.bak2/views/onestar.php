<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <div class="z-jifenheader bgcolor<">
		<div class="header-jifen">
			<div class="main w90">一星分销商</div>
		</div>
	</div>
	<div class="admin-box bgwhite">
		<div class="merc-admin w90">
			<div class="pic fl"><img src="<?php echo static_file('m/img/pic17.png'); ?> "></div>
			<div class="intro fl">
				<p class="admin-p">
				<?php switch($this->user['resale_grade']) {
					case 1: echo '创业一星分销商'; break;
					case 2: echo '创业二星分销商'; break;
					case 3: echo '创业三星分销商'; break;
					case 4: echo '创业四星分销商'; break;
				} ?>
				</p>
				<p class="admin-name"><?=$this->user['username']?></p>
				<p class="login-info"><a href="<?=$this->url('passport/logout')?>">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="staff-jifen bgwhite"><p class="w90">创业财富再升级如下：</div>
	<?php if ($this->user['resale_grade'] < 2) { ?>
	<div class="star-upgrade bgwhite"><div class="w90"><p class="star-r fl"><em class="star01"></em>升级二星分销商</p><a href="<?=$this->url('vip/level?t=2')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>
	<?php if ($this->user['resale_grade'] < 3) { ?>
	<div class="star-upgrade bgwhite"><div class="w90"><p class="star-r fl"><em class="star02"></em>升级三星分销商</p><a href="<?=$this->url('vip/level?t=3')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>
	<?php if ($this->user['resale_grade'] < 4) { ?>
	<div class="star-upgrade bgwhite" style="margin-bottom:10px;"><div class="w90"><p class="star-r fl"><em class="star03"></em>升级四星分销商</p><a href="<?=$this->url('vip/level?t=4')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>

	<div class="staff-jifen bgwhite"><p class="w90">本月发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['vip']?></em>&nbsp;个</span></p></div>
    <div class="staff-jifen bgwhite"><p class="w90">历史发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['vip']?></em>&nbsp;个</span></p></div>
    <div class="staff-jifen bgwhite"><p class="w90">历史发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<?php if ($this->user['resale_grade'] >= 2) { ?>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的一级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin3']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的二级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin4']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<?php } ?>
	<div class="staff-jifen bgwhite"><p class="w90">我发展的商家本月使用免费积分<span class="fr"><em><?=(float)$this->bonus['seller']['credit_coin']['total']?></em>&nbsp;分</span></p></div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span><?=$this->bonus['amount']?></span>&nbsp;元</p>
	</div>

	<div class="member-rank bgwhite">
		<p class="w90">一级会员：我直接邀请注册的会员</p>
		<p class="w90">二级会员：我的一级会员邀请注册的会员</p>
	</div>
    <?php //include_once VIEWS.'inc/footer_retailer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>