<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="merchant-pic">
    	<img src="<?php echo static_file('m/img/pic14.jpg'); ?> " />
    	<p class="merchant-name">&nbsp;&nbsp;&nbsp;&nbsp;<?=$this->data['name']?></p>
    </div>
    <div class="m-phone m-text bgwhite">
    	<p class="w90"><span>电话：</span><?=$this->data['tel']?><a class="fr" href="tel:<?=$this->data['tel']?>"></a></p>
    </div>
     <div class="m-address m-text bgwhite">
    	<p class="w90"><span>地址：</span><?=$this->data['addr']?><a class="fr" href="#"></a></p>
    </div>
    <!-- <div class="m-serve m-text bgwhite">
    	<p class="w90"><span>地址：</span>WIFI</p>
    </div> -->
    <div class="m-intro m-text bgwhite">
    	<div class="intro-main w90">
    		<p class="title">商家概述：</p>
    		<p class="info"><?=$this->data['description']?></p>
    	</div>
    </div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>