<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '发票管理';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$types = M('Invoice')->getTypes();
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<ul class="nav nav-pills">
			<li <?=$this->_request->t=='pending' ? 'class="active"' : ''?>><a href="<?=$this->url('&t=pending')?>"><i class="fa fa-question-circle"></i> 待开发票</a></li>
			<li <?=$this->_request->t=='yes' ? 'class="active"' : ''?>><a href="<?=$this->url('&t=yes')?>"><i class="fa fa-check-circle-o"></i> 已开发票</a></li>
		</ul>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
			<?=$this->paginator($this->datalist)?>
			</ul>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
			<i class="fa fa-trash-o"></i> 删除</button>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="160">发票类型</th>
					<th>发票抬头</th>
					<th width="180">开票金额</th>
					<th width="160">创建时间</th>
					<th width="80">状态</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="7"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?=$types[$row['type_id']]?></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['title'], $this->_request->q)?>
						</a></td>
					<td><?=$this->currency($row['invoice_amount'])?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><?php switch($row['status']) {
						case 0: echo '<span class="label label-default">未下单</span>'; break;
						case 1: echo '<span class="label label-default">待开</span>'; break;
						case 2: echo '<span class="label label-success">已开</span>'; break;
						}?></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>">查看</a> <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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