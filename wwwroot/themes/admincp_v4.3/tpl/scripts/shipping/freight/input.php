<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '区域运费';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
			<input type="hidden" name="shipping_id" value="<?=$this->data['shipping_id'] ? $this->data['shipping_id'] : $this->_request->sid?>" />
			<input type="hidden" name="destination" value="<?=$this->data['destination']?>" />
		<div class="form-group">
			<label class="control-label col-sm-2">首重:</label>
			<div class="col-sm-9"><div class="input-group" style="width:300px">
				<input type="text" name="first_weight" value="<?=$this->data['first_weight']?>" class="form-control" /> 
					<span class="input-group-addon">公斤</span>
					<input type="text" name="first_freight" value="<?=$this->data['first_freight']?>" class="form-control" /> 
					<span class="input-group-addon">元</span></div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">继重:</label>
			<div class="col-sm-9"><div class="input-group" style="width:300px">
				<input type="text" name="second_weight" value="<?=$this->data['second_weight']?>" class="form-control" />
					<span class="input-group-addon">公斤</span>
					<input type="text" name="second_freight" value="<?=$this->data['second_freight']?>" class="form-control" />
					<span class="input-group-addon">元</span></div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">预计送达:</label>
			<div class="col-sm-9"><div class="input-group" style="width:120px">
				<input type="text" name="estimated_delivery" value="<?=$this->data['estimated_delivery']?>" class="form-control" />
					<span class="input-group-addon">天</span></div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">配送区域:</label>
			<div class="col-sm-9">
				<div class="JS_Mtree" style="width:600px"></div></div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
seajs.use('/assets/js/mtree/mtree.sea.js', function(mtree) {
	mtree.init($('.JS_Mtree'), {
		script:'<?=$this->url('controller=region&action=getMtree')?>',
		input:$('input[name=destination]'),
		disableIds:[<?=$this->disabledIds?>]
	});
});
</script>