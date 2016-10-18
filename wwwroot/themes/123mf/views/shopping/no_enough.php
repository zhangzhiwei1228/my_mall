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
	    		<dd class="sure-info"><?=$this->message?></dd>
	    		<!-- <dd class="sure-point">赠送免费积分<span>200</span>点</dd> -->
	    	</dl>
	    </div>
	</div>
	<div class="jifen-step02">
		<?php if($this->code == 101) { $t='credit'?>
		<?php } elseif($this->code == 102) { $t='credit_happy'?>
		<?php } elseif($this->code == 103) { $t='credit_coin'?>
		<?php } elseif($this->code == 104) { $t='vouchers'?>
		<?php } ?>
		<a href="<?=$this->url('/usercp/money/recharge/?t='.$t)?>" class=" btn sure">确   定</a>
		<a href="<?=$this->url('/usercp/money/recharge/?t='.$t)?>" class=" btn cancel">充值</a>

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




                       
