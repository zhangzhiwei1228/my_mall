<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
 	<div class="n-business">
 		<div class="imgbox">
 			<?php echo $this->desc?>
 		</div>
 	</div>	
    <?php include_once VIEWS.'app/inc/footer.php'; ?>
<?php
	echo static_file('app/js/main.js');
?>
<script>
$(function(){

})
</script>
</body>
</html>