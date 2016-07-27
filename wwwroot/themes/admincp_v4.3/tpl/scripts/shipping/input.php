<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '发货点';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">名称:</label>
			<div class="col-sm-4"><input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">Logo:</label>
			<div class="col-sm-4">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
					data-ipt="logo" data-ref="shipping-logo">
					<div class="sui-img-value"><?=$this->data['logo']?$this->baseUrl($this->data['logo']):''?></div>
					<div class="sui-img-selector-box"></div>
					<div class="sui-img-selector-btns">
						<button type="button" class="btn" role="btn">选择图片</button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">减免:</label>
			<div class="col-sm-4"><div class="input-group">
				<input type="text" name="discount" value="<?=$this->data['discount']?>" class="form-control" /> 
				<span class="input-group-addon">%</span></div>
				<div class="help-block">按百分比设置，如 8折 = 减免20%</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">简介:</label>
			<div class="col-sm-4"><textarea name="description" class="form-control" rows="4"><?=$this->data['description']?></textarea></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">状态:</label>
			<div class="col-sm-4">
				<div class="radio">
					<label><input type="radio" name="is_enabled" <?php if ($this->data['is_enabled'] == 1) echo 'checked'?> value="1" />启用</label>
					<label><input type="radio" name="is_enabled" <?php if ($this->data['is_enabled'] == 0) echo 'checked'?> value="0" />禁用</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
			<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>