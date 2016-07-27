<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '资金帐户';
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
			<a class="btn btn-default btn-sm" href="<?=$this->url('controller=recharge&action=add&ref='.$this->_request->url)?>"> 
			<i class="fa fa-plus-circle"></i> 手工充值</a>
			<a class="btn btn-default btn-sm" href="<?=$this->url('controller=withdraw&action=add&ref='.$this->_request->url)?>"> 
			<i class="fa fa-minus-circle"></i> 手工提现</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th>帐户名</th>
					<th width="130" class="hidden-xs">收入</th>
					<th width="130" class="hidden-xs">支出</th>
					<th width="130">冻结</th>
					<th width="130">余额</th>
					<th width="140" class="hidden-xs">创建时间</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="8"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['username'], $this->_request->q)?></a></td>
					<td class="hidden-xs"><font color="red">&yen; <?=$row['income']?></font></td>
					<td class="hidden-xs"><font color="green">&yen; <?=$row['expend']?></font></td>
					<td><font color="gray">&yen; <?=$row['unusable']?></font></td>
					<td><font color="green">&yen; <?=$row['balance']?></font></td>
					<td class="hidden-xs"><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>">明细</a>
			<a href="<?=$this->url('controller=recharge&action=add&uid=' . $row['id'].'&ref='.$this->_request->url)?>">充值</a>
			<a href="<?=$this->url('controller=withdraw&action=add&uid=' . $row['id'].'&ref='.$this->_request->url)?>">提现</a></td>
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