<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-weblist">
	<div class="n-personal-center-tit">
		<a href="<?php echo isset($_COOKIE['buy_shop']) ? 'javascript:history.go(-2);' : 'javascript:history.go(-1);' ?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		收货地址管理
	</div>
	<div class="n-h5"></div>
	<?php if(count($this->datalist)) { foreach($this->datalist as $row) { ?>
	<div class="n-shopping-box">
		<div class="n-shopping-box-top">
<!--			<input type="checkbox" name="test" value="a" />  -->
			<a class="n-shopping-hide" data-id="<?php echo $row['id'];?>" href="javascript:;"><img src="<?php echo static_file('mobile/img/img-46.png'); ?> " alt=""></a>
		</div>
		<div class="n-shopping-box-down">
			<div class="n-web-top">
				<a href="<?=$this->url('./edit?id='.$row['id'])?>"><p class="n-web-topl"><?=$row['consignee']?></p></a>
				<a href="<?=$this->url('./edit?id='.$row['id'])?>"><p class="n-web-topr"><?=$row['phone']?></p></a>
			</div>
			<div class="n-web-down">
				<a href="<?=$this->url('./edit?id='.$row['id'])?>"><?=$row['is_def']?'<span>[ 默认 ]</span>':''?><p><?=$row['area_text']?> 
				<?=$row['address']?></p></a>
			</div>
		</div>
	</div>
	<?php } } else { ?>
		<p class="notfound">您还没有设置收货地址哦.</p>
	<?php } ?>
	<div class="n-h60"></div>
	<div style="position: fixed;bottom: 0px;left: 0px;" class="n-addlist">
		<a href="<?=$this->url('./add'); ?> ">新增收货地址</a>
	</div>
</div>
</body>
<script>
	$(document).ready(function() {
		$('.n-shopping-hide').click(function() {
			var addr_id = $(this).attr('data-id');
			var url = '<?php echo $this->url('./delete/')?>'+'?id='+addr_id;
			if(confirm("确认要删除吗？")) {
				window.location.href = url;
			}
			return false;
		});
	});
</script>
</html>
