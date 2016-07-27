<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '优惠券';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">礼券名称:</label>
			<div class="col-sm-6">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">礼券金额:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" name="amount" value="<?=$this->data['amount']?>" class="form-control" />
					<span class="input-group-addon">元</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">发行量:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" name="quantity" value="<?=$this->data['quantity']?>" class="form-control" />
					<span class="input-group-addon">张</span>
				</div>
				<!-- <div class="help-block">0表示不限数量</div> -->
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">有效期:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" name="start_time" value="<?=$this->data['start_time'] ? date(DATE_FORMAT, $this->data['start_time']) : ''?>" class="form-control" data-plugin="date-picker" />
					<span class="input-group-addon">起</span>
					<input type="text" name="end_time" value="<?=$this->data['end_time'] ? date(DATE_FORMAT, $this->data['end_time']) : ''?>" class="form-control" data-plugin="date-picker" />
					<span class="input-group-addon">止</span>
				</div>
				<div class="help-block">为空表示不限</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">使用说明:</label>
			<div class="col-sm-6"><textarea name="description" class="form-control" rows="3"><?=$this->data['description']?></textarea></div>
		</div>
		<h4 class="heading">使用条件</h4>
		<div class="form-group">
			<label class="control-label col-sm-2">满足金额:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<input type="text" name="precond[amount]" value="<?=$this->data->precond['amount']?>" class="form-control" />
					<span class="input-group-addon">元</span>
				</div>
				<div class="help-block">消费满足以上金额时方可使用，为0时表示不限。</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">限定用户:</label>
			<div class="col-sm-6">
				<textarea name="precond[members]" class="form-control" rows="3"><?=$this->data->precond['members']?></textarea>
				<div class="help-block">此处为空表示不限制使用人。 <br />如需绑定使用人，直接填写会员帐户即可。 支持多个，用半角逗号 “,” 分隔</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
