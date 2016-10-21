<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<style type="text/css">
    /*.pic {
        position: relative;
    }*/
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
    .cheaper{
    	    padding: 0px 5px;
	    background: #b40000;
	    border-radius: 5px;
	    color: #fff;
	    font-size: 12px;
	    margin-left: 5px;
    }
	.n-pro-l ul{
		float: none;
	}
</style>

<body>
	<?php include_once VIEWS.'inc/header-z.php'; ?>
    <div class="n-big">
    <div class="n-banner">
    	<div class="n-gd"><!-- 全球购物低价商城！免费物品商城 --></div>
    	<div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx1', '<div class="swiper-slide"><a href="">%s</a></div>')?>
    	</div>
    	<div class="hd-1"></div>
    </div>

    <div class="n-h5"></div>
    <!--暂时隐藏视频模块 <div class="n-same" onclick="window.location = '<?=$this->url('controller=page&action=detail&code='.$this->video['code'])?>'">
    	<div class="n-smae-l">
            <a href="<?=$this->url('controller=page&action=detail&code='.$this->video['code'])?>">视频说明</a>
            <p><?=$this->cutstr($this->video['content'], 70)?></p>
        </div>
    	<div class="n-same-r"></div>
    </div>
    <div class="n-h5"></div>
   
    <div class="vv-video-box">
        <div class="hd">
            <ul>
                <li><a href="javascript:;">了解平台视频</a></li>
                <li><a href="javascript:;">优惠活动视频</a></li>
                <li><a href="javascript:;">获取积分视频</a></li>
                <li><a href="javascript:;">公共视频</a></li>
                <li><a href="javascript:;">公共视频</a></li>
            </ul>
        </div>
        <div class="bd">
            <ul>
                <li>
                    <div class="vv-bd-l">
                       <table>
                           <tr>    
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                       </table>
                    </div>
                    <div class="vv-bd-r">
                        <img src="<?php echo static_file('mobile/img/img-06.jpg'); ?> " alt="">
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="vv-bd-l">
                       <table>
                           <tr>    
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                       </table>
                    </div>
                    <div class="vv-bd-r">
                        <img src="<?php echo static_file('mobile/img/img-06.jpg'); ?> " alt="">
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="vv-bd-l">
                       <table>
                           <tr>    
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                       </table>
                    </div>
                    <div class="vv-bd-r">
                        <img src="<?php echo static_file('mobile/img/img-06.jpg'); ?> " alt="">
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="vv-bd-l">
                       <table>
                           <tr>    
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                       </table>
                    </div>
                    <div class="vv-bd-r">
                        <img src="<?php echo static_file('mobile/img/img-06.jpg'); ?> " alt="">
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="vv-bd-l">
                       <table>
                           <tr>    
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                            <tr>
                               <td data-url="<?php echo site_url('ajax/welcome-ajax'); ?>">了解平台视频</td>
                           </tr>
                       </table>
                    </div>
                    <div class="vv-bd-r">
                        <img src="<?php echo static_file('mobile/img/img-06.jpg'); ?> " alt="">
                    </div>
                </li>
            </ul>
        </div>
    </div> -->

    <div class="n-h5"></div>
    <div class="n-same" onclick="window.location = '<?=$this->url('controller=page&action=detail&code='.$this->todaynews['code'])?>'">
        <div class="n-smae-l">
            <a href="<?=$this->url('controller=page&action=detail&code='.$this->todaynews['code'])?>">今日消息</a>
            <p><?=$this->cutstr($this->todaynews['content'], 62)?></p>
        </div>
        <div class="n-same-r"></div>
    </div>
     <div class="n-h5"></div>

    <div class="n-same" onclick="window.location = '<?=$this->url('controller=page&action=detail&code='.$this->description['code'])?>'">
        <div class="n-smae-l">
            <a href="<?=$this->url('controller=page&action=detail&code='.$this->description['code'])?>">商城说明</a>
            <p><?=$this->cutstr($this->description['content'], 62)?></p>
        </div>
        <div class="n-same-r"></div>
    </div>
    <div class="n-h5"></div>


    <div class="h20"></div>
    <div class="img-box">
        <a href="<?php echo $this->url('usercp/money/purchase')?>"><img src="<? echo static_file('m/img/pic/img_3.jpg')?>" alt="" style="width: 100%;"></a>
    </div>

    <div class="n-h5"></div>
    <div class="n-lise-16 clear">
        <?php 
        $cn = array('一','二','三','四');//去掉了,'五','六','七','八'
        $i=0; foreach($this->quickLinks as $key => $row) { ?>
    	<span>

            <a href="<?=$this->url($row['redirect'])?>" <?=$row['is_blank']?'target="_blank"':''?>>

                <div style="color:red"><?=$cn[$i]?></div>
        		<img src="<?php echo static_file($row['icon']); ?> " alt="">
        		<p class="n-lise-666"><?=$row['name']?></p>
                <?php if ($row['description']) { ?>
        		<p class="n-lise-96">(<?=$row['description']?>)</p>
                <?php } ?>
            </a>
    	</span>
        <?php $i++; } ?>
    </div>

     <div class="n-h5"></div>
    <div class="n-banner2">
        <div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx-a0', '<div class="swiper-slide"><a href="">%s</a></div>')?>
        </div>
        <div class="hd2"></div>
    </div>
    <div class="n-js2">
        <div class="n-banner3">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-b0', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd3"></div>
        </div>
        <div class="n-banner4">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-c0', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd4"></div>
        </div>
    </div>
    
    <div class="n-banner5 clear">
        <div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx-d0', '<div class="swiper-slide"><a href="">%s</a></div>')?>
        </div>
    </div>

    <div class="n-h5"></div>
    <!--<div class="n-same" onclick="window.location = '<?/*=$this->url('controller=page&action=detail&code='.$this->guide['code'])*/?>'">
    <div class="n-smae-l">
        <a href="<?/*=$this->url('controller=page&action=detail&code='.$this->guide['code'])*/?>">商城指南</a>
        <p><?/*=$this->cutstr($this->guide['content'], 62)*/?></p>
    </div>
    <div class="n-same-r"></div>
    </div>
    <div class="n-h5"></div>-->

    <?php foreach($this->recShop as $key => $row) {?>
        <?php if($row['shops']) {?>
        <div class="n-shop">
            <div class="n-shop-r"><?php echo $row['name']?></div>
            <div class="n-shop-l">

                <ul>
                    <?php foreach($row['shops'] as $shop) { ?>
                        <li><a href="<?=$this->url('/shop/detail?id='.$shop['id'])?>"><img src="<?php echo $shop['ref_img_bg'] ? $this->baseUrl($shop['ref_img_bg']) : $this->baseUrl($shop['thumb'])?> " alt="<?=$shop['name']?>"></a></li>
                    <?php } ?>

                    <li class="n-shop-l-te"><a href="<?=$this->url("/shop/list/?is_special={$key}")?> ">更多</a></li>
                </ul>
            </div>
        </div>
        <div class="n-h5"></div>
        <?php }?>
    <?php }?>
    <div class="n-banner2">
        <div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx-a1', '<div class="swiper-slide"><a href="">%s</a></div>')?>
        </div>
        <div class="hd2"></div>
    </div>
    <div class="n-js2">
        <div class="n-banner3">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-b1', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd3"></div>
        </div>
        <div class="n-banner4">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-c1', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd4"></div>
        </div>
    </div>

    <div class="n-h5"></div>
    
    <!--特殊商家隐藏 <div class="n-shop">
        <div class="n-shop-r">获取免费积分特殊商家</div>
        <div class="n-shop-l">
            <ul>
                <?php foreach($this->specialShop as $row) { ?>
                <li><a href="<?=$this->url('/shop/detail?id='.$row['id'])?>"><img src="<?=$this->baseUrl($row['thumb'])?> " alt="<?=$row['name']?>"></a></li>
                <?php } ?>
                
                <li class="n-shop-l-te"><a href="<?=$this->url('/shop/list/?is_special=1')?> ">更多</a></li>
            </ul>
        </div>
    </div> -->

    <!--特殊商家广告隐藏 <div class="n-h5"></div>
    <div class="n-banner2">
        <div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx-a2', '<div class="swiper-slide"><a href="">%s</a></div>')?>
        </div>
        <div class="hd2"></div>
    </div>
    <div class="n-js2">
        <div class="n-banner3">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-b2', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd3"></div>
        </div>
        <div class="n-banner4">
            <div class="swiper-wrapper">
                <?=$this->advert->getByCode('wap-idx-c2', '<div class="swiper-slide"><a href="">%s</a></div>')?>
            </div>
            <div class="hd4"></div>
        </div>
    </div> -->
    

	<div class="n-h5"></div>
    <?php foreach($this->recGoodsCates as $cate) { ?>
    <div class="n-cheap">
    	<span><?=$cate['name']?></span>
    	<!-- <p>商城说明</p> -->
    	<a href="<?=$this->url('/page/detail?code=cate-desc-'.$cate['id'])?>">商城说明></a>
    </div>
    <div class="n-banner8 clear">
    	<div class="swiper-wrapper">
            <?=$this->advert->getByCode('wap-idx-a'.$cate['id'], '<div class="swiper-slide"><a href="">%s</a></div>')?>
	    </div>
	    <div class="hd8"></div>
    </div>
    <div class="n-js2">
        <div class="n-banner11 swiper-container-horizontal">
            <div class="swiper-wrapper" style="transition: 0ms; -webkit-transition: 0ms; transform: translate3d(0px, 0px, 0px);">
                <?=$this->advert->getByCode('wap-idx-b'.$cate['id'], '<div class="swiper-slide" style="width: 179px; margin-right: 30px;"><a href="">%s</a></div>')?>
            </div>
            <div class="hd11 swiper-pagination-clickable"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
        </div>
        <div class="n-banner12 swiper-container-horizontal">
            <div class="swiper-wrapper" style="transition: 0ms; -webkit-transition: 0ms; transform: translate3d(0px, 0px, 0px);">
                <?=$this->advert->getByCode('wap-idx-c'.$cate['id'], '<div class="swiper-slide" style="width: 179px; margin-right: 30px;"><a href="">%s</a></div>')?>
            </div>
            <div class="hd12 swiper-pagination-clickable"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
        </div>
    </div>
    <div class="n-pro">
        <?php foreach($cate->goods as $row) { ?>
    	<div class="n-pro-l">
    		<span class="n-pro-img">
                <?php if ($row['sup']['lt']) { ?><i class="sup-lt"><?=$row['sup']['lt']?></i><?php } ?>
                <?php if ($row['sup']['ld']) { ?><i class="sup-ld"><?=$row['sup']['ld']?></i><?php } ?>
                <?php if ($row['sup']['rt']) { ?><i class="sup-rt"><?=$row['sup']['rt']?></i><?php } ?>
                <?php if ($row['sup']['rd']) { ?><i class="sup-rd"><?=$row['sup']['rd']?></i><?php } ?>
    			<a href="<?=$this->url('goods/detail?id='.$row['id'])?> "><img src="<?=$this->baseUrl($row['thumb'])?> " alt=""></a>
    		</span>
    		<p class="n-pro-l-tit"><?=$row['title']?></p>
    		<div class="n-pro-l-te">
    			<ul>
                    <?php if ($row['skus'][0]['point1']) { ?>
                    <li><strong>快乐积分：</strong><span><?=$row['skus'][0]['point1']?></span><p>快乐积分</p></li>
                    <?php } ?>
                    <?php if ($row['skus'][0]['point2']) { ?>
                    <li><strong>免费积分：</strong><span><?=$row['skus'][0]['point2']?></span><p>免费积分</p></li>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
                    <li><strong>现金+免费积分:</strong><span><?=$row['skus'][0]['exts']['ext1']['cash']?><font color="#666">元</font></span><p>+</p><span><?=$row['skus'][0]['exts']['ext1']['point']?></span><p>积分</p></li>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
                    <li><strong>现金+积分币:</strong><span><?=$row['skus'][0]['exts']['ext2']['cash']?><font color="#666">元</font></span><p>+</p><span><?=$row['skus'][0]['exts']['ext2']['point']?></span><p>积分币</p></li>
                    <?php } ?>
                    <li><del ><strong  style="text-decoration: line-through;">原价：￥</strong><span  style="text-decoration: line-through;"><?=$row['skus'][0]['market_price']?></span><p  style="text-decoration: line-through;">元</p></del><del class="fr"><strong class="cheaper"><?php echo $row['notes'];?></strong></del></li>
                    
    			</ul>
    		</div>
    	</div>
        <?php } ?>
    </div>
    <div class="n-more"><a href="<?=$this->url('goods/channel?cid='.$cate['id'])?> ">更多</a></div>
    <div class="n-h5"></div>
    <div class=""></div>
    <?php } ?>
    </div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
	<?php echo static_file('mobile/js/swiper.min.js'); ?>
	<?php echo static_file('mobile/css/swiper.min.css'); ?>
    <?php echo static_file('mobile/js/jquery.SuperSlide.2.1.1.js'); ?>
    <?php echo static_file('mobile/js/plug.preload.js'); ?>
</body>
<script>
	$(function(){
        // $(".n-pro").each(function(){
        //     var NewUrl = "<?php echo site_url('ajax/wel_ajax');?>"
        //     var k = $(this).height() * 0.5

        //     if($(window).scrollTop() >= $(this).offset().top + k) {
        //         $.ajax({
        //             url: NewUrl
        //             success:function(e){
        //                 if(!e) {
        //                     flag = false ;
        //                     $('.n-pro').append('<p style="text-align: center">已加载完毕<p>');
        //                     return false;
        //                 }
        //                 $('.n-pro').append(e);
        //                 n++;
        //                 setTimeout(function(){
        //                     flag=true;
        //                 },300)
        //             }
        //         })  
        //     }
        // }); 

        // $(".vv-bd-l span").click(function(){
        // var url =$(this).data("url")        
        // $(".vv-bd-r").load(url);


        $(".vv-bd-l table td").click(function(){
           var url = $(this).data("url");  
           var $prent= $(this).parents('.vv-bd-l');
           console.log(url)     
           $(".vv-bd-r").load(url,function(){
                var src = $prent.next().find('img').attr('src');
                _PreLoadImg([
                    src
                ],function(){
                    $prent.height($prent.next().find('img').height())
                })
                
           });
        })


        jQuery(".vv-video-box").slide({trigger:"click"});

		var swiper = new Swiper('.n-banner', {
        pagination: '.hd-1',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false,
    	});

    	var swiper = new Swiper('.n-banner2', {
        pagination: '.hd2',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner3', {
        pagination: '.hd3',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 8000,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner4', {
        pagination: '.hd4',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 8000,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner5', {
        slidesPerView: 4,
        paginationClickable: true,
        autoplay: 8000,

    	});


    	var swiper = new Swiper('.n-banner6', {
        pagination: '.hd6',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner7', {
        pagination: '.hd7',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner8', {
        pagination: '.hd8',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    	});

    	var swiper = new Swiper('.n-banner9', {
        pagination: '.hd9',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
    	});


        var swiper = new Swiper('.n-banner10', {
        pagination: '.hd10',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner11', {
        pagination: '.hd11',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner12', {
        pagination: '.hd12',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner13', {
        pagination: '.hd13',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner14', {
        pagination: '.hd14',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner15', {
        pagination: '.hd15',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });
        

        var swiper = new Swiper('.n-banner16', {
        pagination: '.hd16',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });



        var swiper = new Swiper('.n-banner17', {
        pagination: '.hd17',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });
        

        var swiper = new Swiper('.n-banner18', {
        pagination: '.hd18',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        

        var swiper = new Swiper('.n-banner19', {
        pagination: '.hd19',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });
        

        var swiper = new Swiper('.n-banner20', {
        pagination: '.hd20',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });
        

        var swiper = new Swiper('.n-banner21', {
        pagination: '.hd21',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner22', {
        pagination: '.hd22',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });
        

        var swiper = new Swiper('.n-banner23', {
        pagination: '.hd23',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


        var swiper = new Swiper('.n-banner24', {
        pagination: '.hd24',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner25', {
        pagination: '.hd25',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner26', {
        pagination: '.hd26',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner27', {
        pagination: '.hd27',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner28', {
        pagination: '.hd28',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner29', {
        pagination: '.hd29',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner30', {
        pagination: '.hd30',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner31', {
        pagination: '.hd31',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner32', {
        pagination: '.hd32',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner33', {
        pagination: '.hd33',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner34', {
        pagination: '.hd34',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner35', {
        pagination: '.hd35',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner36', {
        pagination: '.hd36',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner37', {
        pagination: '.hd37',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner38', {
        pagination: '.hd38',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner39', {
        pagination: '.hd39',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });

        var swiper = new Swiper('.n-banner40', {
        pagination: '.hd40',
        paginationClickable: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: 2500,
        autoplayDisableOnInteraction: false
        });


	})
        _PreLoadImg([
            "<?php echo static_file('mobile/img/img-06.jpg'); ?>"
        ],function(){
            $(".vv-bd-l").height($(".vv-bd-r").height()-0);
        })
</script>

</html>