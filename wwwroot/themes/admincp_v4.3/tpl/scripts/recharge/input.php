<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle('充值');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem JS_Form">
		<?php if ($this->data) { ?>
		<div class="form-group">
			<label class="control-label col-sm-2">充值帐户: </label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data->user['username']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">充值金额: </label>
			<div class="col-sm-6"><p class="form-control-static">&yen; <?=$this->data['amount']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手续费: </label>
			<div class="col-sm-6"><p class="form-control-static">&yen; <?=$this->data['fee']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">支付方式: </label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data->payment['name']?></p></div>
		</div>
		<?php } else { ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>充值帐户: </label>
			<div class="col-sm-6"><input type="text" name="username" value="<?=$this->account['username']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>充值金额: </label>
			<div class="col-sm-6"> 
				<div class="input-group">
					<span class="input-group-addon">&yen;</span>
					<input type="text" name="amount" value="" class="form-control" />
					<span class="input-group-addon">元</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手续费:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<span class="input-group-addon">&yen;</span>
					<input type="text" name="fee" value="<?=$this->data['fee']?>" class="form-control" />
					<span class="input-group-addon">元</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>支付方式: </label>
			<div class="col-sm-6"><select name="payment_id" class="form-control">
				<option value="">请选择</option>
				<?php foreach($this->payments as $row) { ?>
				<option value="<?=$row['id']?>"><?=$row['name']?></option>
				<?php } ?>
				</select>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>凭证号: </label>
			<div class="col-sm-6">
				<input type="text" name="voucher" value="<?=$this->data['voucher']?>" class="form-control" />
				<div class="help-block">小提示：凭证号是用于各银行及支付平台对帐的重要凭据, 请认真填写<br />
				为方便日后管理，请在原始凭证前为指定支付方式加前缀<br />
				如：建行凭证号为 87654321。 我们可以在此凭证号前加建行的缩写 "CCB" 就像这样 "CCB-87654321"</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>操作备注: </label>
			<div class="col-sm-6"> <textarea name="remark" class="form-control" rows="3"><?=$this->data['remark']?></textarea></div>
		</div>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script>
seajs.use('/assets/js/validator/validator.sea.js', function(validator){
	validator('.JS_Form', {
		rules: {
			'username': { valid: 'required', errorText: '请填写充值帐户' },
			'amount': { valid: 'required|numeric', errorText: '请填写充值金额|金额必须是数字' },
			'fee': { valid: 'numeric', errorText: '金额必须是数字' },
			'payment_id': { valid: 'required', errorText: '请选择充值方式' },
			'voucher': { valid: 'required', errorText: '请填写凭证号' },
			'remark': { valid: 'required', errorText: '请填写操作备注' }
		}
	});
});
</script>