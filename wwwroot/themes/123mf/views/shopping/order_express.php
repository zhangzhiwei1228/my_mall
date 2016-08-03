<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-7-29
 * Time: 上午10:00
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
    <style type="text/css">
        table{width: 100% !important;}


    </style>
</head>

<body style="background:#ebebeb">
<div class="n-allorders">
    <div class="n-personal-center-tit">
        <a href="javascript:void(history.back())"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
        物流信息
    </div>
    <div class="express-tracking"></div>
    <script type="text/javascript">
    </script>
</div>
<div class="add">
    <div class="add-boxab"></div>
    <div class="top">
        <span class="fl">快递公司：<label id="com"></label></span>
        <span class="fr">快递单号：<label id="nu"></label></span>
    </div>
    <div class="cen">物流信息</div>
    <div class="bot">
        <ul class="express">
            <li><p class="loading">正在查询,请稍后...</p></li>
        </ul>
    </div>
</div>
<div><a href="http://m.kuaidi100.com">快递查询</a> </div>
</body>
<script>
    $(function(){

        $.getJSON('/callback/kuaidi100/',{com:'huitongkuaidi',nu:'70087400502939'}).done(function(data){
            $('#com').html(data.com);
            $('#nu').html(data.nu);
            var html = '';
            for(var i in data.data){
                var datas=data.data[i]
                html+=  '<li>'
                html+=  '<span class="img fl"></span>'
                html+=  '<span class="jkk fl">'
                html+=  '<p class="pa"> '+ datas.context +' </p>'
                html+=  '<p class="pb"> '+ datas.time +' </p>'
                html+=  '</span>'
                html+=  '</li>'
            }
            $('.express').html(html);
            $(".add .bot").addClass("cur");
            $(".add .bot li").eq(0).find("span.img").css("background","#009933");
            $(".add .bot li").eq(0).find(".pa").css("color","#fc0000");
            $(".add .bot li").eq(0).find(".pb").css("color","#fc0000");
        });

    })

</script>
</html>
