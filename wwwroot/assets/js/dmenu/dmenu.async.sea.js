define(function(require, exports, module) {
	module.exports = {
		init: function(DOM, opts) {
			var opts = $.extend({
				htmlTpl: '<select></select>', //控件模板
				selected: 0, //选中项ID
				disableId: 0, //禁止ID加载
				defaultText: false, //默认文本
				firstText: '请选择...', //首个加载控件文本
				script: '', //请求脚本地址
				getPathScript: '',
				mapping: new Array(), //控件映射 [['province', '请选择省份...'], ['city', '请选择城市...']]
				callback: function(data) {}, //回调方法
				rootId: 0 //载入的根ID
			}, opts);
			var data = new Object();
			var path = new Array();

			//初始化
			if (opts.selected) {
				$.ajaxSetup({async:false});
				$.getJSON(opts.getPathScript, {cid:opts.selected}, function(data){
					var el = $(this);
					$.each(data, function(i, id){
						$('[value='+id+']', el).attr('selected', true);
						el = loadNote(id, el);
					});
				});
			} else {
				loadNote(opts.rootId, $(this));
			}
			
			function loadNote(id, obj) {
				var level = ($(obj).data('level') ? $(obj).data('level') : 0) + 1;			
				$('.JS_DmenuSelect').each(function() {
					var lv = $(this).data('level');
					if (lv >= level) {
						$(this).remove();
					}
				});

				var mp = opts.mapping[level-1];
				var el = $(opts.htmlTpl)
					.data('level', level)
					.addClass('JS_DmenuSelect')
					.change(function() {
						if (!$(this).val()) return false;
						loadNote($(this).val(), $(this));
					});

				$.getJSON(opts.script, {pid:id}, function(data){
					if (level == 1) {
						el.appendTo(DOM);
					} else {
						el.insertAfter(obj);
					}
					if (opts.firstText && level == 1) {
						el.empty();
						$(el).append('<option value="-1">'+opts.firstText+'</option>');
					} else if (opts.defaultText) {
						el.empty();
						$(el).append('<option value="-1">'+opts.defaultText+'</option>');
					}
					if (mp) {
						el.attr('name', mp[0]);
						if (mp[1]) {
							el.empty();
							el.append('<option value="-1">'+mp[1]+'</option>');
						}
					}

					var find = false;
					$.each(data, function(key, val) {
						if (opts.disableId != val.id) {
							if (val.parent_id == id) {
								$(el).append('<option value="'+val.id+'" data-extend=\''+JSON.stringify(val)+'\'>'+val.name+'</option>');
								find = true;
							}
						}
					});

					if (!find) $(el).remove();
					
					opts.callback($(':selected', obj).data('extend') ? $(':selected', obj).data('extend') : false);
				});
				return el;
			}
		}
	};
})