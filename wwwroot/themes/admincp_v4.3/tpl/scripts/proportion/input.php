<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '规则';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"> 左侧数值 </label>
			<div class="col-sm-4">
				<textarea name="l_digital" class="form-control" rows="2"><?=$this->data['l_digital']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 左侧名称</label>
			<div class="col-sm-4">
				<select name="left_id" class="form-control">
					<?php foreach($this->coltypes_cash as $row) {?>
						<option value="<?php echo $row['id']?>" <?php echo $this->data['left_id']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
					<?php }?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 右侧数值 </label>
			<div class="col-sm-4">
				<textarea name="r_digital" class="form-control" rows="2"><?=$this->data['r_digital']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 右侧名称</label>
			<div class="col-sm-4">
				<select name="right_id" class="form-control">
					<?php foreach($this->coltypes_cash as $row) {?>
						<option value="<?php echo $row['id']?>" <?php echo $this->data['right_id']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
					<?php }?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 所属类型</label>
			<div class="col-sm-4">
				<select name="type" class="form-control">
					<?php foreach($this->coltypes_desc as $row) {?>
						<option value="<?php echo $row['id']?>" <?php echo $this->data['type']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
					<?php }?>
				</select>
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