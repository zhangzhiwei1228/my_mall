<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="z-jifenheader bgcolor<">
		<div class="header-jifen">
			<div class="main w90"><a href="#"></a>订单详情</div>
		</div>
	</div>
	<div class="person-info">
		<div class="main w90">
			<em class="fl"></em>
			<div class="fl info-box">
				<p class="info01"><span>收货人：周星星</span><span class="fr">13420715658</span></p>
				<p class="info02">收货地址：杭州市莫干山路841弄23号中博文化创意园E座601室</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<p class="order-number bgwhite"><span class="w90">订单编号：56598765</span></p>
	<div class="order-pro bgwhite">
		<div class="pro-info w90">
			<p class="pic fl"><img src="<?php echo static_file('m/img/pic16.jpg'); ?> " width="100%" /></p>
			<div class="intro fl">
				<p>楼兰蜜语玫瑰红葡萄干225g新疆特产无核提子红葡萄</p>
				<p>x 1</p>
				<p>价格：<span>4000</span>积分</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="order-pro bgwhite">
		<div class="pro-info w90">
			<p class="pic fl"><img src="<?php echo static_file('m/img/pic16.jpg'); ?> " width="100%" /></p>
			<div class="intro fl">
				<p>楼兰蜜语玫瑰红葡萄干225g新疆特产无核提子红葡萄</p>
				<p>x 1</p>
				<p>价格：<span>4000</span>积分</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="order-number bgwhite">
		<p class="w90">
			<span class="fl">订单时间 :<em>2015-09-09</em></span>
			<span class="fr">支付状态 :<em>未支付</em></span>
			<div class="clear"></div>
		</p>
	</div>
	<div class="order-total bgwhite">
		<div class="w90">
			<em class="fl">总计：</em>
			<p class="fl">
				<span><i>8000</i>&nbsp;免费积分</span>
				<span><i>500</i>&nbsp;积分币</span>
				<span><i>500</i>&nbsp;RMB</span>
			</p>
			<a class="fr" href="#">立即支付</a>
			<div class="clear"></div>
		</div>
	</div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>


