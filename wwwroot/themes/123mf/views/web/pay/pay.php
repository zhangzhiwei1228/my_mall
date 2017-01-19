<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">
    	<div class="pay">
    		<div class="zt-img"><img src="<?php echo static_file('web/img/img-48.png'); ?> " alt=""></div>
    		<div class="submit-order-tit">
    			<span>恭喜您提交订单成功，还差最后一步！</span>
    		</div>
    		<div class="pay-k">
    			<div class="pay-k-top">马上去付款吧，您需支付积分：</div>
    			<div class="pay-k-bot">
    				<span><font class="sp">20250</font>免费积分</span>
    				<span><font class="sp">5000</font>积分币</span>
    			</div>
    		</div>
    		<div class="pay-add">
    			<img src="<?php echo static_file('web/img/img-49.png'); ?> " alt="">
    			<span>徐少波</span>
    			<span>浙江省杭州市莫干山路登云路口481弄中博文化创意园E座601</span>     
    			<span>15967161500</span> 
    		</div>
    		<div class="explanation">您目前的可用会员积分余额为<font class="ex-sp">15000</font>个人积分余额为<font class="ex-sp">10000</font></div>
    	</div>
    	<div class="pay-bot">
    		<div class="pay-add-bot">
    			<span>选择现金支付方式：无需现金时隐藏</span>
    		</div>
    		<ul class="pay-img">
    			<li>
    				<input name="same" type="radio">支付<img src="<?php echo static_file('web/img/img-32.png'); ?> " alt="">
    			</li>
    			<li>
    				<input name="same" type="radio">支付<img src="<?php echo static_file('web/img/img-33.png'); ?> " alt="">
    			</li>
    		</ul>
    		<div class="pay-input">
    			<input value="立即付款" type="submit">
    		</div>
    	</div>
    </div>
    <div class="fix-bg">
        <!-- <div class="bg-succ">    成功弹框
        	<div class="hide"></div>
        	<img src="<?php echo static_file('web/img/img-52.png'); ?> " alt="">
        	<div class="tea">支付成功，我们会尽快为您发货！</div>
        	<div class="teb">订单编号：254875469</div>
        	<div class="tec">
        		<span>继续查看<a href="">其他产品</a></span>
        		<span>去我的<a href="">订单中心</a>，查看购买商品</span>
        	</div>
        </div> -->
        <!-- <div class="bg-fail">   失败弹框
        	<div class="hide"></div>
        	<img src="<?php echo static_file('web/img/img-53.png'); ?> " alt="">
        	<div class="tea">支付失败！</div>
        	<div class="teb">
        		<span class="kk-te">免费积分余额不足</span>
        		<span class="kk-div">
        			<a href="">获取免费积分</a>
        		</span>
        	</div>
        	<div class="tec">
        		<span class="kk-te">积分币余额不足</span>
        		<span class="kk-div">
        			<a href="">充值积分币</a>
        		</span>
        	</div>
        </div> -->
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
</body>
<script>
$(function(){
	$(".pay-img li input").click(function(){
		$(this).parents("li").addClass('cur');
		$(this).parents("li").siblings('li').removeClass('cur');
	})
	$(".pay-img li").eq(0).css("border-top","1px solid #eaeaea");

	$(".bg-succ .hide").click(function(){
		$(".bg-succ").hide();
		$(".fix-bg").hide();
	})
	$(".bg-fail .hide").click(function(){
		$(".bg-fail").hide();
		$(".fix-bg").hide();
	})
})
	
		
	
</script>
</html>