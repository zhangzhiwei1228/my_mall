<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic20.jpg'); ?> " width="100%"></div>
    <form class="jifen-searchbox" action="#" method="get">
    	<input class="inputext fl" type="text" value="" placeholder="输入会员账号" />
    	<input class="submit fl" type="submit" value="" />
    	<div class="clear"></div>
    </form>
    <div class="jifen-search-result">
	    <table class="jifen-member w90 bgwhite">
	    	<tr>
	    		<td align="center">会员账号</td>
	    		<td align="center">会员名</td>
	    		<td align="center">积分余额</td>
	    	</tr>
	    	<tr>
	    		<td align="center">000101</td>
	    		<td align="center">韦小宝</td>
	    		<td align="center">10000</td>
	    	</tr>
	    </table>
	    <a href="<?php echo site_url('jifen/jifenstep021'); ?> " class="next">下一步</a>
	</div>
    <div class="jifen-default">
	    <div class="jifen-free bgwhite">
	    	<div class="w90">
	    		<p class="free-text fl"><em>积</em>免费积分余额</p>
	    		<p class="free-point fr"><span>10000</span>分</p>
	    		<div class="clear"></div>
	    	</div>
	    </div>
	    <div class="jifen-recharge bgwhite">
	    	<a href="#">立即充值免费积分</a>
	    </div>
	</div>
	<div class="n-h60"></div>
 <?php include_once VIEWS.'inc/footer_merchants.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('.jifen-searchbox .submit').click(function(){
		$(this).parent('.jifen-searchbox').siblings('.jifen-default').css('display','none');
		$(this).parent('.jifen-searchbox').siblings('.jifen-search-result').css('display','block');

	});
</script>
</body>
</html>

                       
