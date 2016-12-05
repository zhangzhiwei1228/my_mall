<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    <div class="n-news-info">
    	<div class="text">
            <?php echo $this->content?>
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