<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>核销成功
</div>

<div class="m-verifi">
    <div class="g-box">
        <div class="tit">
            <p>账号：<?php echo $this->account['username']?></p>
<!--            <p>账号：15868813334</p>-->
        </div>
        <div class="bod">核销成功</div>
        <div class="botm">
            <p><?php echo $this->glod['privilege']?>元抵用金</p>
        </div>
        <a href="<?php echo $this->url('/agent')?>" class="butn-bot">返回</a>
    </div>
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