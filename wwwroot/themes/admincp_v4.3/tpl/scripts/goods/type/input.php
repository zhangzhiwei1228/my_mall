<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '种类';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">种类名称:</label>
			<div class="col-sm-9"><input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" /></div>
		</div>
		<h4 class="heading">属性设置</h4>
		<table id="_attrs" width="100%" class="table">
			<thead>
				<tr>
					<th width="180">属性名称</th>
					<th width="240">输入方式</th>
					<th width="240">是否检索</th>
					<th>可选值列表(一行代表一个可选值)</th>
				</tr>
			</thead>
			<tbody></tbody>
			<tfoot>
				<tr>
					<td colspan="6"><a class="_append"><i class="fa fa-plus-circle"></i> 添加属性</a></td>
				</tr>
			</tfoot>
		</table>
		<div class="alert alert-info">
			<b>小提示：</b> 通过点击表格中的空白处进行拖拽，可更变其排序。
		</div>
		<div class="operate" style="margin-top:40px">
			<div class="col-sm-9">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script id="_attrtpl" type="text/template">
	<tr class="item_@id">
		<td valign="top"><input type="text" name="attr_setting[@id][attr_name]" class="form-control input-xs _attr_name" /><br />
			<a class="_remove"><i class="fa fa-minus-circle-sign"></i> 移除此属性</a>
			</td>
		<td valign="top">
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="text" checked="checked" /> 手工录入</label>
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="textarea" /> 多行文本框</label><br />
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="select" /> 下拉菜单</label>
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="radio" /> 单选属性</label><br />
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="checkbox" /> 复选属性</label>
			<label><input type="radio" name="attr_setting[@id][input_type]" class="_input_type" value="buychoose" /> 选购项</label>
			</td>
		<td valign="top">
			<label><input type="radio" name="attr_setting[@id][is_search]" class="_is_search" disabled="disabled" value="1" /> 是</label>
			<label><input type="radio" name="attr_setting[@id][is_search]" class="_is_search" disabled="disabled" value="0" checked="checked" /> 否</label>
			<p class="help-block">置为检索，用户可在查找商品时通过此属性的可选值进行筛选</p></td>
		<td valign="top"><textarea name="attr_setting[@id][attr_values]" class="form-control input-sm _attr_values" disabled="disabled" style="width:320px; height:80px;"></textarea></td>
	</tr>
</script> 
<script type="text/javascript">
var attrNum = 0;
var sizeNum = 0;
$.fn.ready(function() {
	$(document).on('click', '._remove', function() { $(this).parents('tr').remove(); });
	$(document).on('click', '._input_type', function () { changeInputType($(this).parents('tr'), $(this).val()) });	
	$(document).on('click', '#_attrs ._append', function() { appendAttr({id:attrNum}); });
	<?php $attrs = $this->data['attr_setting'];
	if ($attrs) { foreach ((array)$attrs as $k => $row) {
	echo "appendAttr(".json_encode($row).");\r\n";
	} } ?>
});

function changeInputType(p, v) {
	if (v == 'text' || v == 'textarea') {
		$('._is_search', p).prop('disabled', true);
		$('._attr_values', p).prop('disabled', true);
	} else {
		$('._is_search', p).prop('disabled', false);
		$('._attr_values', p).prop('disabled', false);
	}
	if (v == 'buychoose') {
		$('._attr_type', p).prop('disabled', false);	
	} else {
		$('._attr_type', p).prop('disabled', true);	
	}
}

function appendAttr(option) { 
	attrNum++;
	var html = $('#_attrtpl').html().replace(/@id/g, attrNum); $('#_attrs tbody').append(html);
	var dom = $('.item_'+attrNum, '#_attrs');
	if (option.attr_name) { 
		$('._attr_name', dom).val(option.attr_name); 
	}
	if (option.attr_values) { 
		$('._attr_values', dom).val(option.attr_values); 
	}
	if (option.is_search) {
		$('._is_search[value='+option.is_search+']', dom).prop('checked', true); 
	}
	if (option.input_type) {
		$('._input_type[value='+option.input_type+']', dom).prop('checked', true);
		changeInputType(dom, option.input_type);
	}
	if (option.attr_type) {
		$('._attr_type [value='+option.attr_type+']', dom).prop('selected', true);
	}
	
	seajs.use('/assets/js/dragsort/jquery.dragsort-0.5.2.js', function(dragsort){
		$('tbody').dragsort("destroy");
		$('tbody').dragsort({
			dragSelectorExclude: "input, textarea, a, button, select",
			dragSelector:'tr', 
		});
	});
}
</script>