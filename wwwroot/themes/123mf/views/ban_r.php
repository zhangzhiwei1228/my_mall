<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="full-search">
        <dl style="width:100%;"  class="jifen-goods fl">
            <dt><p>积分兑换商品</p><span>（以下八大商城）</span></dt>
            <?php foreach($this->goodsCates as $row) { ?>
            <dd><a href="<?=$this->url('./channel?cid='.$row['id'])?>"><?=$row['name']?></a></dd>
            <?php } ?>
        </dl>
    </div>
    <div class="n-h60"></div>
    <!--<?php include_once VIEWS.'inc/footer_shopping.php'; ?>-->
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
    echo static_file('web/js/main.js');
?>
</body>
<script>
    $(function(){
        $(".jifen-goods dd a").css("padding","0px");
        $(".jifen-goods dd a").css("text-align","center");
    })
</script>
</html>
