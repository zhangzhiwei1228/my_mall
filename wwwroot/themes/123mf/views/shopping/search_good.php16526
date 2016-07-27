<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header_shop02.php'; ?>
    <ul class="search-good fl">
        <?php foreach($this->cates as $row) { ?>
    	<li><a href="javascript:;" data-id="<?=$row['id']?>"><?=$row['name']?></a></li>
        <?php } ?>
    </ul>
    <div class="products-list fl bgwhite">
        
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
        $(".search-good li a").click(function(){
            $(this).addClass('bordercurr').parent('li').siblings('li').find('a').removeClass('bordercurr');
            var cid = $(this).attr("data-id");
            $(".products-list").load('<?=$this->url('&')?>', {cid: cid});
        });

        <?php if ($this->_request->id) { ?>
        $('.search-good a[data-id="<?=$this->_request->id?>"]').trigger('click');
        <?php } else { ?>
        $('.search-good li:first-child a').trigger('click');
        <?php } ?>
    });
</script>
</body>
</html>












