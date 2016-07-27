<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="z-jifenheader bgcolor<">
		<div class="header-merchants">
			<div class="main"><a href="<?=$this->url('index')?>"><img src="<?php echo static_file('m/img/icon18.png'); ?> "></a>员工业绩详情</div>
		</div>
	</div>
	<!-- <div class="admin-box bgwhite">
		<div class="merc-admin w90">
			<div class="pic fl"><img src="<?php echo static_file('m/img/pic17.png'); ?> "></div>
			<div class="intro fl">
				<p class="admin-p">商家员工收益</p>
				<p class="admin-name">周星星</p>
				<p class="login-info"><a href="#">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">修改密码</a></p>
			</div>
			<div class="clear"></div>
		</div>
	</div> -->
	<div class="staff-jifen bgwhite"><p class="w90">员工账号：<span class="staff fr"><?=$this->data['id']?></span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">员工名称：<span class="staff fr"><?=$this->data['username']?></span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我员工发展的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我员工发展的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span><?=(float)$this->bonus['amount']?></span>&nbsp;元</p>
	</div>
	<div class="member-rank bgwhite">
		<p class="w90">一级会员：我直接邀请注册的会员</p>
		<p class="w90">二级会员：我的一级会员邀请注册的会员</p>
	</div>
   <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>



