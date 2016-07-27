<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<h5 class="heading">选择商品</h5>
<div class="panel panel-default">
	<table width="100%" class="table table-bordered">
		<tr>
			<td width="50%" class="JS_SelectedBody" valign="top" style="padding:0"></td>
			<td width="50%" class="JS_SelectableBody" valign="top" style="padding:0"></td>
		</tr>
	</table>
</div>
<input type="hidden" name="setting[goods_ids]" class="JS_GoodsIds" value="<?=$this->setting['goods_ids']?>" />
<input type="hidden" name="selectable_page" class="JS_SelectablePage" value="1" />
<input type="hidden" name="selected_page" class="JS_SelectedPage" value="1" />

<script type="text/javascript">

function loadSelectedPage(page) {
	if (page) { $('.JS_SelectedPage').val(page) };
	$.post('<?=$this->url('action=goods_selected')?>', $(".JS_Form").serialize(), function(result) {
		$('.JS_SelectedBody').html(result);
	});
}

function loadSelectablePage(page)
{
	if (page) { $('.JS_SelectablePage').val(page) };
	$.post('<?=$this->url('action=goods_selectable')?>', $(".JS_Form").serialize(), function(result) {
		$('.JS_SelectableBody').html(result);
	});
}

function selected(pid) {
	var ipt = $('.JS_GoodsIds');
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
	loadSelectedPage(1); loadSelectablePage();
}
$.fn.ready(function() {
	loadSelectedPage(1); loadSelectablePage(1);
});

</script>