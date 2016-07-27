<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '活动';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem JS_Form">
		<input type="hidden" name="activity_id" value="<?=$this->data['id']?>" />
		<div class="form-group">
			<label class="control-label col-sm-2"><b class="required">*</b>活动名称:</label>
			<div class="col-sm-6">
				<input type="text" name="theme" value="<?=$this->data['theme']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><b class="required">*</b>活动方式:</label>
			<div class="col-sm-6">
				<select name="type" onchange="changeTpl(this.value)" class="form-control">
					<option value="">请选择...</option>
					<?php foreach ($this->types as $key => $name) { ?>
					<option value="<?=$key?>" <?php if ($this->data['type'] == $key) echo 'selected'?>>
					<?=$name?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">开始时间:</label>
			<div class="col-sm-6">
				<input type="text" name="start_time" value="<?=date(DATETIME_FORMAT, $this->data['start_time'] ? $this->data['start_time'] : time())?>" class="form-control" data-plugin="datetime-picker" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">结束时间:</label>
			<div class="col-sm-6">
				<input type="text" name="end_time" value="<?=$this->data['end_time'] ? date(DATETIME_FORMAT, $this->data['end_time']) : ''?>" class="form-control" data-plugin="datetime-picker" />
				<p class="help-block">为空表示长期有限</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">优先级:</label>
			<div class="col-sm-6">
				<input type="text" name="priority" value="<?=$this->data['priority']?>" class="form-control" />
				<p class="help-block">同时满足多个活动条件时，将根据优先级计算。<br />
					(数值越高越优先)</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否启用:</label>
			<div class="col-sm-6">
				<div class="radio">
				<?php $isEnabled = isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 1?>
				<label>
					<input type="radio" name="is_enabled" value="1" <?=$isEnabled == 1 ? 'checked' : ''?> />
					启用</label>
				<label>
					<input type="radio" name="is_enabled" value="0" <?=$isEnabled == 0 ? 'checked' : ''?> />
					关闭</label>
				</div>
			</div>
		</div>
		<div id="activity-setting"> </div>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
function changeTpl(t) {
	$('#activity-setting').load('<?=$this->url('action=setting')?>', {type:t, activity_id:<?=(int)$this->data['id']?>});
}
changeTpl('<?=$this->data['type']?>');
</script>