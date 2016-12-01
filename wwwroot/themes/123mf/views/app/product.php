<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="n-banner f-cb">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
            	<a href="###">
            		<img src="<?php echo static_file('m/img/pic1.jpg'); ?> ">
            	</a>
            </div>
            <div class="swiper-slide">
            	<a href="###">
            		<img src="<?php echo static_file('m/img/pic1.jpg'); ?> ">
            	</a>
            </div>
            <div class="swiper-slide">
            	<a href="###">
            		<img src="<?php echo static_file('m/img/pic1.jpg'); ?> ">
            	</a>
            </div>
        </div>
        <div class="hd-wdi">
        	<div class="hd-1"></div>
        </div>
    </div>
    <div class="n-product">
	    <div class="pro-top">
	    	<div class="x-top">
	    		<h2>秋冬毛绒绒外套女加厚小熊兔耳朵可爱学生上
				衣宽松毛茸茸卫衣褂子</h2>
				<div class="jia">
					<div class="j">原价：100.00 <div class="jies">省10元</div></div>
					<div class="s">已售100件</div>
				</div>
	    	</div>
	    	<div class="jg-c">
	    		<dl class="f-cb">
	    			<dt class="fl">
	    				促销价
	    			</dt>
	    			<dd class="fr">
	    				<div class="inp">
	    					<input name="radio_a" type="radio" value="" checked="checked">20帮帮币
	    				</div>
	    				<div class="inp">
	    					<input name="radio_a" type="radio" value="" checked="checked">
	    					20积分币
	    				</div>
	    				<div class="inp">
		    				<input name="radio_a" type="radio" value="" checked="checked">
		    				20积分币+10.00
	    				</div>
	    				<div class="inp">
		    				<input name="radio_a" type="radio" value="" checked="checked">
		    				50.00
	    				</div>
	    			</dd>
	    		</dl>
	    	</div>
	    </div>
	    <div class="n-line">
	    	 
	    </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('m/js/main.js');
	echo static_file('m/swiper/swiper.min.js');
	echo static_file('m/swiper/swiper.css');
?>
<script>
$(function(){
	var swiper = new Swiper('.n-banner', {
        pagination: '.hd-1',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 4500,
        autoplayDisableOnInteraction: false,
    });
})
</script>
</body>
</html>