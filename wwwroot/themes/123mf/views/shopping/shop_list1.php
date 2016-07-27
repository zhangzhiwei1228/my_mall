<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor positre">
   <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="positre-bg"></div>
    <form class="shop-select bgwhite" method="get">
    	<span class="click-down option"><?=$this->category->exists()?$this->category['name']:'商家分类'?></span>
        <span class="JS_Dmenu form-inline">
            <input type="hidden" name="area_id" value="<?=$this->_request->area_id?>" />
        </span>
    	<!-- <select  class="option">
		  	<option value ="">省份</option>
		  	<option value ="">xxx</option>
		</select>
		<select  class="option">
		  	<option value ="">城市</option>
		  	<option value ="">xxx</option>
		</select>
		<select  class="option">
		  	<option value ="">区县</option>
		  	<option value ="">xxx</option>
		</select> -->
    </form>
    <dl class="drop-down">
    	<dt class="bgcolor">全部分类</dt>
        <?php
        $cates = M('Shop_Category')->select()
            ->where('parent_id = 0')
            ->order('rank ASC, id ASC')
            ->fetchRows();
        foreach($cates as $row) { ?>
    	<dd><a href="<?=$this->url('&cid='.$row['id'])?>"><?=$row['name']?></a></dd>
        <?php } ?>
    </dl>
    <ul class="shop-list-box" style="margin-bottom: 60px">
        <?php foreach($this->datalist as $row) { ?>
    	<li class="shop-main w90 bgwhite">
    		<a href="<?=$this->url('./detail?id='.$row['id'])?>">
                <?php if($row['ref_img_bg']) {?>
                    <img class="fl" src="<?=$this->baseUrl($row['ref_img_bg'])?> "  />
                <?php } else {?>
                    <img class="fl" src="<?php echo static_file('m/img/pic14.jpg'); ?> " />
                <?php }?>
    			<div class="intro fr">
    				<p class="name"><?=$row['name']?></p>
    				<p class="phone"><em></em><span><?=$row['tel']?></span></p>
    				<p class="address"><em></em><span class=""><?=$row['addr']?></span></p>
    			</div>
    			<div class="clear"></div>
    		</a>
    	</li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('.click-down').click(function(){
		$(this).parent('.shop-select').siblings('.drop-down').slideToggle('slow');
		$(this).parent('.shop-select').siblings('.positre-bg').show();
	});
	$('.positre-bg').click(function(){
		$(this).hide();
		$(this).siblings('.drop-down').hide();
	});
</script>
<style type="text/css">
    .h-80{
        height:143px;
        background: #EBEBEB;
    }
    .shop-select {
        width: 100%;
        height: 45px;
        line-height: 45px;
        position: fixed;
        top: 90px;
        left: 0px;
        z-index: 999;
    }
</style>
<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
<script type="text/javascript">
    seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
        dmenu.init('.JS_Dmenu', {
            rootId: 1,
            script: '/misc.php?act=area',
            htmlTpl: '<select class="option"></select>',
            firstText: '请选择所在地',
            defaultText: '请选择',
            selected: $('input[name=area_id]').val(),
            callback: function(el, data) {
                var location = $('.JS_Dmenu>select>option:selected').text();
                $('input[name=area_id]').val(data.id > 0 ? data.id : 0);
                $('.option').bind('change', function(){
                    $('form.shop-select').submit();
                });
            }
        });

    });

    $(function(){

        var url = <?php echo "'".site_url('ajax/g-js')."'"; ?> ;
        $(".shop-list-box").load(url)


        $(".form-inline option").click(function(){
                var Html = $.ajax({
                url   : '<?php echo site_url('ajax/g-js'); ?> ',
                async : false
                })
        $(".shop-list-box").html(Html.responseText)

        })
    })


</script>
</body>
</html>
