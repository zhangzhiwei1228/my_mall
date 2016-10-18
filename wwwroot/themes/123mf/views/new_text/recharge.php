<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>充值抵用券  
</div>
<form class="recharge" method="post" action="<?php echo $this->url('/agent/credit/recharge')?>">
    <input type="hidden" name="type" value="vouchers">
    <input type="hidden" name="amount" value="0">
    <input type="hidden" name="return_url" value="<?php echo $this->url('agent')?>">
    <div class="m-recharge">
        <div class="n-recharge-pic-te">
            <p style="color:#b40000;font-size:14px;">抵用券充值说明：</p>
            <p style="color:#555;">1元=<?php echo $this->data['r_digital']?>抵用券（抵用券可转换为免费积分，积分币等）</p>
        </div>
        <div class="row f-cb" style="margin-top: 20px">
            <div class="tit fl">输入要充值的抵用券</div>
            <input type="text" name="vouchers">
        </div>
        <div class="bu-tit">支付金额：<span class="amount-text">0</span>RMB</div>


        <a href="javascript:;" class="butn-bot">立即支付</a>

    </div>
</form>
<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    })
</script>
<script type="text/javascript">
    var rate = <?php echo $this->data['r_digital']?>;
    $('[name=vouchers]').on('change', function(){
        var num = parseFloat($(this).val());
        //console.log(num/rate);
        $('[name=amount]').val((num/rate).toFixed(2));
        $('.amount-text').text((num/rate).toFixed(2));
    });
    $('.butn-bot').click(function() {
        var num = parseFloat($('[name=vouchers]').val());
        if(!num || num == 0) {
            alert('请填写要充值的抵用券');
            return false;
        }
        $('.recharge').submit();
    });
</script>
</body>
</html>