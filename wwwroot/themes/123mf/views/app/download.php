<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    <div class="n-downbox">
    	<div class="logo">
    		<img src="<?php echo static_file('app/img/logo.png'); ?> " alt="">
    	</div>
    	<a href="###" title="" class="bo-x">应用下载</a>
    </div>
    <?php include_once VIEWS.'app/inc/footer.php'; ?>
<?php
	echo static_file('app/m/js/main.js');
?>
<script>
$(function(){
	$(".n-downbox").height($(window).height());
})
</script>
</body>
</html>