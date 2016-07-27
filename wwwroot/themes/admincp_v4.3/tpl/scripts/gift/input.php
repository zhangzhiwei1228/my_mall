<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '商品';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>商品标题:</label>
			<div class="col-sm-7">
				<input type="text" name="title" value="<?=$this->data['title']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商品图片:</label>
			<div class="col-sm-7">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
					data-ipt="thumb" data-ref="goods">
					<div class="sui-img-value"><?=$this->data['thumb']?></div>
					<div class="sui-img-selector-box clearfix"></div>
					<div class="sui-img-selector-btns clearfix">
						<button type="button" class="btn" role="btn">选择图片</button>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">市场价格:</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="text" name="market_price" value="<?=$this->data['market_price']?>" class="form-control">
					<span class="input-group-addon">元</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">消耗积分:</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="text" name="points" value="<?=$this->data['points']?>" class="form-control">
					<span class="input-group-addon">点</span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商品描述:</label>
			<div class="col-sm-7">
				<textarea name="description" rows="4" class="form-control" data-plugin="editor"><?=stripcslashes($this->data['description'])?></textarea>
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