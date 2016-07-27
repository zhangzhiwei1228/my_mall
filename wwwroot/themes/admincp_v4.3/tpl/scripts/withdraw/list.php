<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '提现申请';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" action="<?=$this->url('action=search')?>" class="sui-searchbox form-inline">
		<input type="hidden" name="search" value="1" />
		<input type="hidden" name="page" value="1" />
		<div class="form-group">
			<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入关键词查询" />
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
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> 
			<i class="fa fa-plus-circle"></i> 手工提现</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="80">流水号</th>
					<th width="120">提现银行</th>
					<th>备注</th>
					<th width="120">提现金额</th>
					<th width="120">手续费</th>
					<th width="120">资金帐户</th>
					<th width="140">发生时间</th>
					<th width="60">状态</th>
					<th width="80">操作</th>
					<!--<th width="90">操作员</th>-->
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
					<td><a href="<?=$this->url('action=edit&id='.$row['id'])?>"><?=$row['id']?></a></td>
					<td><?=$row['bank_name']?></td>
					<td><?=$row['remark']?><em><?=$row['voucher']?' - '.strtoupper($row['voucher']):''?></em></td>
					<td><font color="green">&yen; <?=$row['amount']?></font></td>
					<td><font color="green">&yen; <?=$row['fee']?></font></td>
					<td><a href="<?=$this->url('controller=money&action=detail&id='.$row['user_id'])?>"><?=$this->highlight($row['username'], $this->_request->q)?></a></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><?php switch ($row['status']) {
					case 0: echo '<font class="label label-danger">撤销</font>'; break;	
					case 1: echo '<font class="label label-default">未入账</font>'; break;	
					case 2: echo '<font class="label label-success">已入账</font>'; break;	
					}?></td>
					<td>
					<?php if ($row['status'] == 0) { ?>
					<a href="<?=$this->url('action=rollback&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要回滚吗?')">回滚</a>
					<a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
					<?php } elseif ($row['status'] == 1) { ?>
					<a href="<?=$this->url('action=commit&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要打款吗?')">打款</a>
					<a href="<?=$this->url('action=cancel&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要撤销吗?')">撤销</a>
					<?php } elseif ($row['status'] == 2) { ?>
					<a href="<?=$this->url('action=rollback&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要回滚吗?')">回滚</a>
					<a href="<?=$this->url('action=cancel&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要撤销吗?')">撤销</a>
					<?php } ?>
					</td>
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