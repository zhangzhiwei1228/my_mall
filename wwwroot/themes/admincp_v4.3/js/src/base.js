$('body').on('hidden', '.modal', function () {
	$(this).removeData('modal');
});

$.openNote = function(dom, id) {
	var pst = $(dom).parents('tr');
	var isOpen = pst.hasClass('opened') ? 1 : 0;
	if (!isOpen) {
		$(dom).html('<i class="fa fa-minus-square-o"></i>');
		pst.addClass('opened'); 
	} else { 
		$(dom).html('<i class="fa fa-plus-square-o"></i>'); 
		pst.removeClass('opened'); $('.cat_'+id).remove(); 
	}
	$.post(location.href, {cid:id, t:isOpen}, function(result) { if (!isOpen) { pst.after(result); } });
}

$.setCookie = function (name, value) {
	var Days = 30; //此 cookie 将被保存 30 天	
	var exp	= new Date();		//new Date("December 31, 9998");	
	exp.setTime(exp.getTime() + Days*24*60*60*1000);	
	document.cookie = name + "="+ escape (value) + ";expires=" +exp.toGMTString()+";path=/;"; 
}

$.getCookie = function(name) {
	var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)")); 
	if(arr != null) return unescape(arr[2]); return null; 
}

$.delCookie = function(name) {
	var exp = new Date();
	exp.setTime(exp.getTime() - 1);
	var cval=getCookie(name);
	if(cval!=null) document.cookie= name + "="+cval+";expires="+exp.toGMTString();
}


$.applyImage = function(file, ipt, DOM) {
	var tpl = '<input type="hidden" name="'+ipt+'" value="'+file+'" />'
		+ '<a><img src="'+file+'" role="btn" /></a>'
		+ '<a class="JS_DelImg img-remove" href="javascript:;"><i class="fa fa-remove"></i> <span>移除</span></a>';
	$(DOM).html(tpl);

	$('.JS_DelImg', DOM).on('click', function(){
		$(DOM).empty().html('<input type="hidden" name="'+ipt+'" value="" />');
		event.stopPropagation();
	});
}

var files = new Array();
$.applyImages = function(files, ipt, DOM) {
	$(DOM).empty();
	for(x in files) {
		var file = files[x];
		var tpl = '<div class="JS_ImgItem img-item" style="position:relative;">'
			+ '<div class="alt">'
			+ '<input type="hidden" name="'+ipt+'['+x+'][id]" value="'+file.id+'">'
			+ '<input type="hidden" name="'+ipt+'['+x+'][src]" value="'+file.src+'">'
			+ '<textarea name="'+ipt+'['+x+'][alt]" class="form-control" placeholder="请输入图片说明">'+(file.alt?file.alt:'')+'</textarea>'
			+ '<a href="javascript:;$(\'.alt\',\'.JS_ImgItem\').hide();" class="btn btn-primary btn-xs">确定</a>'
			+ '</div>'
			+ '<a class="thumb" title="'+(file.alt?file.alt:'')+'"><img src="'+file.src+'" data-id="'+file.id+'"></a>'
			+ '<div class="operate">'
			// + '<a href="javascript:;" class="JS_Forward">'
			// + '<i class="glyphicon glyphicon-step-backward"></i></a>&nbsp;'
			// + '<a href="javascript:;" class="JS_Backward">'
			// + '<i class="glyphicon glyphicon-step-forward"></i></a>&nbsp;'
			+ '<a href="javascript:;" class="JS_DelImg">'
			+ '<i class="fa fa-remove"></i> 移除</a>'
			+ '</div></div>';
		$(DOM).append(tpl);
	}

	$('.JS_ImgItem', DOM).on('click', function(){
		var el = $(this);
		$('.alt', DOM).hide();
		$('.alt', el).show();
		$(document).mouseup(function(e){
			var _con = el;   // 设置目标区域
			if(!_con.is(e.target) && _con.has(e.target).length === 0){ // Mark 1
				$('.alt', DOM).hide();
			}
		});
		event.stopPropagation();
	});

	$('.JS_DelImg', DOM).on('click', function(){
		$(this).parents('.JS_ImgItem').remove();
	});

	$('.JS_Forward', DOM).on('click', function(){
		var e = $(this).parents('.JS_ImgItem');
		e.prev('.JS_ImgItem').before(e);
	});
	$('.JS_Backward', DOM).on('click', function(){
		var e = $(this).parents('.JS_ImgItem');
		e.next('.JS_ImgItem').after(e);
	});
	seajs.use('/assets/js/dragsort/jquery.dragsort-0.5.2.min.js', function(dragsort){
		$(DOM).dragsort("destroy");
		$(DOM).dragsort({placeHolderTemplate: "<div class='img-item'></div>"});
	});
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