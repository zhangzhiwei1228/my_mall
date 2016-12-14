<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '广告位';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">广告位名称:</label>
			<div class="col-sm-6">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">广告代码:</label>
			<div class="col-sm-6">
				<input type="text" name="code" value="<?=$this->data['code']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">广告尺寸:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" name="width" value="<?=$this->data['width'] ? $this->data['width'] : ''?>" class="form-control" placeholder="宽度" />
					<span class="input-group-addon">x</span>
					<input type="text" name="height" value="<?=$this->data['height'] ? $this->data['height'] : ''?>" class="form-control" placeholder="高度" />
					<span class="input-group-addon">像素</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">同时显示:</label>
			<div class="col-sm-3">
				<div class="input-group">
					<input type="text" name="limit" value="<?=$this->data['limit'] ? $this->data['limit'] : 1?>" class="form-control" />
					<span class="input-group-addon">组</span>
				</div>
				<p class="help-block">如：焦点图片有5张轮播，则设置5组</p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>