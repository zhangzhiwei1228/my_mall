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
                <div class="worker-box">
                  <h2>员工列表</h2>
                  <table>
                    <tr>
                      <th>员工账号</th>
                      <th>员工名称</th>
                      <th>操作</th>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                    <tr>
                      <td>0001001001</td>
                      <td>员工1</td>
                      <td><a href="">查看</a></td>
                    </tr>
                  </table>
                  <div class="page-box">
                    <img src="<?php echo static_file('web/img/img-39.png'); ?> " alt="">
                  </div>
                </div>
                <div class="worker-box">
                  <h2>员工发展的商家列表</h2>
                  <table>
                    <tr>
                      <th>商家名称</th>
                      <th>本月充值免费积分</th>
                      <th>本月使用免费积分</th>
                      <th>免费积分余额</th>
                    </tr>
                    <tr>
                      <td>爱尚自助烤肉火锅</td>
                      <td>10000</td>
                      <td>10000</td>
                      <td>0001001001</td>
                    </tr>
                     <tr>
                      <td>爱尚自助烤肉火锅</td>
                      <td>10000</td>
                      <td>10000</td>
                      <td>0001001001</td>
                    </tr>
                     <tr>
                      <td>爱尚自助烤肉火锅</td>
                      <td>10000</td>
                      <td>10000</td>
                      <td>0001001001</td>
                    </tr>
                    <tr>
                      <td>爱尚自助烤肉火锅</td>
                      <td>10000</td>
                      <td>10000</td>
                     <td>0001001001</td>
                    </tr>
                  </table>
                  <div class="page-box">
                    <img src="<?php echo static_file('web/img/img-39.png'); ?> " alt="">
                  </div>
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