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
                <div class="end-sp-box">
                  <table width="100%;">
                    <tr>
                      <td><span class="sp1">员工账号：</span>5465656546</td>
                      <td><span class="spl">周星星的本月收益</span></td>
                    </tr>
                    <tr>
                      <td>员工名称：周星星</td>
                      <td><span class="sp2">4000元</span></td>
                    </tr>
                  </table>
                </div>
                <ul class="agents-ul">
                  <li>
                    <span class="spl">本月发展的一级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">本月激活的一级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">历史发展的一级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">历史激活的一级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">本月发展的二级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">本月激活的二级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">历史发展的二级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">历史激活的二级会员总数</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">我的一级会员本月消费积分币</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">我的二级会员本月消费积分币</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">我的商家的一级会员本月消费积分币</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">我的商家的二级会员本月消费积分币</span>
                    <span class="spr">1000  个</span>
                  </li>
                  <li>
                    <span class="spl">我发展的商家本月使用免费积分</span>
                    <span class="spr">1000  个</span>
                  </li>
                </ul>
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