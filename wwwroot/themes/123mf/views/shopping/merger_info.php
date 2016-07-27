<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop03.php'; ?>
    <div class="swiper-container merger-info bgwhite">
	    <div class="swiper-wrapper">
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic11.jpg'); ?> " width="100%" /></div>
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic11.jpg'); ?> " width="100%" /></div>
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic11.jpg'); ?> " width="100%" /></div>
	    </div>
	    <!-- 如果需要分页器 -->
	    <div class="swiper-pagination"></div>  
	</div>
	<p class="product-name bgwhite">【追加前2000名低至98元】美的双层防烫保温电热水壶</p>
	<form action="#" method="get" class="bgwhite">
		<div class="boline">
			<div class="discount-box w90">
				<span class="discount-price fl">促销价:</span>
				<label class="fl">
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;4890 积分</p>
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;2000 RMB+2000 积分</p>
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;2000 RMB+1800 积分币</p>
				</label>
				<p class="original fl">原  价:&nbsp;&nbsp;<span>¥4890 RMB</span></p><p class="selled fr">已售  100件</p>
				<div class="clear"></div>
			</div>
		</div>
		<div class="merger-time">
			<a class="time-show w90" href="#">
				<span class="tips fl">团购时间</span>
				<span class="date fr">10月1日 至 10月30日</span>
				<div class="clear"></div>
			</a>
		</div>
		<div class="boline">
			<div class="residue-time w90">
				<p class="fl"><em></em>剩余时间</p>
				<span class="fr">20天23小时59分</span>
				<div class="clear"></div>
			</div>
		</div>
		<div class="boline">
			<div class="stock w90">
				<div class="stock-info"><span class="fl">总库存&nbsp;<em>600</em></span><span class="fr">剩余库存&nbsp;<em>300</em></span><p class="clear"></p></div>
				<div class="progressbar"><em style="width: 30%;"></em></div>
				<!-- <p class="mergeref">已团购&nbsp;<span>30%</span></p> -->
				<div class="stock-info"><span class="fl">已团购&nbsp;<em>30%</em></span><!-- <span class="fr">剩余库存&nbsp;<em>300</em></span> --><p class="clear"></p></div>
			</div> 
		</div>
		<div class="boline">
			<p class="redtip">*&nbsp;&nbsp;抢购使用区域</p>
			<div class="send-addess w90">
				<select class="fl">
					<option>配送至浙江省</option>
					<option>xxxx</option>
					</option>
				</select>
				<p class="fl postage">邮费  10元</p>
				<p class="fr">A类综合打包</p>
				<div class="clear"></div>
			</div>
		</div>
		<div class="boline">
			<div class="buy-muns w90">
				<span class="title fl">购买数量：</span>
				<div class="click-nums fl">
		            <a href="javascript:void(0);" class="add">+</a>
		            <input type="text" value="1">
		            <a href="javascript:void(0);" class="jian">-</a>
				</div>
				<span class="fl">&nbsp;&nbsp;&nbsp;&nbsp;库存 500件</span>
				<div class="clear"></div>
			</div>
		</div>

	</form>
	<div class="product-details-box bgwhite">
		<p class="product-headline"><em class="fl"></em>&nbsp;&nbsp;&nbsp;&nbsp;<span class="cn">产品详情</span>&nbsp;&nbsp;<span class="en"></span>Product&nbsp; details</span></p>.
		<p class="pic"><img src="<?php echo static_file('m/img/pic10.jpg'); ?> "></p>
	</div>
        <?php ///include_once VIEWS.'inc/footer_shopping.php'; ?>
    <?php //include_once VIEWS.'inc/footer01.php'; ?>
    <?php include_once VIEWS.'inc/footer_buying.php'; ?>
<?php
	echo static_file('m/js/main.js');
	echo static_file('jquery.SuperSlide.2.1.1.js');
?>
<script type="text/javascript">
	 var mySwiper = new Swiper ('.swiper-container', {
    loop: true,
    
    // 如果需要分页器
    pagination: '.swiper-pagination',
    autoplay: 2000
    
  })   
	$('.discount-box label .select').click(function(){
	 	$(this).addClass('selectcurr').siblings('.select').removeClass('selectcurr');
	});




</script>
</body>
</html>


