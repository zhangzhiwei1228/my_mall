<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
<link rel="stylesheet" type="text/css" href="m/css/mystyle.css">
</head>
<style type="text/css">
    .good-price .text04 {
      text-decoration: line-through;
    }
    .pic {
        position: relative;
    }
    .sup-lt, .sup-ld, .sup-rt, .sup-rd {
        position: absolute;
        display: table-cell;
        background: #b40000;
        border-radius: 40px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        vertical-align: middle;
        color: #fff;
    }

    .sup-lt {
        left: 5px;
        top: 5px;
    }
    .sup-ld {
        left: 5px;
        bottom: 5px;
    }
    .sup-rt {
        right: 5px;
        top: 5px;
    }
    .sup-rd {
        right: 5px;
        bottom: 5px;
    }

</style>
<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <!-- <p class="ibuy bgwhite"><em></em>我能购买</p> -->
    <ul class="good-list product-list" style="<?php if(count($this->datalist)>0) {?>margin-bottom: 60px;<?php }?>">
        <?php foreach($this->datalist as $row) { ?>
            <?php $row = $row->_raw; ?>
            <?php if(!$row['is_selling'] && $row['is_checked'] != 2) continue; ?>
    	<li>
            <a class="bgwhite" href="<?=$this->url('./detail?id='.$row['id'])?>">
                <p class="pic">
                    <?php if ($row['sup']['lt']) { ?><i class="sup-lt"><?=$row['sup']['lt']?></i><?php } ?>
                    <?php if ($row['sup']['ld']) { ?><i class="sup-ld"><?=$row['sup']['ld']?></i><?php } ?>
                    <?php if ($row['sup']['rt']) { ?><i class="sup-rt"><?=$row['sup']['rt']?></i><?php } ?>
                    <?php if ($row['sup']['rd']) { ?><i class="sup-rd"><?=$row['sup']['rd']?></i><?php } ?>
                    <img src="<?=$row['thumb']?> " width="100%" />
                </p>
                <p class="name"><?=$this->highlight($row['title'], $this->_request->q)?></p>
                <div class="good-price">
                    <?php if ($row['skus'][0]['point1'] > 0) { ?>
                    <p class="text01">快乐积分：<span><?=$row['skus'][0]['point1']?></span>积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['point2'] > 0) { ?>
                    <p class="text01">免费积分：<span><?=$row['skus'][0]['point2']?></span>积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
                    <p class="text02">现金+免费积分：￥<span><?=$row['skus'][0]['exts']['ext1']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext1']['point']?></span>免费积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
                    <p class="text03">现金+积分币：￥<span><span><?=$row['skus'][0]['exts']['ext2']['cash']?></span>+<span><span><?=$row['skus'][0]['exts']['ext2']['point']?></span>积分币</p>
                    <?php } ?>
                    <p class="text04">原价：￥<span class=""><?=$row['skus'][0]['market_price']?></span></p>
                </div>
            </a>
        </li>
    	<?php } ?>
    </ul>
    <div class="clear"></div>
    <?php if ($this->_request->action != 'search') { ?>
    <h3>同类商品推荐</h3>
    <ul class="good-list product-list" style="margin-bottom: 60px;">
        <?php foreach($this->relateGoods as $row) { ?>
        <li>
            <a class="bgwhite" href="<?=$this->url('./detail?id='.$row['id'])?>">
                <p class="pic">
                    <?php if ($row['sup']['lt']) { ?><i class="sup-lt"><?=$row['sup']['lt']?></i><?php } ?>
                    <?php if ($row['sup']['ld']) { ?><i class="sup-ld"><?=$row['sup']['ld']?></i><?php } ?>
                    <?php if ($row['sup']['rt']) { ?><i class="sup-rt"><?=$row['sup']['rt']?></i><?php } ?>
                    <?php if ($row['sup']['rd']) { ?><i class="sup-rd"><?=$row['sup']['rd']?></i><?php } ?>
                    <img src="<?=$row['thumb']?> " width="100%" />
                </p>
                <p class="name"><?=$this->highlight($row['title'], $this->_request->q)?></p>
                <div class="good-price">
                    <?php if ($row['skus'][0]['point1'] > 0) { ?>
                    <p class="text01">快乐积分：<span><?=$row['skus'][0]['point1']?></span>积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['point2'] > 0) { ?>
                    <p class="text01">免费积分：<span><?=$row['skus'][0]['point2']?></span>积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
                    <p class="text02">现金+免费积分：￥<span><?=$row['skus'][0]['exts']['ext1']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext1']['point']?></span>免费积分</p>
                    <?php } ?>
                    <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
                    <p class="text03">现金+积分币：￥<span><span><?=$row['skus'][0]['exts']['ext2']['cash']?></span>+<span><span><?=$row['skus'][0]['exts']['ext2']['point']?></span>积分币</p>
                    <?php } ?>
                    <p class="text04">原价：￥<span class=""><?=$row['skus'][0]['market_price']?></span></p>
                </div>
            </a>
        </li>
        <?php } ?>
    </ul>
    <?php } ?>
    <!--<?php include_once VIEWS.'inc/footer_shopping.php'; ?>-->
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
    $('#cnzz_stat_icon_1259881631').hide();
    // $('.good-list li:even').css('marginRight','2%')
</script>
</body>
</html>












