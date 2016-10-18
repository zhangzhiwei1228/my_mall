<div class="sui-toolbar text-right">
	<a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#recharge-box">积分充值</a>
</div>

<table width="100%" class="table table-striped" data-plugin="chk-group">
	<thead>
		<tr>
			<th>帐户类型</th>
			<th>备注</th>
			<th width="200">积分</th>
			<th width="200">发生时间</th>
		</tr>
	</thead>
	<tbody>
		<?php if (!count($this->datalist)) { ?>
		<tr align="center">
			<td colspan="6"><div class="notfound">找不到相关信息</div></td>
		</tr>
		<?php } else { foreach ($this->datalist as $row) { ?>
		<tr>
			<td><?php
				switch($row['type']) {
					case 'credit': echo '免费积分'; break;
					case 'credit_happy': echo '快乐积分'; break;
					case 'credit_coin': echo '积分币'; break;
					case 'worth_gold': echo '抵用金'; break;
					case 'vouchers': echo '抵用券'; break;
			} ?></td>
			<td><?=$row['note']?></td>
			<td><?=$row['credit']>0?('+'.abs($row['credit'])):('-'.abs($row['credit']))?></td>
			<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
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
<div class="modal fade" id="recharge-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width:400px">
	<form class="modal-content c-form" method="post">
		<input type="hidden" name="uid" value="<?=$this->_request->id?>">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title" id="myModalLabel">积分充值</h4>
		</div>
		<div class="modal-body">
			<div class="form-group">
				<select name="type" class="form-control">
					<option value="">请选择账户类型</option>
					<option value="credit">免费积分</option>
					<option value="credit_happy">快乐积分</option>
					<option value="credit_coin">积分币</option>
					<option value="vouchers">抵用券</option>
				</select>
			</div>
			<div class="form-group">
				<input type="text" name="point" value="" placeholder="请输入充值点数" class="form-control">
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
		var uid = $('[name=uid]').val();
		var type = $('[name=type]').val();
		var point = $('[name=point]').val();

		if (!type) {
			alert('请选择帐户类型');
			return false;
		}

		if (!point) {
			alert('请输入充值点数');
			return false;
		}

		$('#recharge-box').modal('hide');
		$('.modal-backdrop').remove();

		$.post('<?=$this->url('controller=user_credit&action=recharge')?>', {uid:uid, type:type, point:point}, function(ret){
			if (ret) {
				alert(ret);
			}
			$('#credit').load('<?=$this->url('action=credit&id='.$this->_request->id)?>');
		});

		return false;
	});
</script>