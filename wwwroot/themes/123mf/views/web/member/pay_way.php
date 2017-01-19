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
    	<div class="pay-way">
            <div class="tit">选择支付方式</div> 
            <ul class="pay-way-ula">
                <li style="border-bottom:1px solid #cdcdcd;">
                    <input class="inputa" name="ai" type="radio">
                    <span>支付</span>
                    <img src="<?php echo static_file('web/img/img-32.png'); ?> " alt="">
                </li>
                <li>
                    <input class="inputa" name="ai" type="radio">
                    <span>支付</span>
                    <img src="<?php echo static_file('web/img/img-33.png'); ?> " alt="">
                </li>
            </ul>
            <input value="去支付" class="inputb" type="">
        </div>
	</div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
	$(function(){
		$(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');
    
        $(".pay-way-ula li input").click(function(){
            $(this).parents("li").addClass('cur');
            $(this).parents("li").siblings('li').removeClass('cur');
        })
	})
</script>
</html>