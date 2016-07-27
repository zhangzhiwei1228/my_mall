<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <ul class="search-good fl">
    	<li><a href="#">家具建材</a></li>
    	<li><a href="#">家用电器</a></li>
    	<li><a href="#">电脑办公</a></li>
    	<li><a href="#">手机数码</a></li>
    	<li><a href="#">居家生活</a></li>
    	<li><a href="#">母婴频道</a></li>
    	<li><a href="#">家用电器</a></li>
    	<li><a href="#">电脑办公</a></li>
    	<li><a href="#">手机数码</a></li>
    	<li><a href="#">居家生活</a></li>
    </ul>
    <div class="products-list fl bgwhite">
        
    </div>
    <?php include_once VIEWS.'inc/footer_shopping.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
    $('.search-good li').eq(0).find('a').addClass('bordercurr');
    // $('.search-good li a').click(function(){
    //     $(this).addClass('bordercurr').parent('li').siblings('li').find('a').removeClass('bordercurr');
    // });


    $(function(){
        var url = <?php echo "'".site_url('ajax/good_ajax')."'"; ?> ;

        $(".search-good li a").click(function(){
            $(this).addClass('bordercurr').parent('li').siblings('li').find('a').removeClass('bordercurr');
            // var id = $(this).attr("data-id");
            $(".products-list").load(url);
        }) ;
        $(".products-list").load(url);
    });
</script>
</body>
</html>












