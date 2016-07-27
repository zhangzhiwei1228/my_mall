<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '区域';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">区域名称:</label>
			<div class="col-sm-5">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">所属区域:</label>
			<div class="col-sm-9">
				<div class="JS_Dmenu form-inline">
					<input type="hidden" name="parent_id" value="<?=$this->data['parent_id']?>" />
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
	dmenu.init('.JS_Dmenu', {
		disableId: <?=(int)$this->data['id']?>,
		script: '<?=$this->url('action=getJson')?>',
		htmlTpl: '<select size="8" class="form-control" style="margin-right:6px"></select>',
		firstText: '主分类',
		selected: '<?=$this->data['parent_id'] ? $this->data['parent_id'] : $this->_request->pid?>',
		callback: function(el, data) { $('input[name=parent_id]').val(data.id > 0 ? data.id : 0); }
	});
});
</script>