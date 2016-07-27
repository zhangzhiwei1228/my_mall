<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<!-- Modal -->

<div class="modal-dialog" style="width:800px">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title" id="myModalLabel">请选择图片...</h4>
			<div class="panel-heading" style="position: absolute; top:8px; left:180px">
				<ul class="nav nav-pills">
					<li class="active"><a href="#cur" onclick="$.showCur();" data-toggle="tab">当前板块</a></li>
					<li><a href="#all" onclick="$.showAll();" data-toggle="tab">全部图片</a></li>
				</ul>
			</div>
		</div>
		<div class="modal-body">
			<div class="sui-toolbar">
				<div class="pull-right" style="height:30px; overflow:hidden;">
					<input id="file-upload" type="file" name="imgFile" />
				</div>
				<form id="search-form" method="get" class="form-inline">
					<input type="hidden" name="search" value="1" />
					<input type="hidden" name="page" value="1" />
					<div class="form-group">
						<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入图片文件名" />
						<?php if ($this->_request->q) { ?>
						<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
						<?php } ?>
					</div>
					<!-- 
					<div class="form-group">
						<div class="input-group input-sm">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
							<input type="text" name="d" class="form-control" data-plugin="date-picker">
						</div>
					</div> -->
					<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
				</form>
			</div>
			
			<div class="JS_AjaxBody">
				<?php if ($this->_request->aj) { $this->fragmentStart(); } ?>
				<div class="sui-img-explorer pop clearfix">
					<?php if (count($this->datalist)) { ?>
					<?php foreach($this->datalist as $row) { ?>
					<dl data-id="<?=$row['id']?>" data-src="<?=$row['src']?>" onclick="$.selected(this)">
						<dd><a class="thumb" title="<?=$row['name']?>"><img src="<?=$this->img($row['src'], '160x160')?>" alt="<?=$row['name']?>" /></a> 
							<i class="fa fa-check-circle-o"></i>
						</dd>
					</dl>
					<?php } ?>
				</div>
				<div class="text-center">
					<div class="pagination pagination-sm">
						<?=$this->paginator($this->datalist)->getAjaxBar('$.gotopage')?>
					</div>
				</div>
				<?php } else { echo '<p style="padding:50px;">暂无图片</p>'; } ?>
				<?php if ($this->_request->aj) { $this->fragmentEnd(); } ?>
			</div>
		</div>
		<div class="modal-footer">
			<span style="line-height:26px; float:left">
				当前已选择 <strong class="JS_Slen">0</strong> 张图片, 
				本次最多可选 <strong>
				<?=(int)$this->_request->limit?>
				</strong> 张图片</span>
			<button type="submit" class="btn btn-ok btn-primary" data-dismiss="modal">确定</button>
		</div>
	</div>
	<!-- /.modal-content --> 
</div>
<!-- /.modal-dialog --> 

<script src="/assets/js/uploadify/jquery.uploadify.min.js"></script> 
<script>
var images = new Array();
var limit = <?=(int)$this->_request->limit?>;
var ipt = '<?=$this->_request->ipt?>';
var dom = $('[data-ipt="'+ipt+'"]>.sui-img-selector-box');
var showCurScript = '/?module=admincp&controller=image&action=selector&aj=1&ref=<?=$this->_request->ref?>';
var showAllScript = '/?module=admincp&controller=image&action=selector&aj=1';
var script = showCurScript;

$.selected = function(DOM){
	var id = $(DOM).data('id');
	var src = $(DOM).data('src');
	var tmp = new Array();
	for(x in images) {
		if (images[x]) {
			tmp.push(images[x]);
		}
	} images = tmp;
	
	for(x in images) {
		if (images[x].id == id)	{
			delete images[x];
			$(DOM).removeClass('selected');
			return false;
		}
	}
	
	$(DOM).addClass('selected');
	images.push({'id': id, 'src': src});
	if (images.length > limit) {
		images.shift();
	}
	$.refreshStatus();
}

$.refreshStatus = function() {
	$('.JS_AjaxBody dl').removeClass('selected');
	for(x in images) {
		$('.JS_AjaxBody dl[data-id='+images[x].id+']').addClass('selected');
	}
	$('.JS_Slen').text(images.length);
}

$.gotopage = function(page) { 
	$('.JS_AjaxBody').load(script+'&page='+page, function(){
		$.refreshStatus();
	});
}

$.query = function() {
	var q = $('[name=q]').val();
	script = script+'&q='+q;
	$.gotopage(1);
}

$.showAll = function() {
	script = showAllScript;
	$.gotopage(1);
}

$.showCur = function() {
	script = showCurScript;
	$.gotopage(1);
}

$(function() {
	$('#search-form').submit(function(){
		$.query();
		return false;
	});
	$('#file-upload').uploadify({
		swf : '/assets/js/uploadify/uploadify.swf',
		uploader : '/misc.php?act=upload&token=<?=$this->admin->getToken()?>&ref=<?=$this->_request->ref?>',
		fileObjName : 'imgFile',
		fileTypeDesc : '图片文件',
		fileTypeExts : '*.gif; *.jpg; *.jpeg; *.png; *.bmp',
		buttonText : '<i class="fa fa-plus-circle"></i> 从本地上传文件',
		width : '120',
		buttonClass : 'btn btn-default btn-sm',
		buttonCursor : 'hand',
		fileSizeLimit : <?=getUploadFileSize()/1024?>,
		onQueueComplete: function(response){
			$('#img-selector .nav-pills li').removeClass('active');
			$('#img-selector .nav-pills li:first-child').addClass('active');
			$.showCur();
		},
		onUploadSuccess: function(file, data, response) {
			console.log(data);
			var json = jQuery.parseJSON(data);
			if (json.error) {
				alert('文件“'+file.name+"” 上传失败。\r\n"+json.message);
			}
		}
	});

	$('.JS_ImgItem', dom).each(function() {
		var id = $('img', this).data('id');
		var src = $('img', this).attr('src');
		images.push({'id': id, 'src': src});
		$.refreshStatus();
	});
});
</script> 
