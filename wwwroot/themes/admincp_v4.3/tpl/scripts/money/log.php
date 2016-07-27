<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '资金流水情况';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入帐户名查询" />
				<?php if ($this->_request->q) { ?>
				<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
				<?php } ?>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<a class="btn btn-default btn-sm" href="<?=$this->url('controller=recharge&action=add&ref='.$this->_request->url)?>"> 
				<i class="fa fa-plus-circle"></i> 手工充值</a>
			<a class="btn btn-default btn-sm" href="<?=$this->url('controller=withdraw&action=add&ref='.$this->_request->url)?>"> 
				<i class="fa fa-minus-circle"></i> 手工提现</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="140">发生时间</th>
					<th width="100">类型</th>
					<th>备注</th>
					<th width="120">收入</th>
					<th width="120">支出</th>
					<th width="120">结余</th>
					<th width="120">资金帐户</th>
					<th width="90">状态</th>
					<!-- <th width="90">操作员</th> -->
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="9"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><?=M('User_Money')->getTypeText($row['type'])?></td>
					<td><?=$row['remark']?></td>
					<td style="color:green"><?=$row['amount']>0?$this->currency($row['amount']):'-'?></td>
					<td style="color:red"><?=$row['amount']<0?$this->currency($row['amount']):'-'?></td>
					<td><?=$this->currency($row['balance'])?></td>
					<td><a href="<?=$this->url('controller=money&action=detail&id='.$row['user_id'])?>"><?=$this->highlight($row['username'], $this->_request->q)?></a></td>
					<td><?php switch ($row['status']) {
						case 0: echo '<font class="label label-danger">失败</font>'; break;	
						case 1: echo '<font class="label label-default">处理中</font>'; break;	
						case 2: echo '<font class="label label-success">成功</font>'; break;	
					}?></td>
					<!--<td><?=$row['admin_id'] ? $row['admin_name'] : '系统'?></td>-->
				</tr>
				<?php } } ?>
			</tbody>
		</table>
		<div class="sui-toolbar">
			<script type="text/javascript">
				var toolbar = $('.sui-toolbar').clone();
				document.write(toolbar.html());
			</script>
		</div>
	</form>

</div>