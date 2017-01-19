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
    			<div class="tit">积分转换</div>
    			<div class="check">
                    <img src="<?php echo static_file('web/img/img-35.png'); ?> " alt="">   
                    <p>免费积分余额不足</p>  
                    <p>转换失败</p>    
                    <div class="fail">
                        <a class="a1" href="">确认</a>
                        <a class="a2" href="">充值抵用卷</a>
                    </div>
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