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
               <div class="balance-ul">
                    <ul>
                        <li style="width:33.3%">
                            <p class="top">我员工发展的一级会员消费积分币</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">我员工发展的二级会员消费积分币</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">我的本月收益</p>
                            <p class="bot">10000</p>
                        </li>
                    </ul>
               </div>
               <div class="agents-give">
                  <p style="font-size:14px;"><font style="color:#333;">一级会员：</font>我直接邀请注册的会员</p>
                  <p style="font-size:14px;"><font style="color:#333;">二级会员：</font>我的一级会员邀请注册的会员</p>
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

        $(".position").html("创业商家管理员")
        




    })
</script>
</html>