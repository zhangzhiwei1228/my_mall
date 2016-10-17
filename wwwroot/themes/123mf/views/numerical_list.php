<!DOCTYPE html>
<head>
    <?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body style="background-color: #ebebeb">
<div class="n-personal-center-tit">
	<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
	转换
</div>
<ul class="numerical_list">
	<?php foreach($this->datalist as $row) {?>
		<li><a href="<?php echo $this->url('/usercp/credit/').'?cid='.$row['id']?>"><?php echo $row['left_name']?>转化成<?php echo $row['right_name']?></a></li>
	<?php }?>
</ul>

<?php
echo static_file('m/js/main.js');
?>
<script>
    $(function(){

    })
</script>
</body>
</html>