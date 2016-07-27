<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="pay-cash bgwhite">
    	<p class="cash w90">支付金额 :</p>
        <?php if(!empty($this->order['total_credit'])) {?>
    	<p class="cash-muns w90">
            <span><?=$this->order['total_credit']?></span>免费积分
        </p>
        <?php }?>
        <?php if(!empty($this->order['total_credit_coin'])) {?>
        <p class="cash-muns w90">
            <span><?=$this->order['total_credit_coin']?></span>积分币
        </p>
        <?php }?>
        <?php if(!empty($this->order['total_credit_happy'])) {?>
        <p class="cash-muns w90">
            <span><?=$this->order['total_credit_happy']?></span>快乐积分
        </p>
        <?php }?>
        <?php if(!empty($this->order['total_amount']) && $this->order['total_amount']>0 ) {?>
        <p class="cash-muns w90">
            <span><?=$this->order['total_amount']?></span>RMB
        </p>
        <?php }?>


    </div>
    <div class="pay-cash bgwhite" style="margin-top: -20px">
        <p class="cash w90">物流费 :</p>
        <p class="cash-muns w90"><span><?=$this->total_postage;?></span>RMB</p>
    </div>
    <div class="pay-cash bgwhite" style="margin-top: -20px">
        <p class="cash w90">总计 :</p>
        <p class="cash-muns w90">
            <?php if(!empty($this->order['total_credit'])) {?>
                <span><?=$this->order['total_credit']?></span>免费积分+
            <?php }?>
            <?php if(!empty($this->order['total_credit_coin'])) {?>
                <span><?=$this->order['total_credit_coin']?></span>积分币+
            <?php }?>
            <span><?=$this->total_postage + $this->order['total_amount'];?></span>
            RMB
        </p>
    </div>
    <?php if ($this->order['total_amount']) { ?>

    <div class="jifen-step06 ">
    	<p class="you-method w90">选择支付方式 :</p>
    	<div class="method bgwhite">
	    	<a class="ali bgwhite" href="javascript:;" data-code="alipay"></a>
	    </div>
	    <div class="method bgwhite">
	    	<a class="wechat bgwhite" href="javascript:;" data-code="wxpay"></a>
	    </div>
	</div>

    <?php } ?>
    <?php //include_once VIEWS.'inc/footer.php'; ?>

    <form method="post" class="pay-form">
        <input type="hidden" name="id" value="<?=$this->_request->id?>">
        <input type="hidden" name="payment">
        <input type="hidden" name="total_postage" value="<?=$this->total_postage + $this->order['total_amount'];?>">
    </form>
<?php
	echo static_file('web/js/main.js');
?>

<script type="text/javascript">
$('.method a').on('click', function(){
    var code = $(this).data('code');
    $('[name=payment]').val(code);
    console.log(code);
    $('form.pay-form').submit();
});
</script>
</body>
</html>




                       
