<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #fff;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>赠送免费积分记录  
</div>

<form action="">
    <div class="records-day">
        <div class="top f-cb">
            <span class="tit n1">日期</span>
            <input type="date" name="" value="2016-09-21">
            <span class="tit n2">至</span>
            <input type="date" name="" value="2016-09-21">
        </div>
        <input type="submit" value="查询" class="submit">
    </div>
</form>

<table class="records-table">
    <thead>
    <tr>
        <th>日期</th>
        <th>会员账号</th>
        <th>赠送数额</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>2016-08-20</td>
        <td>15868813334</td>
        <td>200</td>
    </tr>
    <tr>
        <td>2016-08-20</td>
        <td>15868813334</td>
        <td>200</td>
    </tr>
    <tr>
        <td>2016-08-20</td>
        <td>15868813334</td>
        <td>200</td>
    </tr>
    </tbody>
</table>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    })
</script>
</body>
</html>