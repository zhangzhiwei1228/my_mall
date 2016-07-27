<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="download-bg">
    <div class="dpic-box"><img class="pic-bg" src="<?php echo static_file('m/img/d01.jpg'); ?> "></div>

    <div class="download-btn">
    	<a class="fl" href="#">Android下载</a>
    	<a class="fr" href="#">IOS下载</a>
    	<div class="clear"></div>
    </div>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	function FullBg(box, obj){
		box.css("background", "none")
		obj.eq(0).stop().fadeIn(1000)
		function resizeBg() {
			obj.removeClass("w-f").removeClass("h-f").css("margin", 0)
			var boxR = box.width() / box.height(),
				objR = obj.width() / obj.height();
			if ( objR < boxR ) {
			    obj.addClass('w-f').css("margin-top", -(obj.height() - box.height()) / 2);
			} else {
			    obj.addClass('h-f').css("margin-left", -(obj.width() - box.width()) / 2);
			}	
		}
		$(window).resize(resizeBg).trigger("resize");
	}
			// $(".news-container").height($(window).height());

			// window.onresize = function(){
			// 	$(".news-container").height($(window).height());
			// }

			var box = $('.dpic-box');
			var obj = $('.dpic-box .pic-bg');
			FullBg(box, obj);
</script>
</body>
</html>