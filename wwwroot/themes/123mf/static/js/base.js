
// 弹框
$.dialog = function(msg, opts) {
	seajs.use(['/assets/js/weebox/weebox.js', 
		'/assets/js/weebox/bgiframe.js', 
		'/themes/lhjmall/css/plugins/weebox.css'], function(){
		$.weeboxs.open(msg, opts);
	});
}

// 提示框
$.notice = function(title, msg) {
	$.dialog(msg, {
		title: title,
		type: 'dialog',
		width: 400,
		isFull: false,
		showTitle: title ? 1: 0,
		showButton: false
	});
}

// 会员登录
$.login = function(callback) {
	if ($.getCookie('User')) {
		return true;
	}

	var ref = encodeURIComponent(window.location.href);
	$.dialog('/?module=usercp&controller=passport&action=fast_login&ref='+ref, {
		contentType: 'ajax',
		type: 'dialog',
		title: '您尚未登录',
		width: 360,
		height: 300,
		showButton: false,
	});

	seajs.use('/assets/js/validator/validator.sea.js', function(validator){
		validator('.lhj-fast-login', {
			rules: {
				'[name=mobile]': { valid: 'required|mobile', errorText: '请输入手机号码|号码格式不正确' },
				'[name=password]': { valid: 'required', errorText: '请输入密码' }
			}
		});
	});

	return false;
}

// 立即购买
$.buynow = function(params) {
	console.log(this);

	if ($.login(this)) {
		$.getJSON('/?module=default&controller=cart&action=add', params, function(json){
			$('.cart-qty').text(json.qty);
			var arr = {};
			var s = params.split("&");
			for(var i=0;i<s.length;i++){
				var d=s[i].split("=");	
				eval("arr."+d[0]+" = '"+d[1]+"';");	
			}

			var tmpForm = document.createElement("form");
			
			document.body.appendChild(tmpForm); 
			var key = arr['goods_id']+'.'+arr['sku_id'];

			var tmpInput = document.createElement("input"); 
			tmpInput.type = 'hidden';
			tmpInput.name = 'cart['+key+'][checkout]';
			tmpInput.value = 1;
			tmpForm.appendChild(tmpInput);

			tmpForm.method = 'post';
			tmpForm.action = '/cart/checkout';
			tmpForm.submit(); 
		});
	}
}

// 关注
$.fn.follow = function(refType, refId) {
	//...
}

// 喜欢
$.fn.like = function(refType, refId) {
	var el = $(this);
	if ($.login()) {
		$.getJSON('/?module=usercp&controller=like&action=add', {ref_type:refType, ref_id:refId}, function(json){
			if (el) {
				$(el).text(json.total);
				$(el).css('position', 'relative');
				$(el).append('<span class="js-animate">'+(json.status?'+':'-')+'1</span>');
				$('.js-animate', el).css('position', 'absolute')
					.css('margin-left','10px')
					.animate({
						top: '-12px',
						opacity: 0
					}, 'slow');
			}
		});
	}
}

// 收藏
$.fn.collect = function(refType, refId) {
	var el = $(this);
	var text = $(this).text();
	//console.log(refType);
	if ($.login()) {
		$.getJSON('/?module=usercp&controller=collect&action=add', {ref_type:refType, ref_id:refId}, function(json){
			console.log(json);
			$(el).html(json.status ? '收藏商品' : '取消收藏');
		});
	}
}

// 添加到购物车
$.addCart = function(params) {
	$.getJSON('/?module=default&controller=cart&action=add', params, function(json){
		$('.cart-qty').text(json.qty);
		$.notice(0, '<div class="text-block" style="line-height:26px;">'
			+'<h3><i class="icon-ok icon-large" style="color:#39a30b"></i> 添加成功！</h3>'
			+'<div style="margin-bottom:15px;">当前购物车中有'+json.qty+'件宝贝</div>'
			+'<a href="/cart" class="btn btn-primary btn-sm">去购物车结算</a>　'
			+'<a href="javascript:;" class="btn btn-default btn-sm" onclick="$.weeboxs.close();">继续购物</a>'
			+'</div>');
	});
}

// 删除购物车中商品
$.removeCart = function(code) {
	$.get('/?module=default&controller=cart&action=delete', {code:code}, function(){
		$.refreshCart();
	});
}

// 更新购物车
$.updateCart = function(data) {
	$.post('/?module=default&controller=cart&action=update', data, function(){
		$.refreshCart();
	});
}

// 刷新购物车
$.refreshCart = function() {
	// var loadImgSrc = '/themes/default/img/loading.gif';
	// $('.my-cart').append('<div class="loading"><img src="'+loadImgSrc+'" /></div>');
	// $('.my-cart').load('/?module=default&controller=cart&action=default');

	var total_amount = 0;
	var total_credit = 0;
	var total_credit_coin = 0;
	var total_credit_happy = 0;
	var total_vouchers = 0;
	var html = '<p></p>';
	$('[role=chk-item]:checked').each(function(i){
		var subtotal_amount = $(this).data('amount');
		var subtotal_credit = $(this).data('credit');
		var subtotal_credit_happy = $(this).data('credit-happy');
		var subtotal_credit_coin = $(this).data('credit-coin');
		var subtotal_vouchers = $(this).data('vouchers');

		total_amount += subtotal_amount;
		total_credit += subtotal_credit;
		total_credit_happy += subtotal_credit_happy;
		total_credit_coin += subtotal_credit_coin;
		total_vouchers += subtotal_vouchers;

		html = '<p></p>';
		if (total_credit_happy>0) {
			html += '<p>'+total_credit_happy+'快乐积分</p>';
		}
		if (total_credit>0) {
			html += '<p>'+total_credit+'帮帮币</p>';
		}
		if (total_credit_coin>0) {
			html += '<p>'+total_credit_coin+'积分币</p>';
		}
		if (total_amount>0) {
			html += '<p>'+total_amount+'现金</p>';
		}
		if (total_vouchers>0) {
			html += '<p>'+total_vouchers+'抵用券</p>';
		}
	});
	$('.n-shopping-end-r').html(html);
}

// 设置收货地址
$.setAddr = function(id) {
	if (id == 0) {
		$('.js-addr-item').removeClass('active');
		$('.js-addr-'+id).addClass('active');
		$('.js-addr-form').show();
		$('.js-addr-form input').val('');
		$('[name=addr_id]', '#addr-0').prop('checked', 1);
	} else {
		var data = $('.js-addr-'+id).data('opts');
		$('.js-addr-form').hide();
		$('.js-addr-item').removeClass('active');
		$('.js-addr-'+id).addClass('active');
		$('[name=addr_id]','.js-addr-'+id).prop('checked', true);
		$('[name=area_id]', '.js-addr-form').val(data.area_id);
		$('[name=area_text]', '.js-addr-form').val(data.area_text);
		$('[name=consignee]', '.js-addr-form').val(data.consignee);
		$('[name=address]', '.js-addr-form').val(data.address);
		$('[name=zipcode]', '.js-addr-form').val(data.zipcode);
		$('[name=phone]', '.js-addr-form').val(data.phone);
		$('[name=email]', '.js-addr-form').val(data.email);
		$('[name=is_def]', '.js-addr-form').val(data.is_def);
		$('[name=def_ipt]').prop('checked', parseInt(data.is_def));
	}
}

// 保存收货地址
$.saveAddr = function() {
	seajs.use('/assets/js/validator/validator.sea.js', function(validator){
		var vd1 = validator('.js-addr-form', {
			rules: {
				'[name=consignee]': { valid: 'required', errorText: '请填写收件人姓名' },
				'[name=area_text]': { valid: 'required', errorText: '请选择省市区' },
				'[name=address]': { valid: 'required', errorText: '请填写收货地址' },
				'[name=zipcode]': { valid: 'required', errorText: '请填写邮编' },
				'[name=phone]': { valid: 'required', errorText: '请填写联系电话' },
			}
		});
		if (vd1.run()) {
			var id = $('[name=addr_id]:checked').val();
			var targetUrl = id == 0 
				? '/?module=usercp&controller=address&action=add'
				: '/?module=usercp&controller=address&action=edit&id='+id;
			$.post(targetUrl, $('.my-cart').serialize(), function(){
				$('.js-addr-box').loadAddr();
			});
		}
	});
}

// 编辑收货地址
$.editAddr = function(id) {
	$.setAddr(id);
	$('.js-addr-form').show();
	$('.js-dmenu').loadRegionMenu();
	$('.js-shipping-box').loadShipping();
}

// 删除收货地址
$.removeAddr = function(id) {
	$.get('/?module=usercp&controller=address&action=delete&id='+id);
	$('.js-addr-box').loadAddr();
}

// SKU选购项
$.buychoose = function(el) {
	$(el).siblings().removeClass('active');
	$(el).addClass('active');

	var spec = new Array();
	$('.sku-list a.active').each(function(){
		spec.push('['+$(this).data('param')+']');
	});

	$('[name=sku_id] option').each(function() {
		var v = $(this).text();
		if (v == spec) {
			$(this).prop('selected', true);
			$('[name=sku_id]').loadSkuInfo();
		}
	});
}

// 增加数量
$.plusQty = function(el) {
	var v = parseInt($(el).val())+1;
	var max = $(el).data('max');
	if (max && v > max) { 
		alert('库存不足，您最多可以购买'+max+'件');
		$(el).val(max);
		return false;
	}
	$(el).val(v);
}

// 减少数量
$.minusQty = function(el) {
	var v = parseInt($(el).val())-1;
	if (v <= 0) { alert('购买数量不能少于1'); return false; }
	$(el).val(v);
}

// 设置COOKIE
$.setCookie = function (name, value) {
	var Days = 30; //此 cookie 将被保存 30 天	
		var exp	= new Date();		//new Date("December 31, 9998");	
		exp.setTime(exp.getTime() + Days*24*60*60*1000);	
		document.cookie = name + "="+ escape (value) + ";expires=" +exp.toGMTString()+";path=/;"; 
}

// 获取COOKIE
$.getCookie = function(name) {
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)")); 
		if(arr != null) return unescape(arr[2]); return null; 
}

// 删除COOKIE
$.delCookie = function(name) {
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var cval=getCookie(name);
		if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}

// BASE64 编码
$.base64Encode = function(input){
	var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var output = "";
		var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
		var i = 0;
		while (i < input.length) {
				chr1 = input.charCodeAt(i++);
				chr2 = input.charCodeAt(i++);
				chr3 = input.charCodeAt(i++);
				enc1 = chr1 >> 2;
				enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
				enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
				enc4 = chr3 & 63;
				if (isNaN(chr2)) { enc3 = enc4 = 64; } 
				else if (isNaN(chr3)) { enc4 = 64; }
				output = output +
				keyStr.charAt(enc1) + keyStr.charAt(enc2) +
				keyStr.charAt(enc3) + keyStr.charAt(enc4);
		}
		return output;
}

// BASE64 解码
$.base64Decode = function(input) {
	var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var output = "";
		var chr1, chr2, chr3;
		var enc1, enc2, enc3, enc4;
		var i = 0;
		input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
		while (i < input.length) {
				enc1 = keyStr.indexOf(input.charAt(i++));
				enc2 = keyStr.indexOf(input.charAt(i++));
				enc3 = keyStr.indexOf(input.charAt(i++));
				enc4 = keyStr.indexOf(input.charAt(i++));
				chr1 = (enc1 << 2) | (enc2 >> 4);
				chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
				chr3 = ((enc3 & 3) << 6) | enc4;
				output = output + String.fromCharCode(chr1);
				if (enc3 != 64) { output = output + String.fromCharCode(chr2); }
				if (enc4 != 64) { output = output + String.fromCharCode(chr3); }
		}
		return output;
}

// 格式化价格
$.formatMoney = function(number, places, symbol, thousand, decimal) {
	number = number || 0;
	places = !isNaN(places = Math.abs(places)) ? places : 2;
	symbol = symbol !== undefined ? symbol : "$";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var negative = number < 0 ? "-" : "",
			i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
			j = (j = i.length) > 3 ? j % 3 : 0;
	return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
}


/*==================AJAX 载入==================*/

// 获取商品信息
$.fn.loadGoods = function(t, opts, callback) {
	var el = this;
	var url = '/?module=default&controller=goods&action=get_goods_lists';
	$.getJSON(url, {t: t, opts: opts}, function(json){
		if (callback) {
			callback(json, el);
		} else {
			var html = '';
			$(json).each(function(i){
				var cur = json[i];
				html+='<dl class="gd-item-'+(i+1)+'">'
					+'<dt class="gd-rank">'+(i+1)+'</dt>'
					+'<dd class="gd-thumb"><a href="'+cur.click_url+'" target="_blank"><img src="'+cur.thumb+'" /></a></dd>'
					+'<dd class="gd-title"><a href="'+cur.click_url+'" target="_blank">'+cur.title+'</a></dd>'
					+'<dd class="gd-price">&yen;'+cur.price+'</dd>'
					+'</dl>';
				/*
				html+='<li><a href="'+cur.click_url+'" class="thumb" target="_blank">'
					+'<img src="'+cur.thumb+'" /></a>'
					+'<a href="'+cur.click_url+'" class="title" target="_blank">'+cur.title+'</a>'
					+'<span class="price">&yen;'+cur.price+'</span></li>';*/
			});
			if (html) {
				$(el).html('<div class="goods-box">'+html+'</div>');
			} else {
				$(el).html('<p class="notfound">没有找到相关信息</p>');
			}
		}
	});
}

// 载入收货地址
$.fn.loadAddr = function() {
	$.ajaxSetup({cache:false});
	$(this).load('/?module=default&controller=cart&action=load_delivery', function(){
		$('.js-shipping-box').loadShipping();
	});
}

// 载入物流方式
$.fn.loadShipping = function() {
	var el = $(this);
	el.html('<p class="loading">加载中...</p>');
	$.post('/?module=default&controller=cart&action=load_shipping', $('.my-cart').serialize(), function(result){
		el.html(result);
	});
}

// 载入区域联动菜单
$.fn.loadRegionMenu = function() {
	var el = this;
	$('select', el).remove();
	seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
		dmenu.init(el, {
			rootId: 1,
			script: '/misc.php?act=area',
			htmlTpl: '<select class="form-control input-sm" style="width:auto; margin-right:6px"></select>',
			firstText: '请选择所在地',
			defaultText: '请选择',
			selected: $('input[name=area_id]').val(),
			callback: function(dom, data) { 
				var location = $('select>option:selected', el).text();
				$('input[name=area_id]').val(data.id > 0 ? data.id : 0); 
				$('input[name=area_text]').val(location);
				$('input[name=zipcode]').val(data.zipcode > 0 ? data.zipcode : '');
			}
		});
	});
}

// 载入SKU信息
$.fn.loadSkuInfo = function() {
	var el = this;
	var v = $(el).val();
	var t = $(':selected', el).text();

	var arr = t.split(',');
	$('a', '.sku-list').removeClass('active');
	$(arr).each(function(idx, item) {
		var p = item.replace('[','').replace(']','');
		$('a[data-param="'+p+'"]').addClass('active');
	});

	$.getJSON('/?module=default&controller=goods&action=getSkuInfo', {sku_id:v}, function(json){
		$('.js-sp').html('&yen; '+$.formatMoney(json.selling_price,2,''));
		$('.js-mp').html('&yen; '+$.formatMoney(json.market_price,2,''));
		$('.js-pp').html('&yen; '+$.formatMoney(json.promotion_price,2,''));
		if (json.thumb) {
			$('.preview').prop('src', json.thumb);
			$('.zoomWrapperImage>img').prop('src', json.thumb);
		}

		if (json.quantity <= 0) {
			//$('.js-stock').html('缺货');
			$('.btn-addcart').addClass('btn-disabled');
		} else if (json.quantity <= json.quantity_warning) {
			//$('.js-stock').html('库存紧张');
			$('.btn-addcart').removeClass('btn-disabled');
		} else {
			//$('.js-stock').empty();
			$('.btn-addcart').removeClass('btn-disabled');
		}

		$('.js-stock').text(json.quantity);
		var $qty = $('[name=purchase_quantity]');
		if ($qty.val()>json.quantity) {
			$qty.val(json.quantity);
		} else if ($qty.val() <= 0) {
			$qty.val(1);
		}
		$('[name=purchase_quantity]').data('max', json.quantity);
	});
}

//加载单面
$.fn.loadContent = function(code) {
	$(this).load('/?module=default&controller=page&action=get_content', {code: code});
}

//加载快递信息
$.fn.loadExpress = function(t, code) {
	var el = this;
	$.post('/?controller=callback&action=express&t='+t, {code:code}, function(json){
		if (json.message == 'success') {
			var html = '';
			$(json.result.router).each(function(i){
				html += '<li>'
				html += '<span class="ex-time">'+this.time+'</span>';
				html += '<span class="ex-addr">'+this.address+'</span>';
				html += '<span class="ex-status">'+this.statue_message+'</span>';
				html += '</li>'
			});

			$(el).html(html);
		}
	}, 'json');
}