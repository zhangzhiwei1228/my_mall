<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '页面';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">页面标题:</label>
			<div class="col-sm-9">
				<input type="text" name="title" value="<?=$this->data['title']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">引用代码:</label>
			<div class="col-sm-9">
				<input type="text" name="code" value="<?=$this->data['code']?>" class="form-control" />
				<p class="help-block">仅用于页面调用，为空时系统将自动生成一个标识码</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">页面内容:</label>
			<div class="col-sm-9"><div class="input-group">
				<textarea name="content" class="form-control" rows="20" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['content'])?></textarea>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">点击率:</label>
			<div class="col-sm-9">
				<input type="text" name="clicks_num" value="<?=$this->data['clicks_num']?>" class="form-control" />
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