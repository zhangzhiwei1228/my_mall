define(function(require, exports, module) {

	var validator = function (form, opts){
	
		var self = this;
		self.defaults = {
			form: '',
			enabled: 1,
			showContainer: false,
			focusClass: 'has-info',
			errorClass: 'has-error',
			successClass: 'has-success',
			onkeyup: false,
			onchange: true,
			onblur: true,
			onsubmit: true,
			trigger: false,
			skipErr: true
		};

		self.opts = $.extend(self.defaults, opts);
		self.opts.form = form;

		//绑定事件
		self.bind = function() {
			var onfocus = function(DOM, rule) {
				var focusText = rule ? rule['focusText'] : $(DOM).attr('focusText');
				if (focusText) { 
					self.tip('focus', $(DOM), focusText);
				}
			};

			if (self.opts.showContainer) {
				$(self.opts.showContainer).hide();
			}

			for(var item in self.opts.rules) {
				var el = item;
				var firstText = item.substr(0,1);
				if (firstText != '[' && firstText != '.' && firstText != '#') {
					var el = '[name='+item+']';
				}

				$(el, form).data('valid', self.opts.rules[item]);
				if (self.opts.onchange) {
					$(el, form).bind('change', function() { self.valid(this, $(this).data('valid')) });
				}
				if (self.opts.onkeyup) {
					$(el, form).bind('keyup', function() { self.valid(this, $(this).data('valid')) });
				}
				if (self.opts.onblur) {
					$(el, form).bind('blur', function() { self.valid(this, $(this).data('valid')) });
					$(el, form).bind('focus', function() { onfocus($(this), $(this).data('valid')) });
				}
			}
			
			$(form).submit( function () {
				return self.run(form);
			});
		};

		//开始验证
		self.run = function() {
			var form = self.opts.form;
			if (!self.opts.onsubmit || !self.opts.enabled) return true;
			var flag = true; 
			var isFocus = false;
			for(var item in self.opts.rules) {
				var el = item;
				var firstText = item.substr(0,1);
				if (firstText != '[' && firstText != '.' && firstText != '#') {
					var el = '[name='+item+']';
				}
				if (self.valid($(el, form), self.opts.rules[item]) == false) {
					flag = false;
					if (!isFocus) {
						$(el, form).focus();
						isFocus = true;
					}
					if (!self.opts.skipErr) {
						return false;
					}
				}
			}
			if (self.opts.submitHandler) {
				flag = self.opts.submitHandler(flag, self.opts);
			}
			return flag;
		};

		self.tip = function (type, obj, msg) {
			var name = $(obj).attr('name') ? $(obj).attr('name') : $(obj).attr('id');
			try { 
				v = name.split('['); 
				for(i = 0; i < v.length; i++) { 
					name = name.replace('[', '').replace(']', ''); 
				}	
			} catch(e) {}
			
			switch (type) {
				/*case 'tip':
					var curClass = self.opts.tipClass; 
					var curInputClass = self.opts.inputTipClass;
					break;*/
				case 'focus': 
					var curClass = self.opts.focusClass; 
					var curInputClass = self.opts.focusClass;
					break;
				case 'error': 
					var curClass = self.opts.errorClass; 
					var curInputClass = self.opts.errorClass;
					break;
				case 'success': 
					var curClass = self.opts.successClass; 
					var curInputClass = self.opts.successClass;
					break;
			}
			
			var html = msg ? '<p id="_'+name+'_msg" class="help-block '+curClass+'"><i class="addon"></i>'+(msg ? msg : '&nbsp;')+'</p>' : '';
			$(obj).removeClass(self.opts.focusClass);
			$(obj).removeClass(self.opts.successClass);
			$(obj).removeClass(self.opts.errorClass);
			//$(obj).removeClass(self.opts.inputTipClass);
			$(obj).addClass(curInputClass);

			$('#_'+name+'_msg').remove();
			if (self.opts.showContainer) {
				if (!self.opts.skipErr) {
					$(self.opts.showContainer).empty();
				}
				$(self.opts.showContainer).show().append(html);
			} else {
				if ($(obj).parents('.input-group').length > 0) {
					$(obj).parents('.input-group').parent().append(html);
				} else {
					$(obj).parent().append(html);
				}
			}
		};

		self.valid = function(DOM, rule) {
			if ($(DOM).length <= 0) {
				return true;
			}

			var val = $(DOM).val();
			var func = rule ? rule['func'] : eval($(DOM).attr('func'));
			var validates = rule ? rule['valid'] : $(DOM).attr('valid');
			var errorTexts = rule ? rule['errorText'] : $(DOM).attr('errorText');
			var successText = rule ? rule['successText'] : $(DOM).attr('successText');
			
			var tip = function (type, obj, msg) {
				return self.tip(type, obj, msg);
			};

			if (validates) validates = validates.split('|'); else return true;
			if (errorTexts) errorTexts = errorTexts.split('|'); else return true;		
			
			for (j=0; j<validates.length; j++) {
				var validate = validates[j];
				var errorText = errorTexts[j];
				if (errorTexts.length <= 1) {
					errorText = errorTexts[0];
				}
				if (!validate) { continue; }
				switch(validate) {
					case 'required':
						if (DOM.length > 1) { //检测是否选项框
							var fg = 0;
							$(DOM).each(function(i) {
								if ($(this).prop('checked')) {
									fg = 1;
									return;
								}
								lastDom = this;
							})
							if (!fg) {
								tip('error', $(lastDom), errorText);
								return false;
							}
							return true;
						} else {
							if (val == '' || val == null) {
								tip('error', $(DOM), errorText);
								return false;
							}
						}
						break;
					case 'string':
						var patrn=/^[A-Za-z]+$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'password':
						var patrn=/^(.+){6,16}$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'strlen':
						var length = val.length;
						var arr = val.match(/[\u4e00-\u9fa5]/ig); //中文两字节
						if (arr != null) length += arr.length;

						_min = rule ? rule['minlen'] : $(this).attr('minlen');
						_max = rule ? rule['maxlen'] : $(this).attr('maxlen');
						if (length < parseInt(_min) || length > parseInt(_max)) {
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'range':
						_min = rule ? rule['min'] : $(DOM).attr('min');
						_max = rule ? rule['max'] : $(DOM).attr('max');
						
						var patrn=/^\d+(\.\d+)?$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						
						if (parseInt(val) < parseInt(_min) || parseInt(val) > parseInt(_max)) {
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'notZero':
						if (parseFloat(val) == 0) {
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'equal':
						var compare = rule ? rule['compare'] : $(DOM).attr('compare');
						if ($(compare).val() != val && compare != val) {
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'notEqual':
						var compare = rule ? rule['compare'] : $(DOM).attr('compare');
						if ($(compare).val() == val || compare == val) {
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'email':
						var patrn=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/; 
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'numeric':
						var patrn=/^\d+(\.\d+)?$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'telphone':
						var patrn=/^(0\d{2,3}\-?)?\d{6,8}$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'mobile':
						var patrn=/^(1|01)\d{10}$/;
						if( !patrn.test(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'zip':
						var patrn=/^[1-9]\d{5}$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'chinese':
						var patrn=/[\u4e00-\u9fa5]/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'currency':
						var patrn=/^\d+(?:\.\d{0,2})?$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					case 'idCard':
						var patrn=/^(\d{6})(18|19|20)?(\d{2})([01]\d)([0123]\d)(\d{3})(\d|X)?$/;
						if( !patrn.exec(val) && val != ''){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
					default:
						if(!validate.exec(val) ){
							tip('error', $(DOM), errorText);
							return false;
						}
						break;
				}
				tip('success', $(DOM), successText);
			}
			
			//回调方法
			if (func) { 
				msg = func($(DOM));
				if (msg != true) {
					tip('error', $(DOM), msg);
					return false;
				}
			}

			return true;
		};

		self.bind(form);
	};

	module.exports = function (form, opts) {
		return new validator(form, opts);
	}
});