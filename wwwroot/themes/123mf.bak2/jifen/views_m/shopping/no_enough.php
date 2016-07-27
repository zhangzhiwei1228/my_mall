<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgwhite">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="jifen-step03 bgwhite">
	    <div class="step03-main ">
	    	<dl>
	    		<dt></dt>
	    		<dd class="sure-info">积分币余额不足，支付失败。</dd>
	    		<!-- <dd class="sure-point">赠送免费积分<span>200</span>点</dd> -->
	    	</dl>
	    </div>
	</div>
	<div class="jifen-step02">
	    <a href="#" class=" btn sure">确   定</a>
	    <a href="#" class=" btn cancel">充值积分币</a>
    </div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
</script>
</body>
</html>




                       
