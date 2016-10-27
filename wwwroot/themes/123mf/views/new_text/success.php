<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #fff">

<div class="n-personal-center-tit">
    <a href="<?php echo $this->url('default')?>"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>购买成功
</div>

<div class="success">
    <div class="img"><img src="<? echo static_file('m/img/pic/img_18.png')?>" alt=""></div>
    <div class="tit">您的兑换码为</div>
    <div class="tit"><?php echo $this->code?></div>
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