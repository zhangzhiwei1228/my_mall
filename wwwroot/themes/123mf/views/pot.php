<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="pot">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			商家详情
		</div>
		<div class="merchant-pic">
    	<img src="<?php echo static_file('m/img/pic14.jpg'); ?> " />
    	<p class="merchant-name">&nbsp;&nbsp;&nbsp;&nbsp;锅内锅外</p>
	    </div>
	    <div class="m-phone m-text bgwhite">
	    	<p class="w90"><span>电话：</span>0571-8080808080<a class="fr" href="#"></a></p>
	    </div>
	     <div class="m-address m-text bgwhite">
	    	<p class="w90"><span>地址：</span>余杭区良博路145号（良渚街道办事处对面）<a class="fr" href="#"></a></p>
	    </div>
	    <div class="m-serve m-text bgwhite">
	    	<p class="w90"><span>地址：</span>WIFI</p>
	    </div>
	    <div class="n-h5"></div>
	    <div class="pot-js">
	    	<div class="hd">
	    		<ul>
	    			<li>
		    			<img width="11px" height="11px;" src="<?php echo static_file('mobile/img/pot-2.png'); ?> " alt="">
		    			<span>饭店介绍</span>
	    			</li>
	    			<li>
		    			<img width="11px" height="11px;" src="<?php echo static_file('mobile/img/pot-3.png'); ?> " alt="">
		    			<span>累计评价</span>
	    			</li>
	    		</ul>
	    	</div>
	    	<div class="bd">
	    		<ul class="clear">
	    			<li>
	    			<br>
	    				<p>锅内锅外是杭州川味观连锁机构与香港锴瑞餐饮集团有限公司联手打造的港式时尚海鲜自助餐。更有西式铁板烧、时尚小火锅、港式西点、日式寿司、甜品等300余种国际美食内设其中，自助海鲜更是生猛，生鱼片、寿司……吃到过瘾！</p>
	    			</li>
	    		</ul>
	    		<ul class="clear">
	    			<li>
	    				<div class="pot-js-l">
		    				<span>
		    					<img src="<?php echo static_file('mobile/img/pot-1.png'); ?> " alt="">
		    				</span>	
	    				</div>
	    				<div class="pot-js-r">
	    					<div class="pot-js-r-top">
	    						<span style="color:#333;" class="fl">张三李四王五</span><span style="color:#777;" class="fr">2015-11-11</span>
	    					</div>
	    					<div style="color:#777;" class="pot-js-r-down">
	    						<p>鞋子收到了，外观漂亮，质感不错，没有异味。全五星好评物美价廉，推荐购买。</p>
	    					</div>
	    				</div>
	    			</li>
	    			<li>
	    				<div class="pot-js-l">
		    				<span>
		    					<img src="<?php echo static_file('mobile/img/pot-1.png'); ?> " alt="">
		    				</span>	
	    				</div>
	    				<div class="pot-js-r">
	    					<div class="pot-js-r-top">
	    						<span style="color:#333;" class="fl">张三李四王五</span><span style="color:#777;" class="fr">2015-11-11</span>
	    					</div>
	    					<div style="color:#777;" class="pot-js-r-down">
	    						<p>鞋子收到了，外观漂亮，质感不错，没有异味。全五星好评物美价廉，推荐购买。</p>
	    					</div>
	    				</div>
	    			</li>
	    			<li>
	    				<div class="pot-js-l">
		    				<span>
		    					<img src="<?php echo static_file('mobile/img/pot-1.png'); ?> " alt="">
		    				</span>	
	    				</div>
	    				<div class="pot-js-r">
	    					<div class="pot-js-r-top">
	    						<span style="color:#333;" class="fl">张三李四王五</span><span style="color:#777;" class="fr">2015-11-11</span>
	    					</div>
	    					<div style="color:#777;" class="pot-js-r-down">
	    						<p>鞋子收到了，外观漂亮，质感不错，没有异味。全五星好评物美价廉，推荐购买。</p>
	    					</div>
	    				</div>
	    			</li>
	    			<li>
	    				<div class="pot-js-l">
		    				<span>
		    					<img src="<?php echo static_file('mobile/img/pot-1.png'); ?> " alt="">
		    				</span>	
	    				</div>
	    				<div class="pot-js-r">
	    					<div class="pot-js-r-top">
	    						<span style="color:#333;" class="fl">张三李四王五</span><span style="color:#777;" class="fr">2015-11-11</span>
	    					</div>
	    					<div style="color:#777;" class="pot-js-r-down">
	    						<p>鞋子收到了，外观漂亮，质感不错，没有异味。全五星好评物美价廉，推荐购买。</p>
	    					</div>
	    				</div>
	    			</li>
	    		</ul>
	    	</div>
	    	<a href=""></a>
	    	<a href=""></a>
	    </div>
	</div>
	<?php echo static_file('mobile/js/jquery.SuperSlide.2.1.1.js'); ?>
</body>
<script>
	$(function(){
		jQuery(".pot-js").slide({trigger:"click"});
	})
</script>
</html>