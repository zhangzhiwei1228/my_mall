<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '修改销售基数';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="tab-content">
			<div  class="tab-pane fade active in ">
				<div class="form-group">
					<label class="control-label col-sm-2"> 基数值 </label>
					<div class="col-sm-4">
						<textarea name="sales_num" class="form-control" rows="2"><?=$this->data['sales_num']?></textarea>
					</div>
				</div>
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
<script>
	$(document).ready(function() {
	});
</script>