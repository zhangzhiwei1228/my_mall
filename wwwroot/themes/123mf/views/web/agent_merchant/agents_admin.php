<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">    
        <div class="recharge-game-money w1190">
            <?php include_once VIEWS.'inc/agent_side.php'; ?>
            <div class="fr">
               <?php include_once VIEWS.'inc/prople_head.php'; ?>
               <div class="balance-ul">
                    <ul>
                        <li style="width:33.3%">
                            <p class="top">我代理地区商家本月使用免费积分</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">我代理地区会员本月消费积分币</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">本月收益</p>
                            <p class="bot">10000</p>
                        </li>
                    </ul>
               </div>
               
               <div class="agents-give">
                  <p style="font-size:14px;"><font style="color:#333;">一级会员：</font>我直接邀请注册的会员</p>
                  <p style="font-size:14px;"><font style="color:#333;">二级会员：</font>我的一级会员邀请注册的会员</p>
                  <br>
                  <p style="font-size:14px;">说明：发展的会员数根据会员在注册时邀请码填写该代理商号码0001进行识别统计，我发展的会员充值20元激活统计为激活会员数，并且计算我发展的会员充值个人积分总和。当我发展的商家的会员充值个人积分时也统计一个总和。</p>
                  <br>
                  <p style="font-size:14px;">当代理商发展商家时，商家账号由平台管理员生成，然后分发给商家，并且自动归属代理商，代理商可查看自己发展的每个商家本月的会员积分充值使用数量。</p>
               </div>
            </div>
        </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
    $(function(){

      $(".member-side .tit").html("代理商中心")
      $(".balance-ul li").eq(2).css("background","none")  

       
    })
</script>
</html>