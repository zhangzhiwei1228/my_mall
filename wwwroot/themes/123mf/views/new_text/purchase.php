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
                <?php if($row['exts']) continue;?>
                <li class="f-cb">
                    <label>
                        <div class="label fl" style="font-size: 16px">
                            <input type="radio" name="price_type" value="<?php echo $row['id']?>">
                            <i class="fl"></i>
                            <span class="compute" data-id="<?php echo $row['l_digital']/$row['r_digital']?>">0</span><?php echo $row['left_name']?>
                        </div>
                        <p class="tit fl" style="margin-left: 30px">
                            <?php echo $row['l_digital'].$row['left_name'].' = '.$row['r_digital'].$row['right_name']?>
                        </p>
                    </label>
                </li>
            <?php }?>
            <li class="f-cb">
                <label>
                    <div class="label fl" style="font-size: 16px">
                        <input type="radio" name="price_type" value="100">
                        <i class="fl"></i>
                        <span class="compute" data-id="<?php echo ($this->pro15['l_digital']/$this->pro15['r_digital'])* 0.5;?>" data-val="">0</span>免费积分 +
                        <span class="hybrid" data-id="<?php echo ($this->pro18['l_digital']/$this->pro18['r_digital'])* 0.5;?>">0</span>元
                    </div>
                    <p class="tit fl" style="margin-left: 30px">

                    </p>
                </label>
            </li>
            <br/>
            <li class="f-cb">

                <label>
                    <div class="label fl" style="font-size: 16px">
                        <input type="radio" name="price_type" value="101">
                        <i class="fl"></i>
                        <span class="compute" data-id="<?php echo ($this->pro16['l_digital']/$this->pro16['r_digital'])* 0.5;?>" data-val="">0</span>抵用券 +
                        <span class="hybrid" data-id="<?php echo ($this->pro18['l_digital']/$this->pro18['r_digital'])* 0.5;?>">0</span>元
                    </div>
                    <p class="tit fl" style="margin-left: 30px">

                    </p>
                </label>
            </li>
            <li class="f-cb">
                <label>
                    <div class="label fl" style="font-size: 16px">
                        <input type="radio" name="price_type" value="102">
                        <i class="fl"></i>
                        <span class="compute" data-id="<?php echo ($this->pro17['l_digital']/$this->pro17['r_digital'])* 0.5;?>" data-val="">0</span>积分币 +
                        <span class="hybrid" data-id="<?php echo ($this->pro18['l_digital']/$this->pro18['r_digital'])* 0.5;?>">0</span>元
                    </div>
                    <p class="tit fl" style="margin-left: 30px">

                    </p>
                </label>
            </li>
        </ul>
        <input type="submit" value="确定购买" class="submit" style="margin-top: 10px;margin-bottom: 10px">
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
                var hthis = $(this).find('.hybrid');
                hthis.text(Math.round(discount*hthis.attr('data-id')));
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
                    var hthis = $(this).find('.hybrid');
                    hthis.text(Math.round(discount*hthis.attr('data-id')));
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