<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>

<h5 class="heading">批量设置</h5>
<div class="well">
	<div class="form-group">
		<label class="control-label col-sm-2">价格标签</label>
		<div class="col-sm-4">
			<input type="text" class="form-control JS_PriceLabel" name="setting[price_label]" value="<?=$this->setting['price_label'] ? $this->setting['price_label'] : '促销价'?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">设置折扣</label>
		<div class="col-sm-4">
			<div class="input-group">
				<input type="text" class="form-control JS_Discount" name="setting[discount]" value="<?=$this->setting['discount'] ? $this->setting['discount'] : '10'?>" /> 
				<span class="input-group-addon">折</span>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="button" class="btn btn-default btn-sm" onclick="batchSetting()">批量设置</button>
		</div>
	</div>
</div>
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
function batchSetting()
{
	var priceLabel = $('.JS_PriceLabel').val();
	var discount = parseFloat($('.JS_Discount').val());
	if (!discount) {
		alert('错误类型，折扣必须是1~10之间的数字');		
	} else if (discount > 10) {
		alert('错误设置，折扣不能大于10');
		return false;
	} else if (discount < 0) {
		alert('错误设置，折扣不能小于0');
		return false;
	}
	
	$('.JS_Item').each(function() {
		var sellingPrice = parseFloat($('.JS_SellingPrice', this).val());
		var promotionPrice = parseFloat(sellingPrice) * (discount/10);
		
		$('.JS_PriceLabel', this).val(priceLabel);
		$('.JS_Discount', this).val(discount);
		$('.JS_PromotionPrice', this).html('&yen;'+promotionPrice.toFixed(2));
	});
	
}

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