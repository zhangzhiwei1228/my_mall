<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-7-29
 * Time: 上午10:00
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body style="background:#ebebeb">
<div class="n-allorders">
    <div class="n-personal-center-tit">
        <a href="javascript:void(history.back())"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
        物流信息
    </div>
    <div class="express-tracking"><p class="loading">正在查询,请稍后...</p></div>
    <a href="//www.kuaidi100.com/all/yt.shtml" target="_blank">圆通快递查询单号</a>
    <script type="text/javascript">
        $.get('/callback/kuaidi100/', {com:'ems',un:'9753404307401'}, function(data){
            $('.express-tracking').html(data);
        });
    </script>

</div>
<div><a href="http://m.kuaidi100.com">快递查询</a> </div>
</body>
<script>
    <?php
    $n = array('awaiting_payment'=>1, 'shiped'=>2, 'pending_receipt'=>3, 'completed'=>4);
    $i = isset($n[$this->_request->t]) ? $n[$this->_request->t] : 0;
    ?>
    $(".n-allorders-box-li li").eq(<?=$i?>).css("height","48px");
    $(".n-allorders-box-li li").eq(<?=$i?>).css("border-bottom","1px solid #b40000");
</script>
</html>