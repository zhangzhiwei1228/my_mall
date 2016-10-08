<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #fff;">

<div class="purchase">
    <form action="" onsubmit="return form_sub()">
        <div class="top">
            <div class="row f-cb">
                <span class="tit">消费金额</span>
                <input type="text" class="form-loan">
            </div>
            <div class="row f-cb">
                <span class="tit">折扣</span>
                <input type="text" class="form-discount">
                <i>%</i>
            </div>
        </div>
        <ul class="text">
            <li class="n1">您可以享受<b>20.00</b>优惠折扣</li>
            <li class="n2 f-cb"><div class="tit">支付服务费</div>4元<small>（按优惠折扣的20%计算）</small></li>
            <li class="n3 f-cb"><div class="tit">支付方式：</div></li>
        </ul>
        <ul class="sel-list">
            <li class="f-cb">
                <label>
                    <div class="label fl"><input type="radio" name="a1"><i class="fl"></i>10免费积分</div>
                    <p class="tit fl">1免费积分=2抵用金</p>
                </label>
            </li>
            <li class="f-cb">
                <label>
                    <div class="label fl"><input type="radio" name="a1"><i class="fl"></i>40抵用券</div>
                    <p class="tit fl">2抵用券=1抵用金</p>
                </label>
            </li>
            <li class="f-cb">
                <label>
                    <div class="label fl"><input type="radio" name="a1"><i class="fl"></i>4积分币</div>
                    <p class="tit fl">1积分币=5抵用金</p>
                </label>
            </li>
            <li class="f-cb">
                <label>
                    <div class="label fl"><input type="radio" name="a1"><i class="fl"></i>3.33元</div>
                    <p class="tit fl">1元=6抵用金</p>
                </label>
            </li>
        </ul>
        <input type="submit" value="确定购买" class="submit">
    </form>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    });

    function form_sub(){
        var in_val = '',reg = '';

        //金额
        in_val = $.trim($('.form-loan').val());
        if(in_val.length<=0){
            alert('金额不为空!');
            return false;
        }
        //折扣
        in_val = $.trim($('.form-discount').val());
        if(in_val.length<=0){
            alert('折扣不为空!');
            return false;
        }

        //支付方式
        if(!$('input[name=a1]').is(':checked')){
            alert('请选择支付方式!')
            return false;
        }

        return false;
    }
</script>
</body>
</html>