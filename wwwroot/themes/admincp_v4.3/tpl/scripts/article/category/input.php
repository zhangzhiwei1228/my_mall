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
			<label class="control-label col-sm-2"><span class="required">*</span>类目名称:</label>
			<div class="col-sm-9">
				<input name="name" value="<?=$this->data['name']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>所属类目:</label>
			<div class="col-sm-9">
				<select name="parent_id" class="form-control">
					<option value="0">主菜单</option>
					<?php 
					$pid = isset($this->data['pid']) ? $this->data['pid'] : $this->_request->pid;
					foreach ($this->categories as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($pid == $row['id']) echo 'selected';?>> <?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?> </option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>重定向:</label>
			<div class="col-sm-9">
				<input name="redirect" value="<?=$this->data['redirect']?>" class="form-control">
				<div class="help-block">
					可通过重定向设置访问外部模块
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">状态:</label>
			<div class="col-sm-9"> 
				<div class="radio">
				<?php $checked = isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 0 ?>
				<label>
					<input type="radio" name="is_enabled" value="1" <?=$checked==1 ? 'checked' : ''?> />
					启用</label>
				<label>
					<input type="radio" name="is_enabled" value="0" <?=$checked==0 ? 'checked' : ''?> />
					禁用</label>
				</div>
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