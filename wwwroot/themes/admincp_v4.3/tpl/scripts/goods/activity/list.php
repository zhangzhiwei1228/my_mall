<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '促销活动';
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
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
				<i class="fa fa-trash-o"></i> 删除</button>
						<button type="submit" name="act" value="enabled" class="btn btn-default btn-sm">启用</button>
						<button type="submit" name="act" value="disabled" class="btn btn-default btn-sm">禁用</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> 
				<i class="fa fa-plus-circle"></i> 添加活动</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
									<th width="20" class="text-center"><input type="checkbox" role="chk-all" /></th>
					<th width="60" class="text-center">优先级</th>
					<th>活动名称</th>
					<th width="120" class="text-center">活动方式</th>
					<th width="200">活动时间</th>
					<th width="140">创建时间</th>
					<th width="50">状态</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="8"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td class="text-center"><?=$row['priority']?></td>
					<td><a href="<?=$this->url('action=edit&id='.$row['id'])?>"><?=$row['theme']?></a></td>
					<td class="text-center"><?=$row->getType()?></td>
					<td><?=$row['start_time'] ? date(DATE_FORMAT, $row['start_time']) : 'N/A'?> 至 
											<?=$row['end_time'] ? date(DATE_FORMAT, $row['end_time']) : '永久'?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td class="text-center"><a href="<?=$this->url('action=enabled&id='.$row['id'].'&ref='.$this->_request->url)?>">
						<?=$row['is_enabled'] ? '<font class="label label-success">启用</font>' : '<font class="label label-danger">禁用</font>'?></a></td>
					<td>
						<a href="<?=$this->url('action=edit&id='.(int)$row['id'].'&ref='.$this->_request->url)?>">编辑</a>
						<a href="<?=$this->url('action=delete&id='.(int)$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
					</td>
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