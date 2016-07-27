<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '查看发票';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" action="<?=$this->url('&action=edit')?>" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<input type="hidden" name="status" value="2">
		<div class="well">
			<h4>邮寄地址</h4>
			<p>收件人：<?=$this->data['consignee']?> (电话：<?=$this->data['phone']?>)</p>
			<p>地址：<?=$this->data['area_text']?> <?=$this->data['address']?> （邮编：<?=$this->data['zipcode']?>）</p>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">发票抬头：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['title']?></p>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">税务登记证号：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['code']?></p>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">开票金额：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['invoice_amount']?></p>
		</div>
		<div class="form-group"> 
			<label class="control-label col-sm-2">基本开户银行名称：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['bank_name']?></p>
		</div>
		<div class="form-group"> 
			<label class="control-label col-sm-2">基本开户账号：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['bank_account']?></p>
		</div>
		<div class="form-group"> 
			<label class="control-label col-sm-2">注册场所地址：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['company_address']?></p>
		</div>
		<div class="form-group"> 
			<label class="control-label col-sm-2">注册固定电话：</label>
			<p class="form-control-static col-sm-10"><?=$this->data['company_tel']?></p>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>操作备注:</label>
			<div class="col-sm-6"><textarea name="remark" class="form-control" rows="3"><?=$this->data['remark']?></textarea></div>
		</div>
		<div class="form-group">
			<div class="col-sm-6 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 已开票</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>