<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '查看优惠券';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="well well-warning">
	<div class="row">
		<div class="col-sm-4">
			<label>优惠券名称:</label>
			<?=$this->data['name']?> [<a href="<?=$this->url('&action=edit&ref='.$this->_request->url)?>">编辑</a>]
		</div>
		<div class="col-sm-4">
			<label>优惠券金额:</label>
			<?=$this->data['amount']?>元
		</div>
		<div class="col-sm-4">
			<label>发行量:</label>
			<?=$this->data['quantity']?>张
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<label>有效期:</label>
			<?=date(DATE_FORMAT,$this->data['start_time'])?> ~ 
			<?=date(DATE_FORMAT,$this->data['end_time'])?>
		</div>
		<div class="col-sm-4">
			<label>满足条件:</label>
			<?=(float)$this->data->precond['amount']?>元
		</div>
		<div class="col-sm-4">
			<label>使用说明:</label>
			<?=$this->data['description']?>
		</div>
	</div>
</div>

<div class="sui-box">

	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<div class="panel-body">
		<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
			<div class="sui-toolbar">
				<ul class="pagination pagination-sm pull-right">
					<?=$this->paginator($this->datalist)?>
				</ul>
				<a class="btn btn-default btn-sm" href="<?=$this->url('action=export&ref='.$this->_request->url)?>"> 
					<i class="glyphicon glyphicon-export"></i> 导出</a>
			</div>
			<table width="100%" class="table table-striped" data-plugin="chk-group">
				<thead>
					<tr>
						<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
						<th>优惠券代码</th>
						<th>领取</th>
						<th>使用</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->codes as $row) { ?>
					<tr>
						<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
						<td><code><?=$row['code']?></code></td>
						<th><?=$row['user_id']?$row['user_id']:'<span class="label label-default">未领取</span>'?></th>
						<th><?=$row['is_used']?$row['is_used']:'<span class="label label-default">未使用</span>'?></th>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>
</div>
