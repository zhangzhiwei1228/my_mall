<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>核销  
</div>

<div class="m-verifi">
    <div class="g-box">
        <div class="row f-cb">
            <input type="text" placeholder="输入会员账号">
            <input type="submit" value="" class="sub">
        </div>

        <table class="list">
            <tr>
                <th>会员账号</th>
                <th>会员名</th>
            </tr>
            <tr>
                <td>000101</td>
                <td>韦小宝</td>
            </tr>
        </table>

        <div class="sm-row f-cb">
            <span>抵用金额：</span>
            <input type="text" name="" value="10000">
        </div>

        <a href="" class="butn-bot">下一步</a>

    </div>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    })
</script>
</body>
</html>