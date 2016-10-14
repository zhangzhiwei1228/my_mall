<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
	<style type="text/css">
		.text-center {
			text-align: center;
			margin: 0 auto;
		}
		.pagination.pagination-sm {

		}
		ul{
			float: none;
		}
		.pagination {
			vertical-align: middle;
		}
		.pagination {
			display: inline-block;
			padding-left: 0;
			margin: 20px 0;
			border-radius: 4px;
		}
		.pagination.pagination-sm li a {
			height: 28px;
			padding: 4px 10px;
			vertical-align: middle;
		}
		.pagination-sm>li:first-child>a, .pagination-sm>li:first-child>span {
			border-top-left-radius: 3px;
			border-bottom-left-radius: 3px;
		}
		.pagination>li:first-child>a, .pagination>li:first-child>span {
			margin-left: 0;
			border-top-left-radius: 4px;
			border-bottom-left-radius: 4px;
		}
		.pagination li a {
			color: #333;
		}
		.pagination-sm>li>a, .pagination-sm>li>span {
			padding: 5px 10px;
			font-size: 12px;
			line-height: 1.5;
		}
		.pagination>li>a, .pagination>li>span {
			position: relative;
			float: left;
			padding: 6px 12px;
			margin-left: -1px;
			line-height: 1.42857143;
			color: #337ab7;
			text-decoration: none;
			background-color: #fff;
			border: 1px solid #ddd;
		}

		.pagination>li {
			display: inline;
		}
	</style>
</head>
<body>
	<div class="n-rechargerecord">
		<div class="n-personal-center-tit">
			<a href="<?php echo $this->type == 'worth_gold' ? $this->url('/usercp/money') : 'javascript:history.go(-1);' ?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			<?php
				switch($this->type) {
					case 'credit' :
						echo '获取免费积分记录';
						break;
					case 'credit_happy' :
						echo '获取免费快乐积分记录';
						break;
					case 'credit_coin' :
						echo '我充值积分币记录';
						break;
					case 'worth_gold' :
						echo '获取抵用金记录';
						break;
				}
			?>

		</div>
		<div class="n-rechargerecord-day">
			<span>日期</span><input value="日期插件" type="datetime-local"><span>至</span><input value="日期插件" type="datetime-local">
		</div>
		<div class="n-rechargerecord-sub">
			<input value="查询" type="submit">
		</div>
		<?php if($this->type == 'worth_gold') {?>
			<table width="100%" class="n-rechargerecord-table">
				<tr>
					<th>日期</th>
					<th>抵用金</th>
					<th>兑换码</th>
					<th>状态</th>
				</tr>
				<?php foreach($this->datalist as $row) { ?>
					<tr>
						<td><?=date(DATE_FORMAT, $row['create_time'])?></td>
						<td><?php echo $row['privilege']?></td>
						<td><?php echo $row['code']?></td>
						<td><?php echo $row['write'] <2 ? '未兑换':'已兑换' ?></td>

					</tr>
				<?php } ?>
			</table>
		<?php } else {?>
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
		<?php }?>
		<div class="text-center">
			<ul class="pagination pagination-sm">
				<?=$this->paginator($this->datalist)->getAjaxBar('$.gotopage')?>
			</ul>
		</div>
		<script type="text/javascript">
			$.gotopage = function(page) {
				window.location.href = '<?php echo $this->url('/usercp/money/credit')?>'+'?page='+page+'&t='.<?php echo $this->type?>;
			}
		</script>
	</div>
</body>
</html>