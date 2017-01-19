<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">
        <div class="bread w1190">
            <ul>
                <li><a href="">首页</a></li>
                <li>></li>
                <li><a href="">个人中心</a></li>
            </ul>
        </div>
        <div class="recharge-game-money w1190">
            <?php include_once VIEWS.'inc/member_side.php'; ?>
            <div class="fr">
                <div class="tit">升级四星分销商</div>
                <div class="what-img">
                    <img src="<?php echo static_file('web/img/img-46.jpg'); ?> " alt="">
                </div>
                <div class="input-b">
                    <input class="inputa" value="立即申请" type="submit">
                </div>
            </div>
        </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
    $(function(){
        $(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');

        
    })
</script>
</html>