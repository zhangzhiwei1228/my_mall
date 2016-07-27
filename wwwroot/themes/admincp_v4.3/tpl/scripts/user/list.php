<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }

switch ($this->_request->t) {
	case 'only_enabled': $title = '启用帐户'; break;
	case 'only_disabled': $title = '禁用帐户'; break;
	case 'blacklist': $title = '黑名单'; break;
	default: $title = '用户列表'; 	break;
}
$this->head()->setTitle($title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$paginator = $this->paginator();
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
			<div class="btn-group btn-group-sm">
				<button type="submit" name="act" value="enabled" class="btn btn-default"> <i class="fa fa-check-circle-o"></i> 启用</button>
				<button type="submit" name="act" value="disabled" class="btn btn-default"> <i class="fa fa-times-circle-o"></i> 禁用</button>
			</div>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&role='.$this->_request->role.'&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 创建帐号</a>
		</div>
		<table width="100%" class="table table-striped " data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" class="text-center"><input type="checkbox" role="chk-all" /></th>
					<th width="60">UID</th>
					<th>帐号</th>
					<!-- <th width="60">角色</th> -->
					<th>名称</th>
					<th width="80" class="text-center">免费积分</th>
					<th width="80" class="text-center">快乐积分</th>
					<th width="80" class="text-center">积分币</th>
					<th width="80" class="text-center">余额</th>
					<th width="80" class="text-center">等级</th>
					<th width="150">注册时间</th>
					<th width="50">状态</th>
					<th width="130" class="text-right">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr class="text-center">
					<td colspan="12"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?=$row['id']?></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>"> <?=$this->highlight($row['username'], $this->_request->q)?> </a></td>
					<!-- <td><?=$row['role']?></td> -->
					<td><?=$this->highlight($row['nickname'], $this->_request->q)?></td>
					<td class="text-center"><?=$row['credit']?></td>
					<td class="text-center"><?=$row['credit_happy']?></td>
					<td class="text-center"><?=$row['credit_coin']?></td>
					<td class="text-center"><?=$row['balance']?></td>
					<td class="text-center"><?php switch($row['is_vip']) {
						case 1: echo 'VIP'; break;
						case 2: echo 'VIP1'; break;
						case 3: echo 'VIP2'; break;
						case 4: echo 'VIP3'; break;
						case 5: echo 'VIP4'; break;
						default: echo '未激活'; break;
					} ?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td class="text-center"><a href="<?=$this->url('action=change&m=is_enabled&id='.$row['id'])?>"> <?=$row['is_enabled'] ? '<font class="label label-success">启用</font>' : '<font class="label label-danger">禁用</font>' ?> </a></td>
					<td class="text-right">
						<a href="javascript:;" onclick="$.sendmsgbox(<?=$row['id']?>, '<?=$row['username']?>')" title="私信">私信</a>
						<a href="<?=$this->url('action=edit&id='.(int)$row['id'].'&ref='.$this->_request->url)?>">编辑</a>
						<a href="<?=$this->url('action=delete&id='.(int)$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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

<!-- Modal -->
<div class="modal fade" id="send-msg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:620px">
		<form method="post" class="modal-content" action="<?=$this->url('controller=message&action=new&ref='.$this->_request->url)?>">
			<input type="hidden" name="recipient_uid" class="form-control" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">发私信</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="control-label col-sm-2">内容：</label>
					<div class="col-sm-9">
						<textarea name="content" class="form-control" rows="8"><?=stripcslashes($this->data['content'])?></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				<button type="submit" class="btn btn-primary">立即发送</button>
			</div>
		</form>
		<!-- /.modal-content --> 
	</div>
	<!-- /.modal-dialog --> 
</div>
<!-- /.modal -->

<script>
$.sendmsgbox = function(uid, username) {
	$('.modal-title').text('给“'+username+'”发私信');
	$('[name=recipient_uid]').val(uid);
	$('#send-msg').modal();
}
</script>