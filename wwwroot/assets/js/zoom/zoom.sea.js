define(function(require, exports, module) {
	require('../jquery-migrate-1.2.1.js');
	require('./jqzoom-master/js/jquery.jqzoom-core.pack.js');
	require('./jqzoom-master/css/jquery.jqzoom.css');

	module.exports.init = function(el, opts){
		$(el).jqzoom(opts);
	}
})