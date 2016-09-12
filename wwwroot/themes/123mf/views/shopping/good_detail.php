<?php
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : base64_encode($this->url('action=detail&id='.$this->data['id']));
?>
<!DOCTYPE html>
<head>
	<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<style type="text/css">
	.good-price .text04 {
		text-decoration: line-through;
	}
	.pic {
		position: relative;
	}
	.sup-lt, .sup-ld, .sup-rt, .sup-rd {
		position: absolute;
		display: table-cell;
		background: #b40000;
		border-radius: 40px;
		width: 40px;
		height: 40px;
		line-height: 40px;
		text-align: center;
		vertical-align: middle;
		color: #fff;
	}
	.sup-lt {
		left: 5px;
		top: 5px;
	}
	.sup-ld {
		left: 5px;
		bottom: 5px;
	}
	.sup-rt {
		right: 5px;
		top: 5px;
	}
	.sup-rd {
		right: 5px;
		bottom: 5px;
	}
	.cheaper {
		padding: 0px 5px;
		background: #b40000;
		border-radius: 5px;
		color: #fff;
		font-size: 12px;
		margin-left: 5px;
	}
</style>
<body class="bgcolor">

<?php include_once VIEWS.'inc/header_shop02.php'; ?>
<div class="swiper-container bgwhite">
	<div class="swiper-wrapper">
		<?php $images = json_decode($this->data['ref_img'],1); $preview = $images[0]; ?>
		<?php foreach((array)$images as $row) { ?>
			<div class="swiper-slide">
				<p class="pic">
					<?php if ($this->sup['lt']) { ?><i class="sup-lt"><?=$this->sup['lt']?></i><?php } ?>
					<?php if ($this->sup['ld']) { ?><i class="sup-ld"><?=$this->sup['ld']?></i><?php } ?>
					<?php if ($this->sup['rt']) { ?><i class="sup-rt"><?=$this->sup['rt']?></i><?php } ?>
					<?php if ($this->sup['rd']) { ?><i class="sup-rd"><?=$this->sup['rd']?></i><?php } ?>
					<img src="<?=$this->baseUrl($row['src'])?> " width="100%" />
				</p>
			</div>
		<?php } ?>
	</div>
	<!-- 如果需要分页器 -->
	<div class="swiper-pagination"></div>
</div>
<p class="product-name bgwhite"><?=$this->data['title']?></p>
<form method="post" class="bgwhite buyform" action="<?=$this->url('cart/add')?>">
	<input type="hidden" name="goods_id" id="goods_id" value="<?=$this->data['id']?>">
	<input type="hidden" name="sku_id" id="sku_id" value="">
	<input type="hidden" name="shipping_id" value="<?=$this->data['shipping_id']?>">
	<input type="hidden" name="buynow" value="">
	<input type="hidden" name="is_vip" id="is_vip" value="<?php echo $this->user['is_vip']?>" />
	<input type="hidden" name="spec" id="spec" value="<?=$this->sku['id'] ?>" />
	<div class="boline">
		<div class="discount-box w90 f-cb">
			<span class="discount-price fl">促销价:</span>
			<label class="fl">
				<?php if ($this->data->skus[0]['point1']) { ?>
					<p class="select"><label><input type="radio" name="price_type" value="1"  />&nbsp;&nbsp;<?=$this->data->skus[0]['point1']?> 快乐积分</label></p>
				<?php } ?>
				<?php if ($this->data->skus[0]['point2']) { ?>
					<p class="select"><label><input type="radio" name="price_type" value="2" />&nbsp;&nbsp;<?=$this->data->skus[0]['point2']?> 免费积分</label></p>
				<?php } ?>
				<?php if ($this->data->skus[0]['exts']['ext1']['cash']) { ?>
					<p class="select"><label><input type="radio" name="price_type" value="3" />&nbsp;&nbsp;<?=$this->data->skus[0]['exts']['ext1']['cash']?> 元 + <?=$this->data->skus[0]['exts']['ext1']['point']?> 免费积分</label></p>
				<?php } ?>
				<?php if ($this->data->skus[0]['exts']['ext2']['cash']) { ?>
					<p class="select"><label><input type="radio" name="price_type" value="4" />&nbsp;&nbsp;<?=$this->data->skus[0]['exts']['ext2']['cash']?> 元 + <?=$this->data->skus[0]['exts']['ext2']['point']?> 积分币</label></p>
				<?php } ?>
			</label>
			<div class="nnex f-cb">
				<p class="original fl" style="width:auto;">原  价:&nbsp;&nbsp;<span>¥<?=$this->data->skus[0]['market_price']?> 元</span></p>
				<strong class="cheaper fl"><?=$this->data['notes']?></strong>
				<p class="selled fr">已售  <?=$this->data['sales_num']?>件</p>
			</div>
		</div>
	</div>
	<!-- <div class="boline">
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
    </div> -->

	<?php
	$opts = $this->data->getSkuOpts();
	foreach((array)$opts as $row) { ?>
		<div class="boline">
			<div class="good-style w90">
				<p class="style-name"><?=$row['name']?></p>
				<ul>
					<?php
					foreach($row['values'] as $k => $v) { ?>
						<li><a href="javascript:;" data-param="<?=$row['name']?>:<?=$v?>" onclick="$.buychoose(this)"></a><?=$v?></li>
					<?php } ?>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
	<?php } ?>
	<div class="boline">
		<div class="buy-muns w90">
			<span class="title fl">购买数量：</span>
			<div class="click-nums fl">
				<a href="javascript:void(0);" class="jian">-</a>
				<input type="text" name="quantity" value="1">
				<a href="javascript:void(0);" class="add">+</a>
			</div>
			<span>&nbsp;&nbsp;当前库存 <label class="sku_boline" style="color: red"><?php echo $this->sku['spec'] ? '0' : $this->sku['quantity']?></label>件</span>
			<span >&nbsp;&nbsp;总库存 <?=$this->data['quantity']?>件</span>
			<div class="clear"></div>
		</div>
	</div>
</form>
<div class="product-details-box bgwhite">
	<?php if($this->data['summary']) {?>
	<p class="product-headline"><em class="fl"></em>&nbsp;&nbsp;&nbsp;&nbsp;<span class="cn">商品描述</span>&nbsp;&nbsp;<span class="en"></span>Introduction&nbsp; details</span></p>.
	<div class="time167">
		<p><?=$this->data['summary']?></p>
	</div>
	<?php }?>
	<p class="product-headline"><em class="fl"></em>&nbsp;&nbsp;&nbsp;&nbsp;<span class="cn">产品详情</span>&nbsp;&nbsp;<span class="en"></span>Product&nbsp; details</span></p>.
	<p class="pic"><?=$this->data['description']?></p>
</div>
<div class="end163" style="width: 204px;margin-left: -92px">
	请选择分类
</div>
<input type="hidden" value="<?php echo $_SESSION['login_user_id']?>" id="is_login"/>
<!--<?php //include_once VIEWS.'inc/footer_shopping.php'; ?>-->
<?php //include_once VIEWS.'inc/footer01.php'; ?>
<?php include_once VIEWS.'inc/footer_buying.php'; ?>
<?php
echo static_file('m/js/main.js');

?>


<script type="text/javascript">

	function limit(){
		var avId = new Array();
		var good_id = $('#goods_id').val();
		$(".bgwhite ul li").each(function(i,el){
			var $el = $(el);
			if ($el.hasClass('goodstylecurr')) {
				avId.push("[" + $(this).children().attr("data-param")+"]");
			};
		});
		var str_avId=avId.join();
		$.get('/goods/getgoodsku/', {param:str_avId,good_id:good_id}, function(data){
			var quantity = data.quantity;
			if(quantity !=0 && quantity != 'error') {
				$('.sku_boline').text(data.quantity);
				$('#sku_id').val(data.sku_id);
			} else {
				if(quantity == 'error') {
					$('.sku_boline').text(0);
				} else {
					$('.sku_boline').text(0);
					$(".end163").show().text('所选已经售空，请选择其他规格的').css('font-size','12px');
					setTimeout(function(){
						$('.end163').hide();
					},1000);
				}
			}
		},'json');
	}


	$(function(){
		$(".footer-buying-box li").eq(0).addClass('cur');
	})
	// $('.btn-addcart').on('click', function(){
	// 	$('.buyform').submit();
	// });

	// $('.btn-buynow').on('click', function(){
	// 	$('.buyform').submit();
	// });
</script>

<script type="text/javascript">
	var mySwiper = new Swiper ('.swiper-container', {
		loop: true,

		// 如果需要分页器
		pagination: '.swiper-pagination',
		autoplay: 2000

	});
	$('.discount-box label .select label').click(function(){
		$(this).addClass('selectcurr').siblings('.select').removeClass('selectcurr');
		$(this).css("color","#fc0000");

		$(this).parents("p").siblings('p').find('label').css("color","#777");
	});

	$('.good-style ul li').click(function(){
		$(this).addClass('goodstylecurr').siblings('li').removeClass('goodstylecurr');
		limit();
	});

	$('input:radio[name="price_type"]').click(function(){
		$(".bgwhite .boline").eq(0).css("border","0px none");
		$(".bgwhite .boline").eq(0).css("border-bottom","1px solid #f1f0f0");
	});

	$(".click-buy").click(function(){
		var val=$('input:radio[name="price_type"]:checked').val();
		var flag = 1;
		var is_vip = $('#is_vip').val();
		var is_login = $('#is_login').val();
		var quantity = $('.sku_boline').text();
		if(!is_login) {
			flag = 0;
			$(".end163").show().text('您还没有登录，请先登录');
			setTimeout(function(){
				window.location.href='<?php echo site_url('usercp/passport/login/?ref='.$ref);?>';
			},3000);
			return false;
		}
		if(is_vip != 1) {
			flag = 0;
			$(".end163").show().text('您还没有激活，请先激活');
			setTimeout(function(){
				window.location.href='<?php echo site_url('usercp/vip/');?>';
			},3000);
			return false;
		}

		if(val==null){
			$("html,body").stop().animate({scrollTop:$(".bgwhite .boline").eq(0).offset().top - 90});
			$(".bgwhite .boline").eq(0).css("border","2px solid #fc0000");
			flag = 0;
		} else {
			if($("html,body").hasClass('.style-name')) {
				$("html,body").stop().animate({scrollTop:$(".style-name").eq(0).offset().top - 90});
			}
			//$("html,body").stop().animate({scrollTop:$(".style-name").eq(0).offset().top - 90});
			flag = 1;
		}

		$(".good-style ul").each(function(){
			if ($(this).children('li').hasClass('goodstylecurr')) {
			}else{
				$(this).parents(".boline").css("border","2px solid #fc0000");
				setTimeout(function(){
					$(this).css("border","0px none");
				},3000);
				flag = 0;
				$(".end163").show();
				setTimeout(function(){
					$(".end163").hide();
				},3000);
				flag = 0;
			}
		});
		if(quantity == 0 && flag) {
			flag = 0;
			$(".end163").show().text('当前规格已售空');
			setTimeout(function(){
				$(".end163").hide();
			},3000);
			return false;
		}
		if (flag) {
			$('[name=buynow]').val(1);
			<?php
                $_SESSION['pay_confirm_login'] = true;
                $_SESSION['pay_confirm_login_url'] = true;
            ?>
			$('.buyform').submit();
		}


		//$('.buyform').submit();
	})

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

	$(".btn-addcart").click(function(){
		var flag = 1;
		var val=$('input:radio[name="price_type"]:checked').val();
		var is_vip = $('#is_vip').val();
		var is_login = $('#is_login').val();
		var quantity = $('.sku_boline').text();
		if(!is_login) {
			flag = 0;
			$(".end163").show().text('您还没有登录，请先登录');
			setTimeout(function(){
				window.location.href='<?php echo site_url('usercp/passport/login/');?>';
			},3000);
			return false;
		}
		if(is_vip != 1) {
			flag = 0;
			$(".end163").show().text('您还没有激活，请先激活');
			setTimeout(function(){
				window.location.href='<?php echo site_url('usercp/vip/');?>';
			},3000);
			return false;
		}

		if(val==null){
			$("html,body").stop().animate({scrollTop:$(".bgwhite .boline").eq(0).offset().top - 90});
			$(".bgwhite .boline").eq(0).css("border","2px solid #fc0000");
			flag = 0;
		} else {
			if($("html,body").hasClass('.style-name')) {
				$("html,body").stop().animate({scrollTop:$(".style-name").eq(0).offset().top - 90});
			}
			flag = 1;
		}
		$(".good-style ul").each(function(){
			if ($(this).children('li').hasClass('goodstylecurr')) {
			}else{
				$(this).parents(".boline").css("border","2px solid #fc0000");
				setTimeout(function(){
					$(this).css("border","0px none");
				},3000);
				flag = 0;
				$(".end163").show().text('请选择分类');
				setTimeout(function(){
					$(".end163").hide();
				},3000);
				flag = 0;
			}
		});
		if(quantity == 0 && flag) {
			flag = 0;
			$(".end163").show().text('当前规格已售空');
			setTimeout(function(){
				$(".end163").hide();
			},3000);
			return false;
		}
		if (flag) {
			$('[name=buynow]').val(0);
			$('.buyform').submit();
		}
	});

	$(".good-style ul li").click(function(){
		$(this).parents(".boline").css("border","0px none")
	})

</script>
</body>
</html>


