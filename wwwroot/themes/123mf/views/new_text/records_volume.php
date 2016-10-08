<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #fff;">

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