<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    <div class="n-shopping">
    	<div class="text">
    		<h2><?php echo isset($this->title) && $this->title ? $this->title : $this->description['title']?></h2>
    		<?php echo $this->description['content']?>
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