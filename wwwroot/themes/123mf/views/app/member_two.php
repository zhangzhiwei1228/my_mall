<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="n-member">
    	<div class="text">
    		<h2>会员激活</h2>
    		<div class="p">
    			<p>会员激活后可享受或购买商品服务！</p>
			</div>
    	</div>
    	<!-- <div class="sq-box">
			<div class="sq-x">
				<a href="###">已激活</a>
			</div>
		</div>
        <div class="s-mask">
            <p>激活成功</p>
        </div>
        <div class="s-mask-two">
            <div class="k-mask">
                <div class="close">
                    <img src="<?php echo static_file('m/img/close.png'); ?> " alt="">
                </div>
                <p>抱歉<br>
                您的帮帮币不足，你可选择</p>
                <div class="bot">
                    <a href="###" class="a1">去充值</a>
                    <a href="###" class="a2">去转换</a>
                </div>
            </div>
        </div> -->
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('m/js/main.js');
?>
<script>
$(function(){
    //激活成功弹出框
	$(".sq-x").on('click',function(){
        $(".s-mask").show();
       setTimeout(function(){
            $(".s-mask").hide();
        },1000);
    })
    //激活失败弹出框，弹出充值框
    // $(".sq-x").on('click',function(){
    //     $(".s-mask-two").show();
    // })
    //  $(".close").on('click',function(){
    //     $(".s-mask-two").hide();
    // })

})
</script>
</body>
</html>