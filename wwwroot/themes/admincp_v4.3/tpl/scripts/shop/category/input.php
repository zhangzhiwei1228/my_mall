<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '分类';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">分类名称:</label>
			<div class="col-sm-6">
				<input name="name" value="<?=$this->data['name']?>" class="form-control">
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="control-label col-sm-2">所属分类:</label>
			<div class="col-sm-6">
				<div class="JS_Dmenu form-inline">
					<input type="hidden" name="parent_id" value="<?=$this->data['parent_id']?>" />
				</div>
			</div>
		</div> -->
		<!-- <div class="form-group">
			<label class="control-label col-sm-2">绑定类型:</label>
			<div class="col-sm-6">
				<select name="type_id" class="form-control">
					<option value="0">请选择...</option>
					<?php foreach ($this->types as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($row['id'] == $this->data['type_id']) echo 'selected';?>> <?=$row['name']?> </option>
					<?php } ?>
				</select>
				<div class="help-block">绑定商品类型后，分布商品时选择此分类将会自动跟出对应的商品属性。</div>
			</div>
		</div> -->
		<div class="form-group">
			<label class="control-label col-sm-2">重定向:</label>
			<div class="col-sm-6">
				<input name="redirect" value="<?=$this->data['redirect']?>" class="form-control">
				<div class="help-block">可通过重定向设置访问外部模块</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否启用:</label>
			<div class="col-sm-6">
				<?php $isEnabled = isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 1?>
				<div class="radio">
					<label><input type="radio" name="is_enabled" value="1" <?php if ($isEnabled == 1) echo 'checked'?>/>启用</label>
					<label><input type="radio" name="is_enabled" value="0" <?php if ($isEnabled == 0) echo 'checked'?>/>禁用</label>
				</div>
				<div class="help-block">您可以通过禁用选项临时关闭此分类</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存</button>
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