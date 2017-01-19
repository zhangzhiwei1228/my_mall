<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
		<div class="bg">
			<div class="shop-list">
				<div class="shop-tit">
					<select name="" id="">
						<option value="">商家分类</option>
						<option value="">商家分类</option>
						<option value="">商家分类</option>
					</select>
					<select name="" id="">
						<option value="">省</option>
						<option value="">省</option>
						<option value="">省</option>
					</select>
					<select name="" id="">
						<option value="">市</option>
						<option value="">市</option>
						<option value="">市</option>
					</select>
					<select name="" id="">
						<option value="">区</option>
						<option value="">区</option>
						<option value="">区</option>
					</select>
					<input value="搜 索" type="submit">
				</div>
				<div class="list-line"></div>
				<div class="list-ul">
					
				</div>
			</div>
		</div>
    <?php include_once VIEWS.'inc/footer.php'; ?>
</body>
<script>
	$(function(){
		var url = <?php echo "'".site_url('ajax/shop_list_ajax')."'"; ?> ;
		$(".list-ul").load(url)

		$(".shop-tit input").click(function(){
				var Html = $.ajax({
                url   : '<?php echo site_url('ajax/shop_list_ajax'); ?> ',
                async : false
      			})
		$(".list-ul").html(Html.responseText)

		})
	})
</script>
</html>