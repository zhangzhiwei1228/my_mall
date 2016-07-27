<div class="sui-toolbar text-right">
	<a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_user-box">添加账户</a>
</div>

<table width="100%" class="table table-striped" data-plugin="chk-group">
	<thead>
		<tr>
			<th>编号</th>
			<th>用户名</th>
			<th>手机号码</th>
			<th width="200">创建时间</th>
			<th width="50"></th>
		</tr>
	</thead>
	<tbody>
		<?php if (!count($this->datalist)) { ?>
		<tr align="center">
			<td colspan="6"><div class="notfound">找不到相关信息</div></td>
		</tr>
		<?php } else { foreach ($this->datalist as $row) { ?>
		<tr>
			<td><?=$row['id']?></td>
			<td><?=$row['username']?></td>
			<td><?=$row['mobile']?></td>
			<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
			<td><a href="javascript:;" data-id="<?=$row['id']?>" class="btn-del">删除</a></td>
		</tr>
		<?php } } ?>
	</tbody>
</table>
<div class="text-center">
	<ul class="pagination pagination-sm">
		<?=$this->paginator($this->datalist)->getAjaxBar('$.gotopage')?>
	</ul>
</div>

<script type="text/javascript">
	$.gotopage = function(page) {
		$('#credit').load('<?=$this->url('action=credit&id='.$this->_request->id)?>', {page: page});
		console.log(page);
	}
</script>

<!-- Modal -->
<div class="modal fade" id="add_user-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
	<form class="modal-content c-form" method="post">
		<input type="hidden" name="parent_id" value="<?=$this->_request->id?>">
		<input type="hidden" name="role" value="staff">
		<input type="hidden" name="is_enabled" value="1">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="myModalLabel">添加账户</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<input type="text" name="username" value="" placeholder="用户名" class="form-control">
			</div>
			<div class="form-group">
				<input type="text" name="password" value="" placeholder="密码" class="form-control">
			</div>
			<div class="form-group">
				<input type="text" name="mobile" value="" placeholder="手机号码" class="form-control">
			</div>
		</div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal" >关闭</button>
		<button type="submit" class="btn btn-primary">保存设置</button>
		</div>
	</form><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->	

<script type="text/javascript">
	$('.c-form').on('submit', function(){
		$('#add_user-box').modal('hide');
		$('.modal-backdrop').remove();

		$.post('<?=$this->url('controller=user&action=add')?>', $('.c-form').serialize(), function(ret){
			$('#staff').load('<?=$this->url('action=staff&id='.$this->_request->id)?>');
		});

		return false;
	});
	$('.btn-del').on('click', function(){
		var id = $(this).data('id');
		$.get('<?=$this->url('controller=user&action=delete')?>', {id:id});
		$('#staff').load('<?=$this->url('action=staff&id='.$this->_request->id)?>');
	});
</script>