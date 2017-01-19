<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
    <div class="bg">    
        <div class="recharge-game-money w1190">
            <?php include_once VIEWS.'inc/agent_give_side.php'; ?>
            <div class="fr">
               <?php include_once VIEWS.'inc/prople_head.php'; ?>
               
                <ul class="agents-ul">
                <li>
                  <span class="spl">我代理地区我下线的商家本月使用免费积分</span>
                  <span class="spr">1000  个</span>
                </li>
                <li>
                  <span class="spl">我代理地区我下线的会员本月消费积分币</span>
                  <span class="spr">1000  个</span>
                </li>
              </ul>
              <div class="end-te">
                <p style="font-size: 14px;color: #777;"><font style="color:#333;">一级会员：</font>我直接邀请注册的会员</p>
                <p style="font-size: 14px;color: #777;"><font style="color:#333;">二级会员：</font>我的一级会员邀请注册的会员</p>
                <br>
                <br>
                <p style="font-size:14px;color:#333;">说明</p>
                <p style="font-size: 14px;color: #777;">发展的会员数根据会员在注册时邀请码填写该代理商号码0001进行识别统计，我发展的会员充值20元激活统计为激活会员数，并且计算我发展的会员充值个人积分总和。当我发展的商家的会员充值个人积分时也统计一个总和。
                </p>
                <br>
                <p style="font-size: 14px;color: #777;">当代理商发展商家时，商家账号由平台管理员生成，然后分发给商家，并且自动归属代理商，代理商可查看自己发展的每个商家本月的会员积分充值使用数量。</p>
              </div>
            </div>
        </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
    $(function(){
        $(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');

       
        




    })
</script>
</html>