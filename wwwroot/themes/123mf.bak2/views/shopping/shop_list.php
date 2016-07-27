<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor positre">
   <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div class="positre-bg"></div>
    <div class="shop-select bgwhite">
    	<span class="click-down option">商家分类</span>
    	<select  class="option">
		  	<option value ="">省份</option>
		  	<option value ="">xxx</option>
		</select>
		<select  class="option">
		  	<option value ="">城市</option>
		  	<option value ="">xxx</option>
		</select>
		<select  class="option">
		  	<option value ="">区县</option>
		  	<option value ="">xxx</option>
		</select>
    </div>
    <dl class="drop-down">
    	<dt class="bgcolor">全部分类</dt>
    	<dd><a href="#">酒店</a></dd>
    	<dd><a href="#">餐饮</a></dd>
    	<dd><a href="#">酒店</a></dd>
    	<dd><a href="#">餐饮</a></dd>
    	<dd><a href="#">酒店</a></dd>
    	<dd><a href="#">餐饮</a></dd>
    </dl>
    <ul class="shop-list-box">
        <?php foreach($this->datalist as $row) { ?>
    	<li class="shop-main w90 bgwhite">
    		<a href="<?=$this->url('./detail?id='.$row['id'])?>">
    			<img class="fl" src="<?=$this->baseUrl($row['thumb'])?> ">
    			<div class="intro fr">
    				<p class="name"><?=$row['name']?></p>
    				<p class="phone"><em></em><span><?=$row['tel']?></span></p>
    				<p class="address"><em></em><span class=""><?=$row['addr']?></span></p>
    			</div>
    			<div class="clear"></div>
    		</a>
    	</li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
        <!--<?php include_once VIEWS.'inc/footer_shopping.php'; ?>-->
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('.click-down').click(function(){
		$(this).parent('.shop-select').siblings('.drop-down').slideToggle('slow');
		$(this).parent('.shop-select').siblings('.positre-bg').show();
	});
	$('.positre-bg').click(function(){
		$(this).hide();
		$(this).siblings('.drop-down').hide();
	});
</script>
</body>
</html>
