<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '修改收货地址';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">所在地:</label>
			<div class="col-sm-9"><div class="JS_Dmenu form-inline">
					<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
					<input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
				</div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">收货人:</label>
			<div class="col-sm-5"><input type="text" name="consignee" value="<?=$this->data['consignee']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">收货地址:</label>
			<div class="col-sm-5">
				<textarea name="address" class="form-control" rows="3"><?=$this->data['address']?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">邮编:</label>
			<div class="col-sm-5"><input type="text" name="zipcode" value="<?=$this->data['zipcode']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手机:</label>
			<div class="col-sm-5"><input type="text" name="mobile" value="<?=$this->data['mobile']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">固话:</label>
			<div class="col-sm-5"><input type="text" name="tel" value="<?=$this->data['tel']?>" class="form-control" /></div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
			<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script>
seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
	dmenu.init('.JS_Dmenu', {
		rootId: 1,
		script: '<?=$this->url('controller=region&action=getJson')?>',
		htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
		firstText: '请选择所在地',
		defaultText: '请选择',
		selected: <?=$this->data['area_id']?>,
		callback: function(el, data) { 
			var location = $('.JS_Dmenu>select>option:selected').text();
			$('input[name=area_id]').val(data.id > 0 ? data.id : 0); 
			$('input[name=area_text]').val(location);
			$('input[name=zipcode]').val(data.zipcode > 0 ? data.zipcode : '');
		}
	});
});
</script>