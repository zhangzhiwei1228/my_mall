<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-9
 * Time: 上午11:52
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
    <style type="text/css">
        .text-center {
            text-align: center;
            margin: 0 auto;
        }
        .pagination.pagination-sm {

        }
        ul{
            float: none;
        }
        .pagination {
            vertical-align: middle;
        }
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 20px 0;
            border-radius: 4px;
        }
        .pagination.pagination-sm li a {
            height: 28px;
            padding: 4px 10px;
            vertical-align: middle;
        }
        .pagination-sm>li:first-child>a, .pagination-sm>li:first-child>span {
            border-top-left-radius: 3px;
            border-bottom-left-radius: 3px;
        }
        .pagination>li:first-child>a, .pagination>li:first-child>span {
            margin-left: 0;
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .pagination li a {
            color: #333;
        }
        .pagination-sm>li>a, .pagination-sm>li>span {
            padding: 5px 10px;
            font-size: 12px;
            line-height: 1.5;
        }
        .pagination>li>a, .pagination>li>span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: -1px;
            line-height: 1.42857143;
            color: #337ab7;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .pagination>li {
            display: inline;
        }
    </style>
</head>
<body>
<div class="n-rechargerecord">
    <div class="n-personal-center-tit">
        <a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
        <?php echo $this->_request->t == 'credit' ? '充值免费积分记录':'充值抵用券记录' ?>

    </div>
    <form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
        <div class="n-rechargerecord-day">
            <span>日期</span><input value="" type="datetime-local" name="start_time"><span>至</span><input value="" type="datetime-local" name="end_time">
        </div>
        <div class="n-rechargerecord-sub">
            <input value="查询" type="submit">
        </div>
    </form>
    <table width="100%" class="n-rechargerecord-table">
        <tr>
            <th>日期</th>
            <th>充值途径</th>
            <th>数额</th>
        </tr>
        <?php foreach($this->datalist as $row) { ?>
            <tr>
                <td><?=date(DATE_FORMAT, $row['create_time'])?></td>
                <td><?=$row['note']?></td>
                <td><?=$row['credit']?></td>
            </tr>
        <?php } ?>
    </table>
    <div class="text-center">
        <ul class="pagination pagination-sm">
            <?=$this->paginator($this->datalist)->getAjaxBar('$.gotopage')?>
        </ul>
    </div>
    <script type="text/javascript">
        $.gotopage = function(page) {
            var t = '<?php echo $this->_request->t?>';
            window.location.href = '<?php echo $this->url('agent/logs/recharge/')?>'+'?page='+page+'&t='+t;
        }
    </script>
</div>
</body>
</html>
