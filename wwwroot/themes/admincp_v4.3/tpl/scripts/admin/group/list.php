<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('分组列表');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<ul class="nav nav-pills">
			<li><a href="<?=$this->url('controller=admin')?>"><i class="fa fa-user"></i> 管理员帐户</a></li>
			<li class="active"><a href="<?=$this->url('controller=admin_group')?>"><i class="fa fa-users"></i> 帐户分组</a></li>
			<li><a href="<?=$this->url('controller=admin_acl')?>"><i class="fa fa-list"></i> 权限控制表</a></li>
		</ul>
	</div>
	<p class="alert alert-warning" style="margin-top:15px"><strong>注意！</strong>请勿删除系统分组，否则可能导致系统无法登陆。</p>
	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<div class="btn-group btn-group-sm">
				<button type="submit" name="act" value="enabled" class="btn btn-default"> <i class="fa fa-check-circle-o"></i> 启用</button>
				<button type="submit" name="act" value="disabled" class="btn btn-default"> <i class="fa fa-times-circle-o"></i> 禁用</button>
			</div>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 创建分组</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="60">#ID</th>
					<th width="120">分组名称</th>
					<th>描述</th>
					<th width="160">创建时间</th>
					<th width="90">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="6"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" <?=$row['is_locked'] ? 'disabled' : ''?> /></td>
					<td><?=$row['id']?></td>
					<td><a href="<?=$this->url('action=edit&id='.(int)$row['id'].'&ref='.$this->_request->url)?>"> <?=$row['name']?> </a></td>
					<td><?=$row['description']?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><?php if (!$row['is_locked']) { ?> <a href="<?=$this->url('action=edit&id='.(int)$row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('action=delete&id='.(int)$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a> <?php } else { ?> <i class="fa fa-lock"></i> 禁止操作 <?php } ?></td>
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