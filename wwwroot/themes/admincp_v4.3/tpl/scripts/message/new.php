<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('发送站内信');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">收件人：</label>
			<div class="col-sm-8"><input type="text" name="recipient_uid" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">内容：</label>
			<div class="col-sm-8"><div class="input-group">
				<textarea name="content" class="form-control" rows="20" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['content'])?></textarea></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary">立即发送</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>

<script>
seajs.use(['/assets/js/bootstrap-typeahead.js',
	'/assets/js/tagsinput/bootstrap-tagsinput.js',
	'/assets/js/tagsinput/bootstrap-tagsinput.css'], function(){
	
	var elt1 = $('[name=recipient_uid]')
	elt1.tagsinput({
		itemValue: 'id',
		itemText: 'name',
		typeaheadjs: {
			displayKey: 'name',
			source: function(query, process) {
				$.getJSON('<?=$this->url('action=getUsers')?>', {q:query}, function (data) {
					process(data);
				});
			}
		}
	});
	
	<?php if ($this->recipient->exists()) { ?>
	elt1.tagsinput('add', <?=json_encode(array('id'=>$this->recipient->id,'name'=>$this->recipient->username))?>);
	<?php } ?>
});
</script>