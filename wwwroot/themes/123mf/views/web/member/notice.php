<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
	<div class="bg">
		<div class="bread w1190">
    		<ul>
    			<li><a href="">首页</a></li>
    			<li>></li>
    			<li><a href="">个人中心</a></li>
    		</ul>
    	</div>
    	<div class="recharge-game-money w1190">
    		<?php include_once VIEWS.'inc/member_side.php'; ?>
    		<div class="fr">
    			<div class="tit">通知</div>
                <div class="notice">
                    <ul>
                        <li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url(''); ?> ';">
                            <span class="notice-l">最新优惠信息</span>
                            <span class="notice-r">2015-10-31</span>
                        </li>
                        <li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url(''); ?> ';">
                            <span class="notice-l">最新优惠信息</span>
                            <span class="notice-r">2015-10-31</span>
                        </li>
                        <li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url(''); ?> ';">
                            <span class="notice-l">最新优惠信息</span>
                            <span class="notice-r">2015-10-31</span>
                        </li>
                    </ul>
                </div>
    		</div>
    	</div>
	</div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
	$(function(){
		$(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');
	})
</script>
</html>