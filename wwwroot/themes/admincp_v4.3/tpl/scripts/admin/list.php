<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('管理员列表');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<ul class="nav nav-pills">
			<li class="active"><a href="<?=$this->url('controller=admin')?>"><i class="fa fa-user"></i> 管理员帐户</a></li>
			<li><a href="<?=$this->url('controller=admin_group')?>"><i class="fa fa-users"></i> 帐户分组</a></li>
			<li><a href="<?=$this->url('controller=admin_acl')?>"><i class="fa fa-list"></i> 权限控制表</a></li>
		</ul>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<div class="btn-group btn-group-sm">
				<button type="submit" name="act" value="enabled" class="btn btn-default"> 
					<i class="fa fa-check-circle-o"></i> 启用</button>
				<button type="submit" name="act" value="disabled" class="btn btn-default"> 
					<i class="fa fa-times-circle-o"></i> 禁用</button>
			</div>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
				<i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> 
				<i class="fa fa-plus-circle"></i> 创建管理员</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="60">#ID</th>
					<th>用户名/昵称</th>
					<th width="120">权限组</th>
					<th width="160">最后登陆</th>
					<th width="160">注册时间</th>
					<th width="50" align="center">状态</th>
					<th width="90">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="8" style="padding:0"><p class="alert alert-warning" style="margin:0;">找不到相关信息</p></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" <?=$row['is_locked'] ? 'disabled' : ''?> /></td>
					<td><?=$row['id']?></td>
					<td><a href="<?=$this->url('action=edit&id=' . $row['id'] . '&ref=' . $this->_request->url)?>"> <?=$this->highlight($row['username'], $this->_request->q)?> </a> <span style="color:#888"> <?=$row['nickname'] ? ' - '.$this->highlight($row['nickname'], $this->_request->q) : ''?> </span> <?php if ($row['last_online_time'] > time() - ONLINE_TIMEOUT) { echo '<span class="label label-info">在线</span>'; } ?></td>
					<td><?=$row['group_name']?></td>
					<td><?=$row['last_login_time'] ? date(DATETIME_FORMAT, $row['last_login_time']) : 'N/A'?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td align="center"><a href="<?=$this->url('action=change&m=is_enabled&id='.$row['id'])?>"> <?=$row['is_enabled'] ? '<font class="label label-success">启用</font>' : '<font class="label label-danger">禁用</font>' ?> </a></td>
					<td><a href="<?=$this->url('action=edit&id='.(int)$row['id'].'&ref=' . $this->_request->url)?>">设置</a> <a href="<?=$this->url('action=delete&id='.(int)$row['id'].'&ref=' . $this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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