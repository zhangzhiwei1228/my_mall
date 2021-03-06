<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). 'App版本';
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
			<div class="col-sm-6">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">版本号:</label>
			<div class="col-sm-6">
				<input type="text" name="type" id="type" value="<?=$this->data['type']?>" class="form-control" placeholder="如：1,2,3....."/>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">版本名称:</label>
			<div class="col-sm-6">
				<input type="text" name="version" value="<?=$this->data['version']?>" class="form-control" />
			</div>
		</div>

		<div class="form-group">
			<label class="control-label col-sm-2">简介:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<textarea name="content" class="form-control" rows="20" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=$this->data['content']?></textarea>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否强制更新:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<label><input type="checkbox" name="force" <?php echo $this->data['force'] == 1 ? 'checked' : ''?>  id="force" value="<?=$this->data['force']?>" /></label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">文件:</label>
			<div class="col-sm-9">
				<div class="input-group">
					<label><input type="file" name="app" id="app" value="" /></label>
				</div>
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
<script>
	$(document).ready(function() {

		$('#force').change(function() {
			var force = $('#force').val();
			if(force && force != 0) {
				$(this).val(0);
			} else {
				$(this).val(1);
			}
		});
	});
</script>