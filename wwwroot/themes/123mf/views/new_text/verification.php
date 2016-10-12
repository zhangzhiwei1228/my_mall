<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
    <style>
        .m-verifi .list th{font-size: 14px;padding:0;padding-top:20px;padding-left: 10px;padding-bottom: 20px}
        .m-verifi .list td{font-size: 12px;padding:0;padding-top:10px;padding-left: 10px;padding-bottom: 20px}
    </style>
</head>

<body style="background-color: #ebebeb;">

<div class="n-personal-center-tit">
    <a href="javascript:history.go(-1);"><img width="11" height="21" src="<? echo static_file('mobile/img/img-22.png')?>" alt=""></a>核销  
</div>

<div class="m-verifi">
    <div class="g-box">
        <form class="jifen-searchbox" action="#" method="get">
            <div class="row f-cb">
                <input type="text" placeholder="输入兑换码" name="q">
                <input type="submit" value="" class="sub" style="height: 0.95rem">
            </div>
        </form>
        <form class="search-result" method="get" action="<?=$this->url('./checkout')?>">
            <table class="list">
                <tr>
                    <td></td>
                    <th>会员账号</th>
                    <th>抵用金额</th>
                    <th>兑换码</th>
                    <th>状态</th>
                </tr>
                <tbody class="query_result">

                </tbody>
            </table>
            <a href="javascript:;" onclick="$('.search-result').submit();" class="butn-bot">下一步</a>
        </form>
    </div>
</div>

<?php
echo static_file('m/js/main.js');
?>
<script type="text/javascript">
    $('.jifen-searchbox').on('submit', function(){
        var el = $(this);
        var q = $('[name=q]', this).val();
        if (!q) {
            alert('请输入兑换码');
            return false;
        }

        $.getJSON('<?=$this->url('./querygold')?>', {q:q}, function(json){
            console.log(json);
            $('.query_result').empty();
            if (json.length > 0) {
                $(json).each(function(){
                    $('.query_result').append('<tr>'
                        +'<td align="center"><input type="radio" name="gid" value="'+this.id+'" checked></td>'
                        +'<td align="center">'+this.username+'</td>'
                        +'<td align="center">'+this.privilege+'</td>'
                        +'<td align="center">'+this.code+'</td>'
                        +'<td align="center">'+(this.write == 1 ? '未核销' : '已核销') +'</td>'
                        +'</tr>');
                });
                $('.next').css('background', '#ff6600');
            } else {
                $('.query_result').html('<tr><td align="center" colspan="5">未找到相关兑换码</td></tr>');
                $('.next').css('background', '#ddd');
            }
        });

        return false;
    });
</script>
</body>
</html>