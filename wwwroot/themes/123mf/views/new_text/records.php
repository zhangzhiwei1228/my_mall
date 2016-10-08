<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>赠送抵用券记录  
</div>

<div class="m-records">
    <ul class="list">
        <li class="n1"><a href="<? echo site_url('new_text/records_numerical')?>"><span>商家赠送免费积分记录</span></a></li>
        <li class="n2"><a href="<? echo site_url('new_text/records_volume')?>"><span>商家赠送抵用券记录</span></a></li>
    </ul>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    })
</script>
</body>
</html>