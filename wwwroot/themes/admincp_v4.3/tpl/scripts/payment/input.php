<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '支付方式';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">通道代码:</label>
			<div class="col-sm-7"><input type="text" name="code" value="<?=$this->data['code']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">通道名称:</label>
			<div class="col-sm-7"><input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">LOGO:</label>
			<div class="col-sm-7">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
					data-ipt="logo" data-ref="payment-logo">
					<div class="sui-img-value"><?=$this->data['logo']?$this->baseUrl($this->data['logo']):''?></div>
					<div class="sui-img-selector-box"></div>
					<div class="sui-img-selector-btns">
						<button type="button" class="btn" role="btn">选择图片</button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手续费:</label>
			<div class="col-sm-7"> 
				<div class="input-group">
					<span class="input-group-addon">&yen;</span>
					<input type="text" name="fee" value="<?=$this->data['fee']?>" class="form-control" />
					<span class="input-group-addon">元</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">通道介绍:</label>
			<div class="col-sm-7"><textarea name="description" class="form-control" rows="3"><?=$this->data['description']?></textarea></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">配置参数:</label>
			<div class="col-sm-7"><textarea name="setting" class="form-control" rows="6"><?=$this->data['setting']?></textarea></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否启用:</label>
			<div class="col-sm-7 radio">
				<label><input type="radio" name="is_enabled" value="1" <?php if ($this->data['is_enabled'] == 1) echo 'checked'?>/>启用</label>
				<label><input type="radio" name="is_enabled" value="0" <?php if ($this->data['is_enabled'] == 0) echo 'checked'?>/>禁用</label>
				<div class="help-block">您可以通过禁用选项临时关闭此接口</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-7 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>