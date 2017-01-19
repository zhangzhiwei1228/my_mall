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
                            <p class="top">本月充值免费积分</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">本月使用免费积分</p>
                            <p class="bot">10000</p>
                        </li>
                        <li style="width:33.3%">
                            <p class="top">免费积分余额</p>
                            <p class="bot">10000</p>
                        </li>
                    </ul>
               </div>
               <div class="thrh-box">
                   <div class="write">
                       <span>输入赠送会员账号：</span><input class="inputa" type="text"><input value="会员查询" class="inputb" type="submit">
                   </div>
                   <table class="write-table">
                       <tr>
                           <th>会员账号</th>
                           <th>会员名称</th>
                           <th>免费积分余额</th>
                       </tr>
                       <tr>
                           <td>0001001001</td>
                           <td>会员1</td>
                           <td>0001001001</td>
                       </tr>
                   </table>
               </div>
               <div class="give-box">
                   <span>输入赠送免费积分：</span><input class="inputa" type="text"><input value="确认赠送" class="inputb" type="submit">
               </div>
               <div class="give-line">
                   <div class="give-linel">免费积分赠送记录</div>
                   <a class="give-liner" href="">查看所有交易记录</a>
               </div>
               <div class="give-time">
                    <span>输入日期查询：</span>
                    <input id="start" class="inline laydate-icon" type="text"> —
                    <input id="end" class="inline laydate-icon" type="text">
                    <input value="查询" class="sub" type="submit">
               </div>
               <div class="give-ajax">
                   
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
        
        $(".balance-ul li").eq(2).css("background","none")

        var start = {
            elem: '#start',
            format: 'YYYY/MM/DD hh:mm:ss',
            // min: laydate.now(), //设定最小日期为当前日期
            max: '2099-06-16 23:59:59', //最大日期
            istime: true,
            istoday: false,
            choose: function(datas){
                 end.min = datas; //开始日选好后，重置结束日的最小日期
                 end.start = datas //将结束日的初始值设定为开始日
            }
        };
        var end = {
            elem: '#end',
            format: 'YYYY/MM/DD hh:mm:ss',
            min: laydate.now(),
            max: '2099-06-16 23:59:59',
            istime: true,
            istoday: false,
            choose: function(datas){
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        laydate(start);
        laydate(end);


        var url = <?php echo "'".site_url('ajax/game_give_ajax')."'"; ?> ;
        $(".give-ajax").load(url)


        $(".give-time .sub").click(function(){
                var Html = $.ajax({
                url   : '<?php echo site_url('ajax/game_give_ajax'); ?> ',
                async : false
                })
        $(".give-ajax").html(Html.responseText)
        })

        $(".give-liner").click(function(){
                var Html = $.ajax({
                url   : '<?php echo site_url('ajax/game_give_ajax'); ?> ',
                async : false
                })
        $(".give-ajax").html(Html.responseText)
        })
    })
</script>
</html>