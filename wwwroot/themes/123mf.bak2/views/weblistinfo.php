<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-weblist">
	<div class="n-personal-center-tit">
		<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		收货地址管理
        <input class="n-keep" value="保存" type="submit">
	</div>	
	<div class="n-weblistinfo">
		<table class="" width="100%">
			<tr>
				<td width="20%"><p>收货人:</p></td>
				<td width="80%"><input type="text"></td>
			</tr>
			<tr>
				<td width="20%"><p>手机号码:</p></td>
				<td width="80%"><input type="text"></td>
			</tr>
			<tr>
				<td width="20%"><p>邮政编码:</p></td>
				<td width="80%"><input type="text"></td>
			</tr>
			<tr>
				<td width="20%"><p>所在地区:</p></td>
				<td width="80%">
					<div class="n-addweb-sp-down">
						<ul style="width:100%;">
							<li>
								<select name="" id="">
									<option value="">某某省</option>
									<option value="">某某省</option>
								</select>
							</li>
							<li>
								<select name="" id="">
									<option value="">某某市</option>
									<option value="">某某市</option>
								</select>
							</li>
							<li>
								<select name="" id="">
									<option value="">某某区</option>
									<option value="">某某区</option>
								</select>
							</li>
						</ul>
					</div>
				</td>
			</tr>
			<tr>
				<td width="20%"><p>具体:</p></td>
				<td width="80%"> 
					<input class="n-weblistinfo-p1" type="text">
				
				</td>
			</tr>
		</table>
	</div>
	<div class="n-defa">
			<input value="设为默认地址" type="submit">
	</div>
</div>
</body>
<script>
	$(function(){
		$(".n-weblistinfo").height($(window).height() - 117);
	})
</script>
</html>
