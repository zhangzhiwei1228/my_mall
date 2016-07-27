<!-- <div style="background:#B40001;" class="header-shopp02">
	<a class="l-shopping fl" href="#"><img src="<?php echo static_file('m/img/pic26.jpg'); ?> " width="100%" /></a>
	<a class="m-shopping fl" href="#"><img src="<?php echo static_file('m/img/pic27.jpg'); ?> " width="100%" /></a>
	<a class="r-shopping fr" href="<?php echo site_url('shopping/full_search'); ?> "><img style="width:41px; float:right;" src="<?php echo static_file('m/img/pic25.jpg'); ?> " width="100%" /></a>
	<div class="clear"></div>
</div> -->




<div class="ik">
    <?php if ($_SERVER['HTTP_REFERER']) { ?>
	<span class="ikoj">
    <a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="<?php echo static_file('mobile/img/olk.png'); ?> " alt=""></a><!-- 返回 -->
    </span>
    <?php } ?>
    帮帮网，一个免费、特惠和创业的网站.
</div>
<div style="top:30px;" class="nn-input">
        <a class="nn-new" href="<?=$this->url('default')?>">搜索导航表</a>
        <form class="nn-big-input" action="<?=$this->url('default/goods/search')?>">
            <select value="商品" name="" id="">
                <option value="商品">商品</option>
                <option value="商家">商家</option>
            </select>
            <input class="nn-kk" type="text" name="q" value="<?=$this->_request->q?>">
            <input class="nn-dis" value="" type="submit">
        </form>
        <div class="nn-big-right"><a href="<?=$this->url('default/goods')?>"><img src="<?php echo static_file('mobile/img/nimg-03.png'); ?> " alt=""></a></div>
    </div>
<div class="h-80"></div>