<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '财务概况';
$this->head()->setTitle($this->title);
?>

<div class="panel panel-default">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>

	<table class="table table-bordered" data-plugin="chk-group">
		<thead>
			<tr>
				<th width="120"></th>
				<?php foreach($this->report as $month => $row) { ?>
				<th><?=$month?></th>
				<?php } ?>
			</tr>
		</thead>
		<body>
			<tr>
				<th>充值总额</th>
				<?php foreach($this->report as $month => $row) { ?>
				<td>&yen; <?=abs($row['recharge'])?></td>
				<?php } ?>
			</tr>
			<tr>
				<th>提现总额</th>
				<?php foreach($this->report as $month => $row) { ?>
				<td>&yen; <?=abs($row['withdraw'])?></td>
				<?php } ?>
			</tr>
			<tr>
				<th>资金池结余</th>
				<?php foreach($this->report as $month => $row) { ?>
				<td>&yen; <?=$row['recharge'] - $row['withdraw']?></td>
				<?php } ?>
			</tr>
			<tr>
				<th>订单收入</th>
				<?php foreach($this->report as $month => $row) { ?>
				<td>&yen; <?=$row['pay'] - $row['pay']?></td>
				<?php } ?>
			</tr>
			<tr>
				<th>手续费收入</th>
				<?php foreach($this->report as $month => $row) { ?>
				<td>&yen; <?=$row['fee']?></td>
				<?php } ?>
			</tr>
		</body>
	</table>
</div>