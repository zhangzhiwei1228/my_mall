<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>充值抵用券  
</div>

<div class="m-recharge">

    <div class="row f-cb">
        <div class="tit fl">输入要充值的抵用券</div>
        <input type="text">
    </div>
    <div class="bu-tit">支付金额：<span>2000</span>RMB</div>

    <a href="" class="butn-bot">立即支付</a>

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