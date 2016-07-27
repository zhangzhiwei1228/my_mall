{\rtf1}<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="full-search">
    	<dl style="width:100%" class="jifen-shop fl">
    		<dt><p>获取积分商家</p><span>（以下是商家行业分类）</span></dt>
            <?php foreach($this->shopCates as $row) { ?>
            <dd><a href="<?=$this->url('shop/list?cid='.$row['id'])?>"><?=$row['name']?></a></dd>
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
		$(".jifen-shop dd a").css("padding","0px");
		$(".jifen-shop dd a").css("text-align","center");
	})
</script>
</html>