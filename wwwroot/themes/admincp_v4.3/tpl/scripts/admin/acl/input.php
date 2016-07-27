<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }

$this->head()->setTitle(($this->_request->getActionName() == 'add' ? '添加' : '修改'). '控制单元');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>节点:</label>
			<div class="col-sm-9">
				<input name="description" class="form-control" value="<?=$this->data['description']?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>分组:</label>
			<div class="col-sm-9">
				<select onchange="selectGroup(this.value)" class="form-control">
					<option value="">请选择</option>
					<?php foreach ($this->groups as $val) { ?>
					<option value="<?=$val?>" <?php if ($this->data['package'] == $val) echo 'selected';?>> <?=$val?> </option>
					<?php } ?>
					<option value="-1">其它分组</option>
				</select>
				<span id="addnew-group" style="display:none">
				<input type="text" name="package" value="<?=$this->data['package']?>" class="form-control" placeholder="请填写分组名称" />
				</span>
			</div>
			<script type="text/javascript">
			function selectGroup(val) {
				if (parseInt(val) == -1) {
					$('#addnew-group').show();
					$('input[name=package]').val('');
				} else {
					$('#addnew-group').hide();
					$('input[name=package]').val(val);
				}
			}
			</script>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>资源:</label>
			<div class="col-sm-9"> <textarea name="resource" class="form-control" rows="5"><?=$this->data['resource']?></textarea></div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>