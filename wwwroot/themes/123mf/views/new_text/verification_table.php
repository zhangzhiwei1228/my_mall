<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>核销记录  
</div>

<div class="m-verifi tab">
    <div class="m-notes">
        <table>
            <tr>
                <th>时间</th>
                <th>用户账号</th>
                <th>消费金额</th>
                <th>抵用金额</th>
            </tr>
            <tr>
                <td>2016.08.19</td>
                <td>121.00</td>
                <td>121.00</td>
                <td class="hot">121.00</td>
            </tr>
            <tr>
                <td>2016.08.19</td>
                <td>121.00</td>
                <td>121.00</td>
                <td class="hot">121.00</td>
            </tr>
            <tr>
                <td>2016.08.19</td>
                <td>121.00</td>
                <td>121.00</td>
                <td class="hot">121.00</td>
            </tr>
            <tr>
                <td>2016.08.19</td>
                <td>121.00</td>
                <td>121.00</td>
                <td class="hot">121.00</td>
            </tr>
            <tr>
                <td>2016.08.19</td>
                <td>121.00</td>
                <td>121.00</td>
                <td class="hot">121.00</td>
            </tr>
        </table>
    </div>
    <div class="h20"></div>
    <ul class="nave f-cb">
        <li>
            <b>2000.00</b>
            <p>本月核销</p>
        </li>
        <li>
            <b>2000.00</b>
            <p>本月核销</p>
        </li>
    </ul>
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