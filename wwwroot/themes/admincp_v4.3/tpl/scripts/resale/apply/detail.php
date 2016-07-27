<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '查看申请';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"> 分销商等级: </label>
			<div class="col-sm-4">
				<p class="form-control-static"><?=$this->data['grade']?>星分销商</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 公司或商家名称: </label>
			<div class="col-sm-4">
				<p class="form-control-static"><?=$this->data['company']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 经营类别: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['type']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 场地面积: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['area']?> m<sup>2</sup></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 经营地址: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['location']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 详情地址: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['address']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 日营业额: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['day_selas']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 日客流量: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['day_volume']?> [<?=$this->data['day_volume_stat']?>]</p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 联系人: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['contact']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 联系电话: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['phone']?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 客户留言: </label>
			<div class="col-sm-4"> 
				<p class="form-control-static"><?=$this->data['remark']?></p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">返回</button>
			</div>
		</div>
	</form>
</div>