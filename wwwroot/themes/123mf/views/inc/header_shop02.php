<!-- <div style="background:#B40001;" class="header-shopp02">
	<a class="l-shopping fl" href="#"><img src="<?php echo static_file('m/img/pic26.jpg'); ?> " width="100%" /></a>
	<a class="m-shopping fl" href="#"><img src="<?php echo static_file('m/img/pic27.jpg'); ?> " width="100%" /></a>
	<a class="r-shopping fr" href="<?php echo site_url('shopping/full_search'); ?> "><img style="width:41px; float:right;" src="<?php echo static_file('m/img/pic25.jpg'); ?> " width="100%" /></a>
	<div class="clear"></div>
</div> -->
<style type="text/css">
.search-tips {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 150px;
    height: 80px;
    margin-top: -40px;
    margin-left: -75px;
    background-color: #C0C0C0;
    font-size: 16px;
    text-align: center;
    line-height: 80px;
    color: #494949;
    border-radius: 7px;
    display: none;
}
</style>


<div class="ik">
    <?php if ($_SERVER['HTTP_REFERER']) { ?>
	<span class="ikoj">
    <a href="<?php if(isset($this->_request->ref)) { echo $this->_request->ref;} else {echo 'javascript:window.history.back(-1);';}?>" target="_blank" ><img src="<?php echo static_file('mobile/img/olk.png'); ?> " alt=""></a><!-- 返回 -->
    </span>
    <?php } ?>
    帮帮网：一个免费、特惠和创业的网站！
</div>
<div style="top:40px;" class="nn-input">
        <a class="nn-new" href="<?=$this->url('default')?>">搜索导航表</a>
        <?php $this->_request->sbt = $this->_request->sbt ? $this->_request->sbt : 1;?>
        <form class="nn-big-input" action="<?=$this->url($this->_request->sbt==1?'default/goods/search':'default/shop/search')?>">
            <select name="sbt">
                <option value="1" <?=$this->_request->sbt==1?'selected':''?>>商品</option>
                <option value="2" <?=$this->_request->sbt==2?'selected':''?>>商家</option>
            </select>
            <input class="nn-kk" type="text" name="q" value="<?=$this->_request->q?>">
            <input class="nn-dis" value="" type="submit">
        </form>
        <div class="nn-big-right"><a href="<?=$this->url('default/goods')?>"><img src="<?php echo static_file('mobile/img/nimg-03.png'); ?> " alt="" style="width: 37px;height: 32px;margin-top: -7px"></a></div>
    </div>
<div class="h-80"></div>
<div class="search-tips" style="display: none; z-index:3;">
    请输入查询的内容
</div>
<script type="text/javascript">
    $('[name=sbt]').on('change', function(){
        var v = parseInt($(this).val());
        switch(v) {
            case 1:
                $('.nn-big-input').prop('action', '<?=$this->url('default/goods/search')?>');
                break;
            case 2:
                $('.nn-big-input').prop('action', '<?=$this->url('default/shop/search')?>');
                break;
        }
    });

    $('.nn-dis').click(function(){
      var _shu = $('.nn-kk').val();
      if( _shu == '' || _shu == null) {
            $('.search-tips').show();
            setTimeout(function(){
                $('.search-tips').hide();
            },3000);
            return false;
        }
    });
</script>