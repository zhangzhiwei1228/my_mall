<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<style>
    .w90 img{
        display: block;
        max-width: 100%;
    }
    .merchant-pic {
        margin-top: 40px!important;
    }

</style>
<body class="bgcolor">
    <div class="ik">
        <?php if ($_SERVER['HTTP_REFERER']) { ?>
            <span class="ikoj">
        <a href="javascript:history.go(-1);"><img src="<?php echo static_file('mobile/img/olk.png'); ?> " alt=""></a><!-- 返回 -->
        </span>
        <?php } ?>
        帮帮网：一个免费、特惠和创业的网站！
    </div>
    <div class="merchant-pic">

        <div class="swiper-container bgwhite">
            <?php $images = json_decode($this->data['ref_img'],1); $preview = $images[0]; ?>
            <?php if($images) {?>
            <div class="swiper-wrapper">
                <?php foreach((array)$images as $row) { ?>
                    <?php if(count($images) == 1) {?>
                        <img class="fl" src="<?=$this->baseUrl($row['src'])?> " width="100%" />
                    <?php } else {?>
                        <div class="swiper-slide"><img src="<?=$this->baseUrl($row['src'])?> " width="100%" /></div>
                    <?php }?>
                <?php } ?>
            </div>
            <!-- 如果需要分页器 -->
            <div class="swiper-pagination"></div>
            <?php } else {?>
                <img class="fl" src="<?php echo static_file('m/img/pic14.jpg'); ?> " width="100%" />
            <?php }?>

        </div>
    	<p class="merchant-name">&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->data['name']?></p>
    </div>
    <div class="m-phone m-text bgwhite">
    	<p class="w90"><span>电话：</span><?=$this->data['tel']?><a class="fr" href="tel:<?=$this->data['tel']?>"></a></p>
    </div>
     <div class="m-address m-text bgwhite">
    	<p class="w90"><span>地址：</span><?=$this->data['addr']?><a class="fr map" href="<?php echo $this->baseUrl('shop/map/?id='.$this->data['id'])?>" data-addr="<?php echo $this->data['addr'];?>" data-id="<?php echo $this->data['id'];?>" ></a></p>
    </div>
    <!-- <div class="m-serve m-text bgwhite">
    	<p class="w90"><span>地址：</span>WIFI</p>
    </div> -->
    <div class="m-intro m-text bgwhite">
    	<div class="intro-main w90">
    		<p class="title">商家概述：</p>
    		<p class="info"><?=$this->data['description']?></p>
    	</div>
    </div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
    var mySwiper = new Swiper ('.swiper-container', {
        loop: true,

        // 如果需要分页器
        pagination: '.swiper-pagination',
        autoplay: 2000

    });
</script>
</body>
</html>