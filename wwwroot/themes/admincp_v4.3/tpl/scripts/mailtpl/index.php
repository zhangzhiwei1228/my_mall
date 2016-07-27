<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '邮件模版';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">选择模板:</label>
			<div class="col-sm-9">
				<select id="select-file" onchange="window.location = '?url='+this.value" class="form-control">
					<option value="0">请选择...</option>
					<?php foreach ($this->files as $row) { ?>
					<option value="<?=base64_encode($row['path'].$row['name'])?>" <?php if ($this->_request->url == base64_encode($row['path'].$row['name'])) echo 'selected'?>><?=$row['name']?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">邮件主题:</label>
			<div class="col-sm-9">
				<input type="text" name="subject" value="<?=$this->subject?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">邮件内容:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<textarea name="content" class="form-control" rows="20" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->content)?></textarea>
				</div>
				<div class="help-block">
					大括号包裹部分为变量，如：{$var}
				</div>
			</div>
		</div>
		<?php if ($this->content) { ?>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存模板</button>
			</div>
		</div>
		<?php } ?>
	</form>
</div>