<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic23.jpg'); ?> " width="100%"></div>
    <div class="jifen-step04">
	    <p class="step04-pic"><img src="<?php echo static_file('m/img/pic01.png'); ?> " width="100%" /></p>
	    <p class="prompt-info">余额不足，赠送失败 !</p>
	</div>
	<div class="jifen-step02">
        <?php if($this->type == 'credit') {?>
            <a href="<?php echo $this->url('agent/credit/recharge/?t=credit')?>" class=" btn sure">立即充值免费积分</a>
        <?php } else {?>
            <a href="<?php echo $this->url('agent/credit/recharge/?t=vouchers')?>" class=" btn sure">立即充值抵用券</a>
        <?php }?>

    </div>
    <div class="n-h56"></div>
     <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
     <!--?php include_once VIEWS.'inc/footer01.php'; ?-->
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
</script>
</body>
</html>




                       
