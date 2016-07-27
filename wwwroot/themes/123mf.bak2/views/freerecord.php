<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-rechargerecord">
		<div class="n-personal-center-tit">
			<a href="<?=$this->url('./default')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			获取免费积分记录
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
				<th>获得途径</th>
				<th>数额</th>
			</tr>
			<?php foreach($this->datalist as $row) { ?>
			<tr>
				<td><?=date(DATE_FORMAT, $row['create_time'])?></td>
				<td><?=$row['note']?></td>
				<td><?=$row['credit']?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</body>
</html>