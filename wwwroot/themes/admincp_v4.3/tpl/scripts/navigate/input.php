<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle(($this->_request->getActionName() == 'add' ? '添加' : '修改'). '菜单');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<input type="hidden" name="type" value="<?=isset($this->data['type'])?$this->data['type']:$this->_request->t?>">
		<div class="form-group">
			<label class="control-label col-sm-2">菜单名称</label>
			<div class="col-sm-9">
				<input name="name" value="<?=$this->data['name']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">所属菜单</label>
			<div class="col-sm-9">
				<select name="parent_id" class="form-control">
					<option value="0">主菜单</option>
					<?php 
						$pid = isset($this->data['parent_id']) ? $this->data['parent_id'] : $this->_request->pid;
						foreach ($this->categories as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($pid == $row['id']) echo 'selected';?>> <?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?> </option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">重定向</label>
			<div class="col-sm-9">
				<input name="redirect" value="<?=$this->data['redirect']?>" class="form-control">
				<p class="help-block">当设置重定向时，系统将根据此地址进行跳转</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">ICON</label>
			<div class="col-sm-9">
				<input name="icon" value="<?=$this->data['icon']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">导航描述</label>
			<div class="col-sm-9">
				<textarea name="description" class="form-control"><?=$this->data['description']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">新窗口打开</label>
			<div class="col-sm-9">
				<div class="radio">
					<?php $isBlank = isset($this->data['is_blank']) ? $this->data['is_blank'] : 1?>
					<label><input type="radio" name="is_blank" value="1" <?php if ($isBlank == 1) echo 'checked'?>/> 是</label>
					<label><input type="radio" name="is_blank" value="0" <?php if ($isBlank == 0) echo 'checked'?>/> 否</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否启用</label>
			<div class="col-sm-9">
				<div class="radio">
					<?php $isEnabled = isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 1?>
					<label><input type="radio" name="is_enabled" value="1" <?php if ($isEnabled == 1) echo 'checked'?>/> 启用</label>
					<label><input type="radio" name="is_enabled" value="0" <?php if ($isEnabled == 0) echo 'checked'?>/> 禁用</label>
				</div>
				<p class="help-block">您可以通过禁用选项临时关闭此菜单</p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>