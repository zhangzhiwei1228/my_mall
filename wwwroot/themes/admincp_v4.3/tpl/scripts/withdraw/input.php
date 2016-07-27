<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle('提现');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<?php if ($this->data) { ?>
		<div class="form-group">
			<label class="control-label col-sm-2">提现帐户:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data->user['username']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">金额:</label>
			<div class="col-sm-6"><p class="form-control-static">&yen; <?=$this->data['amount']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手续费:</label>
			<div class="col-sm-6"><p class="form-control-static">&yen; <?=$this->data['fee']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">收款人:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data['payee']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">银行:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data['bank_name']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">开户行:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data['bank_sub_branch']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">帐号:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data['bank_account']?></p></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">Swift Code:</label>
			<div class="col-sm-6"><p class="form-control-static"><?=$this->data['bank_swift_code'] ? $this->data['bank_swift_code'] : 'N/A'?></p></div>
		</div>
		<?php } else { ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>提现帐户:</label>
			<div class="col-sm-6"><input type="text" name="username" value="<?=$this->account['username']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>提现金额:</label>
			<div class="col-sm-6">
				<div class="input-group">
					<span class="input-group-addon">&yen;</span>
					<input type="text" name="amount" value="<?=$this->data['amount']?>" class="form-control" />
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
			<label class="control-label col-sm-2"><span class="required">*</span>收款人:</label>
			<div class="col-sm-6"><input type="text" name="payee" value="<?=$this->data['payee']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>银行:</label>
			<div class="col-sm-6"><input type="text" name="bank[bank_name]" value="<?=$this->data['bank_name']?>" class="form-control bank_name" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>开户行:</label>
			<div class="col-sm-6"><input type="text" name="bank[bank_sub_branch]" value="<?=$this->data['bank_sub_branch']?>" class="form-control bank_sub_branch" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>帐号:</label>
			<div class="col-sm-6"><input type="text" name="bank[bank_account]" value="<?=$this->data['bank_account']?>" class="form-control bank_account" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">Swift Code:</label>
			<div class="col-sm-6"><input type="text" name="bank[bank_swift_code]" value="<?=$this->data['bank_swift_code']?>" class="form-control bank_swift_code" /></div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>凭证号:</label>
			<div class="col-sm-6">
				<input type="text" name="voucher" value="<?=$this->data['voucher']?>" class="form-control" />
				<div class="help-block">
				小提示：凭证号是用于各银行及支付平台对帐的重要凭据, 请认真填写<br />为方便日后管理，请在原始凭证前为指定支付方式加前缀<br />
				如：建行凭证号为 87654321。 我们可以在此凭证号前加建行的缩写 "CCB" 就像这样 "CCB-87654321" 
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>操作备注:</label>
			<div class="col-sm-6"><textarea name="remark" class="form-control" rows="3"><?=$this->data['remark']?></textarea></div>
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
	validator('.sui-form', {
		rules: {
			'username': { valid: 'required', errorText: '请填写充值帐户' },
			'amount': { valid: 'required|numeric', errorText: '请填写充值金额|金额必须是数字' },
			'fee': { valid: 'numeric', errorText: '金额必须是数字' },
			'payee': { valid: 'required', errorText: '请填写收款人姓名' },
			'.bank_name': { valid: 'required', errorText: '请填写收款银行名称' },
			'.bank_sub_branch': { valid: 'required', errorText: '请填写开户行' },
			'.bank_account': { valid: 'required', errorText: '请填写收款帐号' },
			'voucher': { valid: 'required', errorText: '请填写凭证号' },
			'remark': { valid: 'required', errorText: '请填写操作备注' },
		}
	});
});
</script>