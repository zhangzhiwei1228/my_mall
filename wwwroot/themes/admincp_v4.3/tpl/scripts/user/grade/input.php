<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '等级';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="col-sm-2 control-label">头衔:</label>
			<div class="col-sm-5">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<!-- <div class="form-group">
			<label class="col-sm-2 control-label">经验值区间:</label>
			<div class="col-sm-5">
				<div class="input-group">
					<input type="text" name="min_exp" id="min-exp" value="<?=$this->data['min_exp']?>" class="form-control" />
					<span class="input-group-addon">~</span>
					<input type="text" name="max_exp" id="max-exp" value="<?=$this->data['max_exp']?>" class="form-control" />
				</div>
				<div class="help-block">
					用户经验值到达此区间时，系统将自动升级。
				</div>
			</div>
		</div> -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>