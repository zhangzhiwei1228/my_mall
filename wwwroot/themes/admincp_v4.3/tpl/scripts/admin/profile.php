<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle(($this->_request->getActionName() == 'add' ? '添加' : '修改'). '管理员');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>用户名:</label>
			<div class="col-sm-9">
				<input type="text" name="username" value="<?=$this->data['username']?>" disabled="disabled" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>权限组:</label>
			<div class="col-sm-9">
				<select name="group_id" size="5" class="form-control" disabled="disabled">
					<?php foreach ($this->groups as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($row['id'] == $this->data['group_id']) echo 'selected';?>> <?=$row['name']?> </option>
					<?php } ?>
				</select>
			</div>
		</div>
		<?php if ($this->_request->getActionName() == 'add') { ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>密码:</label>
			<div class="col-sm-9">
				<input type="password" name="password" value="" class="form-control" />
			</div>
		</div>
		<?php } else { ?>
		<div class="form-group">
			<label class="control-label col-sm-2">密码:</label>
			<div class="col-sm-9">
				<input type="password" name="password" value="" class="form-control" />
				<p class="help-block">不修改请留空</p>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>全名:</label>
			<div class="col-sm-9">
				<input type="text" name="nickname" id="ccd" value="<?=$this->data['nickname']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">Email:</label>
			<div class="col-sm-9">
				<input type="text" name="email" value="<?=$this->data['email']?>" class="form-control" />
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
<script type="text/javascript">
seajs.use('/assets/js/validator/validator.sea.js', function(validator){
	validator('form', {
		rules: {
			<?php if ($this->_request->getActionName() == 'add') { ?>
			'[name=username]': { valid: 'required', errorText: '请输入您的帐户' },
			'[name=password]': { valid: 'required|strlen', minlen:6, maxlen:16, errorText: '请填写新密码|密码必须是由6至16位的字母、数字或符号组合' },
			<?php } ?>
			'[name=group_id]': { valid: 'required', errorText: '请选择权限组' },
			'[name=nickname]': { valid: 'required', errorText: '请输入您的全名' }
		}
	});
});
</script>