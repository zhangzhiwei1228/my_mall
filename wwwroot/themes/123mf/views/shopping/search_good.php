<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <div style="width:100%;" class="xs-box">
        <ul class="search-good fl">
            <?php foreach($this->cates as $row) { ?>
    	   <li><a href="javascript:;" data-id="<?=$row['id']?>"><?=$row['name']?></a></li>
            <?php } ?>
        </ul>
        <div class="products-list fl bgwhite">
        
        </div>
    </div>
    <div class="n-h60"></div>
    <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
    //$('.search-good li').eq(0).find('a').addClass('bordercurr');
    // $('.search-good li a').click(function(){
    //     $(this).addClass('bordercurr').parent('li').siblings('li').find('a').removeClass('bordercurr');
    // });


    $(function(){
        $(".xs-box").height($(window).height() - 136);
        $(".search-good li a").click(function(){
            $(this).addClass('bordercurr').parent('li').siblings('li').find('a').removeClass('bordercurr');
            var cid = $(this).attr("data-id");
            $.cookie('search_cid',cid);
            $(".products-list").load('<?=$this->url('&')?>', {cid: cid});
        });


        if($.cookie('search_cid')) {
            var flag = '<?php echo $this->flag;?>';
            if(flag) {
                var search_cid = $.cookie('search_cid');
                $('.search-good a[data-id='+search_cid+']').trigger('click');
                $.cookie('search_cid',null);
            } else {
                $('.search-good li:first-child a').trigger('click');
            }
        } else {
            $('.search-good li:first-child a').trigger('click');
        }

    });
</script>
</body>
</html>












