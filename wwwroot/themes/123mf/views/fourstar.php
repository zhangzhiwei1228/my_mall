<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <div class="z-jifenheader bgcolor<">
		<div class="header-jifen">
			<div class="main w90"><a href="#"></a>创业四星分销商员工</div>
			<div class="hnshart">
				<img width="25" height="23" src="<?php echo static_file('mobile/img/icon.png'); ?> " alt="">
			</div>
		</div>
	</div>
	<div class="admin-box bgwhite">
		<div class="merc-admin w90">
			<div class="pic fl"><img src="<?php echo static_file('m/img/pic17.png'); ?> "></div>
			<div class="intro fl">
				<p class="admin-p">创业四星分销商员工</p>
				<p class="admin-name">周星星</p>
				<p class="login-info"><a href="#">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#">修改密码</a></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	

    <div class="staff-jifen bgwhite"><p class="w90">本月发展的一级会员总数：<span class="fr"><em>10000</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的一级会员总数：<span class="fr"><em>100</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史发展的一级会员总数：<span class="fr"><em>10000</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的一级会员总数：<span class="fr"><em>100</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月发展的二级会员总数：<span class="fr"><em>100</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的二级会员总数：<span class="fr"><em>50</em>&nbsp;个</span></p></div>
    <div class="staff-jifen bgwhite"><p class="w90">历史发展的二级会员总数：<span class="fr"><em>100</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的二级会员总数：<span class="fr"><em>50</em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的一级会员本月消费积分币<span class="fr"><em>100</em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的二级会员本月消费积分币<span class="fr"><em>100</em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的一级会员本月消费积分币：<span class="fr"><em>23000</em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的二级会员本月消费积分币：<span class="fr"><em>23000</em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90"><a href="<?php echo site_url('fourstar/retailer_list'); ?> ">我发展的商家本月使用帮帮币<span class="fr"><em>23000</em>&nbsp;币</span></a></p></div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span>10000</span>&nbsp;元</p>
	</div>

	<div class="member-rank bgwhite">
		<p class="w90">一级会员：我直接邀请注册的会员</p>
		<p class="w90">二级会员：我的一级会员邀请注册的会员</p>
	</div>
	<div class="ffxx-box">
	    <div class="ffxx">
	        <!-- JiaThis Button BEGIN -->
	            <div class="jiathis_style_32x32">
	            <a class="jiathis_button_cqq">
	                <p>QQ</p>
	            </a>
	            <a class="jiathis_button_qzone">
	                <p>QQ空间</p>
	            </a>
	            <a class="jiathis_button_weixin">
	                <p>微信</p>
	            </a>
	            <a class="jiathis_button_tsina">
	                <p>新浪微博</p>
	            </a>
	            <a class="jiathis_button_tqq">
	                <p>腾讯微博</p>
	            </a>
	            </div>
	            <div class="friend">
	                <span></span>
	                <p>朋友圈</p>
	            </div>
	        <!-- JiaThis Button END -->
	    </div>
	</div>
	<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
    <?php //include_once VIEWS.'inc/footer_retailer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
<script>
	var jiathis_config={
		summary:"",
		shortUrl:false,
		hideMore:false
	}
	$(".ffxx-box").click(function(){
        $(this).hide();
    })

   	$(".hnshart").click(function(){
    	//console.log('jjd');
    	$(".ffxx-box").show();
    })
</script>
</html>