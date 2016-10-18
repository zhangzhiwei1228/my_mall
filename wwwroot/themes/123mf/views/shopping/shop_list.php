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
        <input type="hidden" name="cid" id="cid" value="<?=$this->_request->cid?>" />
        <input type="hidden" name="sbt" id="sbt" value="<?=$this->_request->sbt?>" />
        <input type="hidden" name="q" id="q" value="<?=$this->_request->q?>" />
        <input type="hidden" name="is_special" id="is_special" value="<?=$this->_request->is_special?>" />
        <select class="tpl-pro option"></select>
        <select class="tpl-pro1 option"></select>
        <select class="tpl-pro2 option"></select>
    </span>
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
                    <p>赠送说明：<span class=""><?=$row['pro_desc']?></span></p>
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
    var cid = $('#cid').val();
    var q = $('#q').val();
    var sbt = $('#sbt').val();
    var is_special = $('#is_special').val();
    $.getJSON('/shop/getJson/').done(function(rs){
        var html1=''
        html1+='<option class="option" value="-1">请选择省</option>'
        for(var i in rs.pro){
            var datas=rs.pro[i]
            html1+='<option class="option" value="'+datas.id[0]+'">'+datas.name[0]+'</option>'
        }
        $('.tpl-pro').html(html1)
        $('.tpl-pro1').html('<option class="option" value="-1">请先选择省份</option>')
        $('.tpl-pro2').html('<option class="option" value="-1">请先选择省份</option>')
        pro_change()

    });

    function pro_change(){
        $('.tpl-pro').on('change',function(){
            var id=$(this).find('option:selected').val();
            $('.shop-list-box').html('');
            $('.shop-list-box').load('/shop/getshoplist/?area_id='+id+'&cid='+cid+'&sbt='+sbt+'&q='+q+'&is_special='+is_special);
            $('.tpl-pro2').html('<option class="option" value="-1">请先选择城市</option>')
            if(id!=-1){
                $.getJSON('/shop/getJsonCity/',{pro_id:id}).done(function(rs){
                    var html1=''
                    html1+='<option class="option" value="-1">请选择城市</option>'
                    for(var i in rs.city){
                        var datas=rs.city[i]
                        html1+='<option class="option" value="'+datas.id[0]+'">'+datas.name[0]+'</option>'
                    }
                    $('.tpl-pro1').html(html1)
                    pro_change1();
                })
            }
        })
    }
    function pro_change1(){
        $('.tpl-pro1').on('change',function(){
            var id=$(this).find('option:selected').val()
            $('.shop-list-box').html('');
            $('.shop-list-box').load('/shop/getshoplist/?area_id='+id+'&cid='+cid+'&sbt='+sbt+'&q='+q+'&is_special='+is_special);
            if(id!=-1){
                $.getJSON('/shop/getJsonRegion/',{city_id:id}).done(function(rs){
                    var html1=''
                    html1+='<option class="option" value="-1">请选择地区</option>'
                    for(var i in rs.region){
                        var datas=rs.region[i]
                        html1+='<option class="option" value="'+datas.id[0]+'">'+datas.name[0]+'</option>'
                    }
                    $('.tpl-pro2').html(html1)
                    pro_change2();
                })
            }
        })
    }
    function pro_change2(){
        $('.tpl-pro2').on('change',function(){
            var id=$(this).find('option:selected').val()
            $('.shop-list-box').html('');
            $('.shop-list-box').load('/shop/getshoplist/?area_id='+id+'&cid='+cid+'&sbt='+sbt+'&q='+q+'&is_special='+is_special);
        })
    }
</script>
</body>
</html>