<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #fff;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>购买抵用金  
</div>
<div class="purchase">
    <form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem" onsubmit="return form_sub()">
        <div class="top">
            <div class="row f-cb">
                <span class="tit">消费金额</span>
                <input type="text" class="form-loan" name="consume">
            </div>
            <div class="row f-cb">
                <span class="tit">折扣</span>
                <input type="text" class="form-discount" name="discount">
                <i>%</i>
            </div>
        </div>
        <ul class="text">
            <li class="n1">您可以享受<b id="discount">0.00</b>元优惠折扣</li>
            <li class="n2 f-cb"><div class="tit">支付服务费</div><span id="service_charge">0</span>元<small>（按优惠折扣的<?php echo $this->service_charge['price']*100 .'%'?>计算）</small></li>
            <li class="n3 f-cb"><div class="tit">支付方式：</div></li>
        </ul>
        <ul class="sel-list">
            <?php foreach($this->data as $row) {?>
                <li class="f-cb">
                    <label>
                        <div class="label fl">
                            <input type="radio" name="price_type" value="<?php echo $row['id']?>">
                            <i class="fl"></i>
                            <span class="compute" data-id="<?php echo $row['l_digital']/$row['r_digital']?>">0</span><?php echo $row['left_name']?>
                        </div>
                        <p class="tit fl"><?php echo $row['l_digital']?><?php echo $row['left_name']?>=<?php echo $row['r_digital']?><?php echo $row['right_name']?></p>
                    </label>
                </li>
            <?php }?>
        </ul>
        <input type="submit" value="确定购买" class="submit">
    </form>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script>
    var in_val = '', reg = '', discount = '', service_charge = '', charge = '', compute = '';
    $(function()
    {
        $('input[name=discount]').keyup(function() {
            checkout();
            discount = in_val - (in_val * reg/100);
            $('#discount').text(discount.toFixed(2));
            charge = '<?php echo $this->service_charge['price']?>';
            service_charge = charge * discount;
            $('#service_charge').text(service_charge.toFixed(2));


            $('.sel-list li').each(function(){
                var othis = $(this).find('.compute');

                othis.text(Math.round(discount*othis.attr('data-id')));
            });

        });

        $('input[name=consume]').blur(function() {
            if(in_val.length > 0 &&  in_val > 0 && reg.length > 0 || reg > 0){
                checkout();
                discount = in_val - (in_val * reg/100);
                $('#discount').text(discount.toFixed(2));
                charge = '<?php echo $this->service_charge['price']?>';
                service_charge = charge * discount;
                $('#service_charge').text(service_charge.toFixed(2));
                $('.sel-list li').each(function(){
                    var othis = $(this).find('.compute');
                    othis.text(Math.round(discount*othis.attr('data-id')));
                });
            }
        });



    });
    function checkout() {
        //金额
        in_val = $.trim($('.form-loan').val());
        if(in_val.length<=0 || in_val <= 0){
            alert('消费金额不为空且不能为负');
            return false;
        }
        //折扣
        reg = $.trim($('.form-discount').val());
        if(reg.length<=0 || reg <= 0){
            alert('折扣不为空且不能为负');
            return false;
        }
    }
    function form_sub(){
        checkout();
        //支付方式
        if(!$('input[name=price_type]').is(':checked')){
            alert('请选择支付方式!');
            return false;
        }

    }
</script>
</body>
</html>