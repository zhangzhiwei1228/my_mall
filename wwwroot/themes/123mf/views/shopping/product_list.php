<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
<link rel="stylesheet" type="text/css" href="m/css/mystyle.css">
</head>
<style type="text/css">
    .good-price .text04 {
      text-decoration: line-through;
    }
    .pic {
        position: relative;
    }
    .sup-lt, .sup-ld, .sup-rt, .sup-rd {
        position: absolute;
        display: table-cell;
        background: #b40000;
        border-radius: 40px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        vertical-align: middle;
        color: #fff;
    }

    .sup-lt {
        left: 5px;
        top: 5px;
    }
    .sup-ld {
        left: 5px;
        bottom: 5px;
    }
    .sup-rt {
        right: 5px;
        top: 5px;
    }
    .sup-rd {
        right: 5px;
        bottom: 5px;
    }

</style>
<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <!-- <p class="ibuy bgwhite"><em></em>我能购买</p> -->
    <ul class="good-list product-list good-ajax-list" style="margin-bottom: 60px;">

    </ul>
    <div style="display:none;" class="mn">
        <img src="<?php echo static_file('img/loading1.gif'); ?>" style="display: block;margin:0 auto;"/>
    </div>
    <div class="clear"></div>
    <?php if ($this->_request->action != 'search') { ?>
    <h3>同类商品推荐</h3>
    <ul class="good-list product-list relate-ajax-list" style="margin-bottom: 60px;">

    </ul>
    <?php } ?>
    <!--<?php include_once VIEWS.'inc/footer_shopping.php'; ?>-->
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
    $('#cnzz_stat_icon_1259881631').hide();
    // $('.good-list li:even').css('marginRight','2%')
</script>
<script>
    $(function(){
        var flag=true;
        var n=1;
        var sbt = '<?php echo $this->_request->sbt?>';
        var q = '<?php echo $this->_request->q?>';
        var cid = '<?php echo $this->_request->cid?>';
        $.ajax({
            url:"/goods/getgoodlist/?page="+n+'&sbt='+sbt+'&q='+q+'&cid='+cid,
            success:function(e){
                $('.good-ajax-list').append(e);
                n++;
                setTimeout(function(){
                    flag=true;
                },500)
            }
        });
        $(window).scroll(function(){
            var H=$('body,html').height();
            var h=$(window).height();
            var t=$('body').scrollTop();
            if(t>=H-h*1.1 && flag==true){
                flag=false;
                $(".mn").show();
                $.ajax({
                    url:"/goods/getgoodlist/?page="+n+'&sbt='+sbt+'&q='+q+'&cid='+cid,
                    success:function(e){
                        if(!e) {
                            flag = false ;
                            $(".mn").hide();
                            $('.good-ajax-list').append('<p style="text-align: center">已加载完毕<p>');

                            $('.relate-ajax-list').load('/goods/getrelategoodslist/',{sbt:sbt,q:q,cid:cid});
                            return false;
                        }
                        $('.good-ajax-list').append(e);
                        n++;

                        setTimeout(function(){
                            flag=true;
                            $(".mn").hide();
                        },100)
                    }

                })
            }
        });
    });
</script>
</body>
</html>












