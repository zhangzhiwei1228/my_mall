<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '填写发货信息';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="panel panel-warning">
	<div class="panel-heading">
		<div class="panel-title">收件人信息</div>
	</div>
	<div class="panel-body">
		<blockquote style="margin:0; font-size:14px;">收件人：<?=$this->data['consignee']?><br />
			地址：<?=$this->data['area_text']?> <?=$this->data['address']?><br />
			邮编：<?=$this->data['zipcode'] ? $this->data['zipcode'] : 'N/A'?><br />
			电话：<?=$this->data['phone']?>
		</blockquote>
	</div>
</div>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">物流方式:</label>
			<div class="col-sm-5"><select name="shipping_id" class="form-control">
				<?php foreach ($this->shipping as $row) { ?>
				<option value="<?=$row['id']?>" <?php if ($row['id'] == $this->data['shipping_id']) echo 'selected';?>><?=$row['name']?></option>
				<?php } ?>
			</select></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">物流单号:</label>
			<div class="col-sm-5"><input type="text" name="code" class="form-control" /></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">操作备注:</label>
			<div class="col-sm-5"><textarea name="remark" rows="3" class="form-control"></textarea></div>
		</div>
		<div class="form-group">
			<div class="col-sm-5 col-sm-offset-2">
				<button type="submit" class="btn btn-primary">确认</button>
				<button type="button" class="btn btn-default" onclick="window.location = '<?=$this->url($ref)?>'">取消</button></div>
		</div>
	</form>

</div>
<?php $this->head()->captureStart()?>
<script type="text/javascript" src="<?=$this->baseUrl('js/jquery.countdown1.2.js')?>"></script>
<script type="text/javascript">
$(document).ready(function() { if ($(".countdown").length) {
	$(".countdown").countdown({date: "<?=date('Y/m/d H:i:s', $this->data['expiry_time'])?>", language:'zh_CN'});
}});
</script>
<script type="text/javascript">
$.fn.ready(function() {
	$('#ucp-transaction').addClass('current');
});
</script>
<?php $this->head()->captureEnd()?>
