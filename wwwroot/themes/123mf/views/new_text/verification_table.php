<!DOCTYPE html>
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
            <?php if(count($this->datas)) {?>
                <?php foreach($this->datas as $row) {?>
                    <tr>
                        <td><?php echo date('Y.m.d',$row['create_time'])?></td>
                        <td><?php echo $row['username']?></td>
                        <td><?php echo $row['consume']?></td>
                        <td class="hot"><?php echo $row['privilege']?></td>
                    </tr>
                <?php }?>
            <?php }?>
        </table>
        <div class="text-center">
            <ul class="pagination pagination-sm">
                <?=$this->paginator($this->datas)->getAjaxBar('$.gotopage')?>
            </ul>
        </div>
        <script type="text/javascript">
            $.gotopage = function(page) {
                window.location.href = '<?php echo $this->url('agent/credit/cancel/')?>'+'?page='+page;
            }
        </script>
    </div>
    <div class="h20"></div>
    <ul class="nave f-cb">
        <li style="width: 100%">
            <p>本月核销</p>
            <b><?php echo $this->earnings['total']?></b>

        </li>
        <li style="width: 100%">
            <p>可获得收益</p>
            <b><?php echo $this->earnings['earnings']?></b>

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