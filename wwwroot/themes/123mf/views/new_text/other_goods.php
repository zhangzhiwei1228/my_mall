<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>合作商家  
</div>

<ul class="other-goodsnave f-cb">
    <li class="n1 on">
        <select name="" id="">
            <option value="">商家分类</option>
            <option value="">省份</option>
            <option value="">城市</option>
            <option value="">区县</option>
        </select>
    </li>
    <li class="n2">
        <select name="" id="">
            <option value="">省份</option>
            <option value="">省份</option>
            <option value="">城市</option>
            <option value="">区县</option>
        </select>
    </li>
    <li class="n2">
        <select name="" id="">
            <option value="">城市</option>
            <option value="">省份</option>
            <option value="">城市</option>
            <option value="">区县</option>
        </select>
    </li>
    <li class="n2">
        <select name="" id="">
            <option value="">区县</option>
            <option value="">省份</option>
            <option value="">城市</option>
            <option value="">区县</option>
        </select>
    </li>
</ul>
<div class="h20"></div>
<div class="other-goodslist f-cb">
    <ul class="list">
        <li class="f-cb">
            <div class="img fl"><img src="<? echo static_file('m/img/pic/img_6.jpg')?>" alt=""></div>
            <div class="right fr">
                <div class="na">商家名称</div>
                <div class="tel">0587-8889784636</div>
                <div class="addrs">浙江省杭州市拱墅区</div>
                <div class="cap">赠送比例说明</div>
                <div class="tit">赠送比例说明赠送比例说明赠送比例说明</div>
            </div>
        </li>
        <li class="f-cb">
            <div class="img fl"><img src="<? echo static_file('m/img/pic/img_6.jpg')?>" alt=""></div>
            <div class="right fr">
                <div class="na">商家名称</div>
                <div class="tel">0587-8889784636</div>
                <div class="addrs">浙江省杭州市拱墅区</div>
                <div class="cap">赠送比例说明</div>
                <div class="tit">赠送比例说明赠送比例说明赠送比例说明</div>
            </div>
        </li>
        <li class="f-cb">
            <div class="img fl"><img src="<? echo static_file('m/img/pic/img_6.jpg')?>" alt=""></div>
            <div class="right fr">
                <div class="na">商家名称</div>
                <div class="tel">0587-8889784636</div>
                <div class="addrs">浙江省杭州市拱墅区</div>
                <div class="cap">赠送比例说明</div>
                <div class="tit">赠送比例说明赠送比例说明赠送比例说明</div>
            </div>
        </li>
        <li class="f-cb">
            <div class="img fl"><img src="<? echo static_file('m/img/pic/img_6.jpg')?>" alt=""></div>
            <div class="right fr">
                <div class="na">商家名称</div>
                <div class="tel">0587-8889784636</div>
                <div class="addrs">浙江省杭州市拱墅区</div>
                <div class="cap">赠送比例说明</div>
                <div class="tit">赠送比例说明赠送比例说明赠送比例说明</div>
            </div>
        </li>
        <li class="f-cb">
            <div class="img fl"><img src="<? echo static_file('m/img/pic/img_6.jpg')?>" alt=""></div>
            <div class="right fr">
                <div class="na">商家名称</div>
                <div class="tel">0587-8889784636</div>
                <div class="addrs">浙江省杭州市拱墅区</div>
                <div class="cap">赠送比例说明</div>
                <div class="tit">赠送比例说明赠送比例说明赠送比例说明</div>
            </div>
        </li>
    </ul>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){
        $('.other-goodsnave li').on('click',function(){
            $(this).addClass('on').siblings().removeClass('on');
        });
    });
</script>
</body>
</html>