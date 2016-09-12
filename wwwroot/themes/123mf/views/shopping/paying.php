<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <?php if($this->orders_json) {?>
        <?php foreach($this->orders_json as $key => $val) {?>
            <div class="n-shopping-box" style="<?php if($key == 0) { ?>padding-top: 15px;<?php }?>">
                <?php if(count($val->goods)) {?>
                    <?php foreach($val->goods as $key=>$good) {?>
                        <div class="n-shopping-box-down" style="border-bottom: 1px solid #868686">
                            <div class="n-shopping-down-img">
                                <img src="<?php echo $good['thumb']?>" alt="<?php echo $good['title']?>">
                            </div>
                            <div class="n-shopping-down-te">
                                <a href="<?=$this->url("item/").$good['id'].'.html'?>"><p class="n-shopping-down-te1"><?=$good['title']?></p></a>
                            </div>
                        </div>
                    <?php }?>
                <?php }?>

                <hr/>
                <div class="n-shopping-box-top">
                    <div style="float: left">
                        <span>价格：<?php echo $val->points?>元</span>
                        <?php if(!empty($val->subtotal_credit)) {?>
                            <span>积分：<?php echo $val->subtotal_credit?></span>
                        <?php }?>
                        <?php if(!empty($val->subtotal_credit_happy)) {?>
                            <span>快乐积分：<?php echo $val->subtotal_credit_happy?></span>
                        <?php }?>
                        <?php if(!empty($val->subtotal_credit_coin)) {?>
                            <span>积分币：<?php echo $val->subtotal_credit_coin?></span>
                        <?php }?>
                        <span>邮费：<?php echo $val->order_postage?>元</span>
                    </div>
                    <div style="float: right">
                        <span>件数：<?php echo $val->total?>件</span>
                        <span>重量：<?php echo $val->weight?>KG</span>
                    </div>
                </div>
            </div>
        <?php }?>
    <?php }?>
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
    <?php if ($this->total_postage + $this->order['total_amount']) { ?>

    <div class="jifen-step06 " style="margin-bottom: 10px;">
    	<p class="you-method w90">选择支付方式 :</p>
    	<div class="method bgwhite">
	    	<a class="ali bgwhite" href="javascript:;" data-code="alipay"></a>
	    </div>
	    <div class="method bgwhite">
	    	<a class="wechat bgwhite" href="javascript:;" data-code="wxpay"></a>
	    </div>
	</div>

    <?php } else { ?>
        <div class="jifen-step06 " style="margin-bottom: 10px;">
            <div class="method n-all-m">
                <input value="立即支付" class="bgwhite" type="button" id="pay_free" data-code="free">
            </div>
        </div>
    <?php }?>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
    <p style="margin-top:10px;margin-left:10px;margin-right:10px;  ">各位会员：因阿里支付宝与腾讯微信支付在相关的支付方面有着相互抵制，在打开平台的入口不一样，支付的工具不一样，所以当你用其中的一个无法支付时，请你用另外一个支付，让你带来不便，敬请凉解。</p>

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
$('#pay_free').on('click', function(){
    var code = $(this).data('code');
    $('[name=payment]').val(code);
    console.log(code);
    $('form.pay-form').submit();
});
</script>
</body>
</html>




                       
