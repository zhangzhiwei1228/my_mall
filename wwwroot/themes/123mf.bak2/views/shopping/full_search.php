<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="full-search">
    	<dl class="jifen-shop fl">
    		<dt><p>获取积分商家</p><span>（以下是商家行业分类）</span></dt>
            <?php foreach($this->shopCates as $row) { ?>
            <dd><a href="<?=$this->url('shop/list?cid='.$row['id'])?>"><?=$row['name']?></a></dd>
            <?php } ?>
    	</dl>
    	<dl class="jifen-goods fl">
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
</html>


















