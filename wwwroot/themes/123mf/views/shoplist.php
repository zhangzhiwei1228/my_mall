<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		员工列表
	</div>
	<table class="n-shoplist-table1" width="100%">

		<tr>
			<th>商家名称</th>
			<th>
				<p>本月充值</p>
				<p>
					<?php switch($this->_request->t) {
						case 'credit' :
							echo '免费积分';
							break;
						case 'vouchers' :
							echo '抵用券';
							break;
						case 'worth_gold' :
							echo '抵用金';
							break;
					}
					?>
				</p>
			</th>
			<th>
				<p>本月使用</p>
				<p><?php switch($this->_request->t) {
						case 'credit' :
							echo '免费积分';
							break;
						case 'vouchers' :
							echo '抵用券';
							break;
						case 'worth_gold' :
							echo '抵用金';
							break;
					}
					?></p>
			</th>
			<th>
				<p><?php switch($this->_request->t) {
						case 'credit' :
							echo '免费积分';
							break;
						case 'vouchers' :
							echo '抵用券';
							break;
						case 'worth_gold' :
							echo '抵用金';
							break;
					}
					?></p>
				<p>余额</p>
			</th>
		</tr>
		<?php if (!count($this->merchants)) { ?>
			<tr align="center">
				<td colspan="4"><div class="notfound">找不到相关信息</div></td>
			</tr>
		<?php } else {foreach ($this->merchants as $row) {?>
			<tr>
				<td>
<!--					<p style="text-align:left;margin-left:10%;">--><?php //echo $row['nickname'] ? $row['nickname']:$row['username'];?><!--</p>-->
					<p style="text-align:left;margin-left:10%;"><?php echo $row['name']?></p>
				</td>
				<td>
					<?php echo $row['recharge']?>
				</td>
				<td>
					<?php echo $row['consume']?>
				</td>
				<td>
					<?php echo $row['remain'];?>
				</td>
			</tr>
		<?php } }?>


	</table>
	<div class="n-h56"></div>
	<?php //include_once VIEWS.'inc/footer_merchants.php'; ?>
</body>
</html>