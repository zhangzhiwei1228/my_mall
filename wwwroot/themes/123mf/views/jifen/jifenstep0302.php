<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic22.jpg'); ?> " width="100%"></div>
    <div class="jifen-step03 bgwhite">
	    <div class="step03-main ">
	    	<dl>
	    		<dt class="sure"></dt>
	    		<dd class="sure-info">已经给<?=$this->account['nickname']?>(<?=$this->account['username']?>)用户</dd>
	    		<dd class="sure-point">赠送免费积分<span><?=(float)$this->_request->pot?></span>点</dd>
	    	</dl>
	    </div>
	</div>
	<div class="jifen-step02">
	    <a href="<?=$this->url('./default')?>" class=" btn sure">返 回</a>
    </div>
    <div class="n-h56"></div>
     <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
     <!--?php include_once VIEWS.'inc/footer01.php'; ?-->
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
</script>
</body>
</html>




                       
