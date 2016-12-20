<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    <div class="n-member">
    	<div class="text">
    		<h2>会员激活</h2>
    		<div class="p">
    			<p>会员激活后可享受或购买商品服务！</p>
			</div>
            <div class="p">
                <p style="color:#b40000;font-size:14px;line-height:26px;">激活说明：</p>
                <p style="color:#333;">会员第一次兑换或购买物品必须先激活，成为有效会员</p>
            </div>

    	</div>
    </div>
    <?php include_once VIEWS.'app/inc/footer.php'; ?>
<?php
	echo static_file('app/m/js/main.js');
?>
<script>
$(function(){

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