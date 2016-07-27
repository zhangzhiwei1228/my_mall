define(function(require, exports, module) {
	require('./mtree.css');
	module.exports = {
		init: function(DOM, opts) {
			var checked = new Array();
			var opts = $.extend({
				script: '/plugins/region/data.php', //请求脚本地址
				input: '',
				disableIds: '',
				callback: function(ids, data) {
					$(opts.input).val(ids);
				}
			}, opts);
			
			$.ajaxSetup({cache: true});
			$.getJSON(opts.script, function(data) {
				var els = render(data); 
				$(DOM).html('<ul class="sc-mtree">'+els+'</ul><div class="clearfix"></div>');

				$(document).bind('click', function(e) {   
					var els = $(e.target);
					if (els.parents('.sc-mtree-item').length == 0) {
						$('.sc-mtree-item').removeClass('active');
					}
				});

				$('.sc-mtree-switch', $(DOM)).click(function() {
					if ($(this).parents('.sc-mtree-item').hasClass('active')) {
						$(this).parents('.sc-mtree-item').removeClass('active');
					} else {
						$('.sc-mtree-item', $(DOM)).removeClass('active');
						$(this).parents('.sc-mtree-item').addClass('active');
					}
				});

				$('input[type=checkbox]', $(DOM)).on('change', function(){
					checkedIpt(this);
					//滤空
					var arr = new Array();
					for(id in checked) {
						if (checked[id]) { arr.push(checked[id]); }
					}
					opts.callback(arr, data);
				});

				for(idx in opts.disableIds) {
					$('#sc-mtree-ipt-'+opts.disableIds[idx]).attr('checked', true).attr('disabled', true);
					$('#sc-mtree-'+opts.disableIds[idx]+' .sc-mtree-childs input').attr('checked', true).attr('disabled', true);
					$('input', $('#sc-mtree-ipt-'+opts.disableIds[idx]).parents('.sc-mtree-item').children('.sc-mtree-item-box')).attr('disabled', true);
				};

				//默认值
				var inputValues = $(opts.input).val() ? $(opts.input).val().split(',') : 0;
				for(idx in inputValues) {
					var id = inputValues[idx];
					$('#sc-mtree-ipt-'+id).attr('checked', true);
					$('#sc-mtree-ipt-'+id).trigger("change");
				}

				$('input:disabled', $(DOM)).each(function() {
					var id = $(this).data('extend').id;
					var total1 = $('#sc-mtree-'+id+' .sc-mtree-childs input').length;
					var total2 = $('#sc-mtree-'+id+' .sc-mtree-childs input:checked').length;

					if (total1 == total2) {
						$(this).attr('checked', true);
					}
				});
			});
			
			//Checkbox 单个选中
			$.fn.checkItem = function (checkAllIpt, groupIpt, controlBtn) {
				var flag = $(groupIpt+':checked').length ? true : false;
				var checkAllFlag = $(groupIpt+':checked').length == $(groupIpt).length ? true : false;
				$(checkAllIpt).attr('checked', checkAllFlag);
			};

			function checkedIpt(obj) {
				var flag = $(obj).is(":checked") ? true : false;
				var extend = $(obj).data('extend');
				var id = $.inArray(extend.id, checked);
				var pid = $.inArray(extend.parent_id, checked);
				if (!$(obj).parents('.sc-mtree-childs').length) { //根级
					$('#sc-mtree-'+extend.id+' > .sc-mtree-childs input:not(:disabled)').prop('checked', flag);

					var count = $('#sc-mtree-'+extend.id+' > .sc-mtree-childs input:not(:disabled):checked').length;			

					if (count > 0) $('#sc-mtree-'+extend.id+' .sc-mtree-checked-count').text('('+count+')');
					else $('#sc-mtree-'+extend.id+' .sc-mtree-checked-count').empty();
					
					$('#sc-mtree-'+extend.id+' > .sc-mtree-childs input:not(:disabled)').each(function() {
						var id = $(this).data('extend').id;
						var idx = $.inArray(id, checked);
						delete checked[idx];
					});

					if ($(obj).is(":checked")) checked.push(extend.id);
					else delete checked[id];
				} else { //子级
					var groupIpt = '#sc-mtree-'+extend.parent_id+' > .sc-mtree-childs input:not(:disabled)';
					var flag = $(groupIpt+':checked').length ? true : false;
					var checkAllFlag = $(groupIpt+':checked').length == $(groupIpt).length ? true : false;
					$('#sc-mtree-'+extend.parent_id+' > .sc-mtree-item-box input').prop('checked', checkAllFlag);


					var count = $('#sc-mtree-'+extend.parent_id+' > .sc-mtree-childs input:not(:disabled):checked').length;
					var total = $('#sc-mtree-'+extend.parent_id+' > .sc-mtree-childs input').length;
					var sChecked = new Array();

					if (count > 0) $('#sc-mtree-'+extend.parent_id+' .sc-mtree-checked-count').text('('+count+')');
					else $('#sc-mtree-'+extend.parent_id+' .sc-mtree-checked-count').empty();				

					//计算选中
					delete checked[pid];
					$('#sc-mtree-'+extend.parent_id+' > .sc-mtree-childs input:not(:disabled)').each(function() {
						var id = $(this).data('extend').id;
						var idx = $.inArray(id, checked);
						if ($(this).is(":checked")) sChecked.push(id);
						delete checked[idx]; //删除选中子类
					});

					if (count == total) { //全选
						checked.push(extend.parent_id);
					} else {
						for(idx in sChecked) {
							checked.push(sChecked[idx]);
						}
					}
				}
			}

			function render(data) {
				var els = '';
				$.each(data, function(i, item) {
					var extend = new Object();
					for(key in item) {
						if (key == 'childnotes') continue;
						extend[key] = item[key];
					}
					extend = JSON.stringify(extend);
					els += '<li id="sc-mtree-'+item.id+'" class="sc-mtree-item">';
					els += '<p class="sc-mtree-item-box"><label><input id="sc-mtree-ipt-'+item.id+'" type="checkbox" data-extend=\''+extend+'\'>'+item.name+'</label>';
					if (item.childnotes) {
						els += '<span class="sc-mtree-checked-count"></span>';
						els += '<span class="sc-mtree-switch"></span></p>';
						els += '<ul class="sc-mtree-childs">'+render(item.childnotes)+'</ul>';
					} else {
						els += '</p>';
					}
					els += '</li>';
				});
				return els;
			}
		}
	};
})