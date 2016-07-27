<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '链接';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">站点名称:</label>
			<div class="col-sm-5">
				<input type="text" name="site" value="<?=$this->data['site']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">分组:</label>
			<div class="col-sm-5">
				<select onchange="selectGroup(this.value)" class="form-control">
					<option value="">默认分组</option>
					<?php foreach ($this->groups as $val) { ?>
					<option value="<?=$val?>" <?php if ($val == $this->data['group']) echo 'selected'?>> <?=$val?> </option>
					<?php } ?>
					<option value="-1">其它分组</option>
				</select>
				<span id="addnew-group" style="display:none">
				<input type="text" name="group" value="<?=$this->data['group']?>" class="form-control" />
				</span> </div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">LOGO:</label>
			<div class="col-sm-5">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
					data-ipt="logo" data-ref="site-logo">
					<div class="sui-img-value"><?=$this->data['logo']?$this->baseUrl($this->data['logo']):''?></div>
					<div class="sui-img-selector-box"></div>
					<div class="sui-img-selector-btns">
						<button type="button" class="btn" role="btn">选择图片</button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">站点描述:</label>
			<div class="col-sm-5"> <textarea name="description" class="form-control"><?=$this->data['description']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">链接地址:</label>
			<div class="col-sm-5">
				<input type="text" name="url" id="url" value="<?=$this->data['url'] ? $this->data['url'] : 'http://'?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
function selectGroup(val) {
	if (parseInt(val) == -1) {
		$('#addnew-group').show();
		$('input[name=group]').val('');
	} else {
		$('#addnew-group').hide();
		$('input[name=group]').val(val);
	}
}
</script> 