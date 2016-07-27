<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '支付方式';
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
			<button type="submit" name="act" value="update" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
			<i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> 
			<i class="fa fa-plus-circle"></i> 添加支付方式</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="120">支付方式</th>
					<th>描述</th>
					<th width="100">手续费</th>
					<th width="60">状态</th>
					<th width="150">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody data-plugin="dragsort">
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="6"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center">
						<input type="hidden" name="data[<?=$row['id']?>][rank]" value="<?=$row['rank']?>" />
						<input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['name'], $this->_request->q)?>
						</a></td>
					<td><?=$row['description']?></td>
					<td>&yen; <?=$row['fee']?></td>
					<td align="center"><a href="<?=$this->url('action=change&m=is_enabled&id='.$row['id'])?>"> <?=$row['is_enabled'] ? '<font class="label label-success">启用</font>' : '<font class="label label-danger">禁用</font>' ?> </a></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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