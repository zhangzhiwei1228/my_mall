<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '图片浏览器';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>
<style type="text/css">
	.uploadify {
		display: inline;
	}
	.opts a { color: #333; }
</style>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" action="<?=$this->url('action=search')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入关键词查询" />
				<?php if ($this->_request->q) { ?>
				<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
				<?php } ?>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" data-plugin="chk-group" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<input type="checkbox" role="chk-all" />
			全选
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<input type="file" name="file_upload" id="file_upload" class="hide" />
		</div>
		<div class="sui-img-explorer clearfix">
			<?php if (count($this->datalist)) { foreach($this->datalist as $row) { ?>
			<dl>
				<dd style="margin-bottom:8px">
					<a class="thumb" href="<?=$this->url('action=detail&id='.$row['id'])?>"> 
						<img src="<?=$this->img($row['src'], '160x160')?>"> </a>
				</dd> 
				<dd><a title="<?=$row['name']?>"><?=$this->highlight($this->cutstr($row['name'], 16), $this->_request->q)?></a></dd>
				<dd>复制: 
					<a href="javascript:;" class="copy-img-html" data-url="<?=$this->baseUrl($row['src'])?>">代码</a> | 
					<a href="javascript:;" class="copy-img-url" data-url="<?=$this->baseUrl($row['src'])?>">链接</a> 
				</dd>
				<dd class="opts">
					<input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" />
					<a href="<?=$this->url('action=delete&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除吗？')">删除</a> | 
					<a href="javascript:;" onclick="$.replaceImgSrc(<?=$row['id']?>)">替换</a> | 
					<a href="<?=$this->url('action=detail&id='.$row['id'])?>">详情</a> 
				</dd>
			</dl>
			<?php } } else { ?>
			<p align="center" style="padding:10px;">没有找到相关图片</p>
			<?php } ?>
		</div>
	</form>

	<!-- Modal -->
	<div class="modal fade" id="replace-img-src" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:400px">
			<form class="modal-content" method="post" enctype="multipart/form-data" action="<?=$this->url('./replace')?>">
				<input type="hidden" name="id">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">替换图片</h4>
				</div>
				<div class="modal-body">
					请选择一张要替换的新图片
					<input type="file" name="src" placeholder="请选择要替换的新图片" class="form-control">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="/assets/js/zclip/jquery.zclip.min.js"></script>
<script src="/assets/js/uploadify/jquery.uploadify.min.js?ver=<?=rand(0,9999)?>"></script>
<script>
$('#file_upload').uploadify({
	swf : '/assets/js/uploadify/uploadify.swf',
	uploader : '/misc.php?act=upload&token=<?=$this->admin->getToken()?>',
	fileObjName : 'imgFile',
	fileTypeDesc : '图片文件',
	fileTypeExts : '*.gif; *.jpg; *.png; *.bmp',
	buttonText : '<i class="fa fa-plus-circle"></i> 从本地上传文件',
	width : 130,
	height : 28,
	buttonClass : 'btn btn-default btn-sm',
	buttonCursor : 'hand',
	fileSizeLimit : <?=getUploadFileSize()/1024?>,
	onQueueComplete: function(response){ 
		window.location.href = '<?=$this->url('&')?>';
	},
	onUploadSuccess: function(file, data, response) {
		var json = jQuery.parseJSON(data);
		if (json.error) {
			alert('文件“'+file.name+"” 上传失败。\r\n"+json.message);
		}
	}
});

$.replaceImgSrc = function(id) {
	$('#replace-img-src [name=id]').val(id);
	$('#replace-img-src').modal();
}

$(function(){
	$(".copy-img-html").zclip({
		path:'/assets/js/zclip/ZeroClipboard.swf', //记得把ZeroClipboard.swf引入到项目中 
		copy:function(){
			alert('复制成功，请使用ctrl+v进行粘贴');
			return '<img src="'+$(this).data('url')+'" />';
		}
	});
	$(".copy-img-url").zclip({
		path:'/assets/js/zclip/ZeroClipboard.swf', //记得把ZeroClipboard.swf引入到项目中 
		copy:function(){
			alert('复制成功，请使用ctrl+v进行粘贴');
			return $(this).data('url');
		}
	});
});
</script>