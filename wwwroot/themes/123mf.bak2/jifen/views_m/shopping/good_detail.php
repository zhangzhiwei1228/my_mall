<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="swiper-container bgwhite">
	    <div class="swiper-wrapper">
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic09.jpg'); ?> " width="100%" /></div>
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic09.jpg'); ?> " width="100%" /></div>
	        <div class="swiper-slide"><img src="<?php echo static_file('m/img/pic09.jpg'); ?> " width="100%" /></div>
	    </div>
	    <!-- 如果需要分页器 -->
	    <div class="swiper-pagination"></div>  
	</div>
	<p class="product-name bgwhite">4050寸液晶电视机 平板电视  智能网络电视LED</p>
	<form action="#" method="get" class="bgwhite">
		<div class="boline">
			<div class="discount-box w90">
				<span class="discount-price fl">促销价:</span>
				<label class="fl">
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;4890 快乐积分</p>
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;4890 免费积分</p>
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;2000 RMB+2000 免费积分</p>
					<p class="select"><input type="radio" name="price" />&nbsp;&nbsp;2000 RMB+1800 积分币</p>
				</label>
				<p class="original fl">原  价:&nbsp;&nbsp;<span>¥4890 RMB</span></p><p class="selled fr">已售  100件</p>
				<div class="clear"></div>
			</div>
		</div>
		<div class="boline">
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
		<div class="boline">
			<div class="good-style w90">
				<p class="style-name">颜色分类</p>
				<ul>
					<li><a href="#"></a>天蓝色</li>
					<li><a href="#"></a>麻灰</li>
					<li><a href="#"></a>粉红色</li>
					<li><a href="#"></a>浅蓝</li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div class="boline">
			<div class="good-style w90">
				<p class="style-name">颜色分类</p>
				<ul>
					<li><a href="#"></a>天蓝色</li>
					<li><a href="#"></a>麻灰</li>
					<li><a href="#"></a>粉红色</li>
					<li><a href="#"></a>浅蓝</li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
	</form>
	<div class="product-details-box bgwhite">
		<p class="product-headline"><em class="fl"></em>&nbsp;&nbsp;&nbsp;&nbsp;<span class="cn">产品详情</span>&nbsp;&nbsp;<span class="en"></span>Product&nbsp; details</span></p>.
		<p class="pic"><img src="<?php echo static_file('m/img/pic10.jpg'); ?> "></p>
	</div>
    <?php include_once VIEWS.'inc/footer_buying.php'; ?>
<?php
	echo static_file('m/js/main.js');
	
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
  
  $('.good-style ul li').click(function(){
  		$(this).addClass('goodstylecurr').siblings('li').removeClass('goodstylecurr');
  });


  $(function(){

  	// $('.buy-muns .click-nums .add').click(function(){
   //      var $this = $(this),
   //          $input = $this.parent().find('input'),

   //          num = parseInt($input.val());
   //      $input.val(num+1);

   //    })

   //  $('.buy-muns .click-nums .jian').click(function(){
   //      var $this = $(this),
   //          $input = $this.parent().find('input'),
   //          num = parseInt($input.val());
   //      if(num > 1){
   //          $input.val(num-1);

   //      }else{
   //          $input.val(1);

   //      }
   //  })

   //  $('.click-nums input').keyup(function() {
   //      var n = parseInt($(this).parent().find("input").val());
   //      if(n > 1){
   //          $(this).parent().find("input").val(n);

   //      }else{
   //          $(this).parent().find("input").val(1);

   //      }
   //  });

  });

</script>
</body>
</html>


