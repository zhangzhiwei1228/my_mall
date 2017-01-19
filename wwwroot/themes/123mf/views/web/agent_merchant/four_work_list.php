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
            </div>
        </div>
    </div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
    <?php echo static_file('web/js/laydate.js'); ?> 
</body>
<script>
    $(function(){
        $(".member-side ul").eq(0).find('li').eq(0).find('a').addClass('cur');

        $(".game-give-head").css("border-bottom","1px solid #cdcdcd");

        $(".game-give-head").css("margin-bottom","20px");

       
        




    })
</script>
</html>