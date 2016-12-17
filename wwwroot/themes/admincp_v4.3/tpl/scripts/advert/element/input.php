<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '广告';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
$this->paths[] = array(
	'name' => $this->data->advert['name'],
	'url' => $ref,
);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">广告主题:</label>
			<div class="col-sm-6">
				<input type="text" name="theme" value="<?=$this->data['theme']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">选择类型:</label>
			<div class="col-sm-6">
				<select name="jump_id" class="form-control" id="jump_id">
					<?php foreach ($this->jump as $row) { ?>
						<option value="<?=$row['type']?>" <?php if ($this->data['jump_id'] == $row['type']) echo 'selected';?>> <?=$row['name']?></option>
					<?php } ?>
				</select>
				<input id="exts_id" type="text" name="exts_id" value="<?=$this->data['exts_id']?>" class="form-control" placeholder="请输入要绑定的id" style="<?php echo $this->data['app'] && $this->data['app'] < 4 ? 'display: block': 'display: none' ?>"/>

			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">投放位置:</label>
			<div class="col-sm-6">
				<select name="advert_id" class="form-control">
					<?php
					$aid = $this->data['advert_id'] ? $this->data['advert_id'] : (int)$this->_request->aid;
					foreach ($this->advert as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($aid == $row['id']) echo 'selected';?>> <?=$row['name']?> [ <?=$row['code']?> ]</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">展现形式:</label>
			<div class="col-sm-6">
				<select name="type" class="form-control" onchange="changeType(this.value)">
					<option value="none" <?php if ($this->data['type'] == 'none') echo 'selected';?>>请选择</option>
					<option value="text" <?php if ($this->data['type'] == 'text') echo 'selected';?>>文本</option>
					<option value="image" <?php if ($this->data['type'] == 'image') echo 'selected';?>>图片</option>
					<option value="html" <?php if ($this->data['type'] == 'html') echo 'selected';?>>HTML</option>
				</select>
			</div>
		</div>
		<div id="setting"></div>
		<div class="form-group">
			<label class="control-label col-sm-2">投放时间:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<span class="input-group-addon">起</span>
					<input type="text" name="start_time" value="<?=$this->data['start_time'] ? date(DATETIME_FORMAT, $this->data['start_time']) : date(DATETIME_FORMAT)?>" class="form-control" data-plugin="datetime-picker" />
					<span class="input-group-addon">止</span>
					<input type="text" name="end_time" value="<?=$this->data['end_time'] ? date(DATETIME_FORMAT, $this->data['end_time']) : ''?>" class="form-control" data-plugin="datetime-picker" />
				</div>
				<p class="help-block">结束时间为空时表示无限制投放</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">是否启用:</label>
			<div class="col-sm-6">
				<div class="radio">
				<?php $isEnabled = isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 1?>
				<label>
					<input type="radio" name="is_enabled" <?php if ($isEnabled == 1) echo 'checked'?> value="1" />
					启用</label>
				<label>
					<input type="radio" name="is_enabled" <?php if ($isEnabled == 0) echo 'checked'?> value="0" />
					禁用</label>
				</div>
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="control-label col-sm-2">排序:</label>
			<div class="col-sm-2">
				<input type="text" name="rank" value="<?=$this->data['rank']?>" class="form-control" />
			</div>
		</div> -->
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
function changeType(val) {
	switch (val) {
		case 'html': $('#setting').html($('#_html').html()); break;
		case 'text': $('#setting').html($('#_text').html()); break;
		case 'image': $('#setting').html($('#_image').html()); $.initSucoJS(); break;
	}
}
$('select[name=position_id]').change(function(){
	changeType($(this).val());
});
$.fn.ready(function() {
	changeType('<?=$this->data['type']?>');
});
$(document).ready(function() {
	var id = $('#jump_id option:selected').val();
	if(id < 4) {
		$('#exts_id').show();
	}
	$('#jump_id').change(function() {
		var app_id = $('#jump_id option:selected').val();
		if(app_id < 4) {
			$('#exts_id').show();
		} else {
			$('#exts_id').hide();
		}
	});
});
</script> 

<!-- 输入模板 --> 
<script id="_html" type="text/template">
	<div class="form-group">
		<label class="control-label col-sm-2">HTML代码:</label>
		<div class="col-sm-6">
			<textarea name="html" rows="10" class="form-control"><?=stripcslashes($this->data['html'])?></textarea>
		</div>
	</div>
</script> 
<script id="_text" type="text/template">
	<div class="form-group">
		<label class="control-label col-sm-2">内容文本:</label>
		<div class="col-sm-6"><textarea name="description" rows="3" class="form-control"><?=$this->data['description']?></textarea></div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">链接地址:</label>
		<div class="col-sm-6"><input type="text" name="link" value="<?=$this->data['link']?>" class="form-control" /></div>
	</div>
</script> 
<script id="_image" type="text/template">
	<div class="form-group">
		<label class="control-label col-sm-2">上传图片:</label>
		<div class="col-sm-6">
			<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
				data-ipt="source" data-ref="adv">
				<div class="sui-img-value"><?=$this->data['source']?$this->baseUrl($this->data['source']):''?></div>
				<div class="sui-img-selector-box"></div>
				<div class="sui-img-selector-btns">
					<button type="button" class="btn" role="btn">选择图片</button>
					<span class="help-block" style="display:inline">请选择一张广告图片</span>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">图片说明:</label>
		<div class="col-sm-6"><textarea name="description" rows="3" class="form-control"><?=$this->data['description']?></textarea></div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">链接地址:</label>
		<div class="col-sm-6"><input type="text" name="link" value="<?=$this->data['link']?>" class="form-control" /></div>
	</div>
</script>