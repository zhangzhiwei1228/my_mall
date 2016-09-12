
//变更商品数据单位
$.changeUnit = function() {
	var v = $('input[name=package_method]:checked').val();
	switch(parseInt(v)) {
		case 1:
			$('[name=package_quantity]').val(0);
			$('#package-setting').hide();
			var xUnit = $('select[name=package_unit]>option:selected').text();
			break;
		case 2:
			$('#package-setting').show();
			var xUnit = $('input[name=package_lot_unit]').val();
			break;	
	}
	$('.package-unit-text').text($('select[name=package_unit]>option:selected').text());
	$('.package-unit').text(xUnit);
}

//载入商品属性
$.loadAttribute = function(cid, pid) {
	var obj = $('.sc-attr-box');
	$.ajax({cache: true, data: {cid: cid, pid: pid},
		beforeSend: function(XMLHttpRequest){
			$(obj).empty().append('<div class="loading-box">Loading...</div>');
		},
		success: function(data) {
			$('.loading-box').remove();
			$(obj).empty().append(data);
		}, url: '/?module=admincp&controller=goods_type&action=attribute'
	});
}

$.appendCustomItem = function() {
	cusnum++;
	var html = $('#_custpl').html().replace(/@id/g, cusnum);
	$('#custom-input-box').append(html);
}

$.removeCustomItem = function(id) {
	$('#custom-item-'+id).remove();
}

$.buychoose = function (id, k) {
	$('#chk_'+id+'_'+k).change(function(){
		$('#sku_change').val(3);
	});
	$('.ipt_'+id+'_'+k).attr('disabled', !$('#chk_'+id+'_'+k+':checked').attr('id'));
}

$.makeSkuTable = function() {
	var id = $('input[name=goods_id]').val();
	$.post('/?module=admincp&controller=goods_type&action=sku&id='+id, $("form").serialize(), function(data) {
				$('.JS_SkuSettings').empty().append(data);
		if (data) {
			$.processQuantity();
		}
	});
}

$.setColorCode = function(k) {
	var ct = $('.color_title_'+k).val();
	var cc = $('.color_code_'+k).val();
	$('.color_value_'+k).val(cc+'|'+ct);
	$.makeSkuTable();
}

//添加一组批发价
$.appendWholesalePrice = function(option) {
	var packageQuantity = $('input[name=package_quantity]').val();
	var packageUnitText = $('input[name=package_unit]').val();
	var packageLotText = $('input[name=package_lot_unit]').val() ? $('input[name=package_lot_unit]').val() : '包';
	var packageMethod = packageQuantity > 0 ? 2 : 1;
	var unitText = (packageMethod == 2 ? packageLotText : packageUnitText);
	var prevId = $('#-prices-setting tbody').length; var curId = prevId + 1; var nextId = prevId + 2;
	var brokerage = parseFloat($('input[name=brokerage]').val());
	var prevMaxQty = parseInt($('#-price-item-'+prevId+' input.-max-qty').val());
	var nextMinQty = parseInt($('#-price-item-'+nextId+' input.-min-qty').val());
	var html = '<tr id="-price-item-'+curId+'">';
		html += '<td><div class="input-group"><input type="text" name="extend[wholesale_prices]['+curId+'][min_qty]" class="form-control input-sm -min-qty" /><span class="input-group-addon"> ~ </span><input type="text" name="extend[wholesale_prices]['+curId+'][max_qty]" class="form-control input-sm -max-qty" /> <span class="input-group-addon package-unit">'+unitText+'</span></div></td>';
		html += '<td><div class="input-group"><input type="text" name="extend[wholesale_prices]['+curId+'][selling_price]" class="form-control input-sm -selling-price" iptn="selling" curid="'+curId+'" /><span class="input-group-addon"> 元 </span></td>';
		html += '<td><div class="input-group"><input type="text" name="extend[wholesale_prices]['+curId+'][processing_time]" class="form-control input-sm -processing-time" /><span class="input-group-addon"> 天 </span></td>';
		html += '<td><a href="javascript:void(0)" onclick="$(\'#-price-item-'+curId+'\').remove(); $(\'#-price-item-'+prevId+' .remove\').show(); calcLimitQty()" class="remove">移除</a></td>';
		html += '</tr>';
	$('#-prices-setting').append(html);
	if (option) {
		if (option.min_qty) { $('#-price-item-'+curId+' input.-min-qty').val(option.min_qty); }
		if (option.max_qty) { $('#-price-item-'+curId+' input.-max-qty').val(option.max_qty); }
		if (option.supply_price) { $('#-price-item-'+curId+' input.-supply-price').val(option.supply_price); }
		if (option.selling_price) { $('#-price-item-'+curId+' input.-selling-price').val(option.selling_price); }
		if (option.processing_time) { $('#-price-item-'+curId+' input.-processing-time').val(option.processing_time); }
	}
	$('#-price-item-'+prevId+' .remove').hide();
	if (prevMaxQty) { $('#-price-item-'+curId+' input.-min-qty').attr('readonly', true).val(prevMaxQty + 1); }
	$('.-min-qty, .-max-qty', $('#-price-item-'+curId)).change(function() {
		var curMinQty = parseInt($('.-min-qty', $('#-price-item-'+curId)).val());
		var curMaxQty = parseInt($('.-max-qty', $('#-price-item-'+curId)).val());
		if (curMaxQty < curMinQty && curMaxQty) { $('.-max-qty', $('#-price-item-'+curId)).val(curMinQty + 1); alert('数量区间设置错误'); }
		$('#-price-item-'+(curId + 1)+' input.-min-qty').val(parseInt($('.-max-qty', $('#-price-item-'+curId)).val()) + 1);
		$.calcLimitQty();
	});
	$('.-supply-price, .-selling-price', $('#-price-item-'+curId)).change(function() {
		var curid = $(this).attr('curid');
		var iptn = $(this).attr('iptn');
		switch(iptn) {
			case 'selling': $('.-supply-price', $('#-price-item-'+curid)).val(($(this).val() / brokerage).toFixed(2)); break;
			case 'supply': $('.-selling-price', $('#-price-item-'+curid)).val(($(this).val() * brokerage).toFixed(2)); break;
		}
	});
}

/* 处理库存 */
$.processQuantity = function() {
	var quantity = 0;
	$('.buychoose-quantity').each(function () {
		var qty = parseInt($(this).val());
		if (qty == -1) { quantity = -1; return false; }
		quantity += parseInt($(this).val());
	});
	$('.buychoose-quantity').change(function() {
		$('#sku_change').val(3);
	});
	$('input[name=quantity]').val(quantity).attr('readonly', quantity ? true : false);
}

