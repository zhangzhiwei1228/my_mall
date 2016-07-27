<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '日志分析';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-groups-bordered">
			<select id="select-page" onchange="window.location = '?filename='+this.value" class="form-control">
				<option value="0">请选择...</option>
				<?php foreach ($this->logs as $row) { ?>
				<option value="<?=$row['name']?>" <?php if ($row['name'] == $this->_request->filename) echo 'selected'; ?>> <?=$row['name']?></option>
				<?php } ?>
			</select><br />
			<textarea name="content" class="form-control" rows="20" style="width:100%"><?=stripcslashes($this->file)?></textarea>
		</div>
	</form>
</div>