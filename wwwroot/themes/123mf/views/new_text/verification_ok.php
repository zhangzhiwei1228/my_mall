<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>确定核销
</div>

<div class="m-verifi">
    <div class="g-box">

        <table class="list m-t">
            <tr>
                <th>会员账号</th>
                <th>会员名</th>
            </tr>
            <tr>
                <td>000101</td>
                <td>韦小宝</td>
            </tr>
        </table>

        <a href="" class="butn-bot">确定核销</a>

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