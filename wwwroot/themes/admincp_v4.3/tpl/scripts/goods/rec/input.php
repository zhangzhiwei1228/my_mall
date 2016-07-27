<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '橱窗';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem js-form">
		<div class="form-group">
			<label class="control-label col-sm-2">橱窗代码:</label>
			<div class="col-sm-6"><input type="text" name="code" value="<?=$this->data['code']?>" class="form-control" />
				<div class="help-block">当程序需要引用橱窗时将会使用此代码</div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">橱窗名称:</label>
			<div class="col-sm-6"><input type="text" name="theme" value="<?=$this->data['theme']?>" class="form-control" /></div>
		</div>
		<div class="panel panel-default">
			<table width="100%" class="table table-bordered">
				<tr>
					<td width="50%" id="selected" valign="top" style="padding:0"></td>
					<td width="50%" id="goods" valign="top" style="padding:0"></td>
				</tr>
			</table>
			<input type="hidden" name="goods_ids" class="_goods_input" value="<?=$this->data['goods_ids']?>" />
			<input type="hidden" name="page1" value="1" />
			<input type="hidden" name="page2" value="1" />
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2"><button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button></div>
		</div>
	</form>
</div>

<script type="text/javascript">
function gotopage1(page) {
	if (page) { $('input[name=page1]').val(page) };
	$.post('<?=$this->url('action=goods')?>', $(".js-form").serialize(), function(result) {
		$('#goods').html(result);
	});
}

function gotopage2(page)
{
	if (page) { $('input[name=page2]').val(page) };
	$.post('<?=$this->url('action=selected')?>', $(".js-form").serialize(), function(result) {
		$('#selected').html(result);
	});
}

function selected(pid) {
	var ipt = $('._goods_input');
	
	var val = ipt.val();
	var data = val ? val.split(',') : new Array();
	var idx = $.inArray(pid.toString(), data);
	if (idx >= 0) {
		delete data[idx];
	} else {
		data.push(pid);
	}
	
	var arr = new Array();
	for(i=0; i<=data.length; i++) {
		if (data[i]) {
			arr.push(data[i]);
		}
	}

	$(ipt).val(arr);
	gotopage1(); gotopage2(1);
}
$.fn.ready(function() {
	gotopage1(1);
	gotopage2(1);
});

</script>
