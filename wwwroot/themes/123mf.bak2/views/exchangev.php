<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-rechargerecord">
		<div class="n-personal-center-tit">
			<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			抵用券兑换码兑换记录
		</div>
		<div class="n-rechargerecord-day">
			<span>日期</span><input value="日期插件" type="text"><span>至</span><input value="日期插件" type="text">
		</div>
		<div class="n-rechargerecord-sub">
			<input value="查询" type="submit">
		</div>
		<table width="100%" class="n-rechargerecord-table">
			<tr>
				<th>日期</th>
				<th>兑换码</th>
				<th>兑换金额</th>
			</tr>
			<tr>
				<td>2015-5-20</td>
				<td>ADSFGASDF4654ASDF</td>
				<td>1000</td>
			</tr>

		</table>
	</div>
</body>
</html>