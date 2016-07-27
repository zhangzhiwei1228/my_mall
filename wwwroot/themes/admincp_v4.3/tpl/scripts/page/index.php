<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '页面设置';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<div class="pull-right">
			<a href="<?=$this->url('action=list')?>" class="btn btn-sm btn-default">查看全部页面</a>
		</div>
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-groups-bordered">
			<select id="select-page" onchange="window.location = '?id='+this.value" class="form-control">
				<option value="0">请选择...</option>
				<?php foreach ($this->pages as $row) { ?>
				<option value="<?=$row['id']?>" <?php if ($row['id'] == $this->_request->id) echo 'selected'; ?>> <?=$row['title']?> [ <?=$row['code']?> ]</option>
				<?php } ?>
			</select><br />
			<textarea name="content" class="form-control" rows="20" style="width:100%" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['content'])?></textarea>
		</div>
		<?php if ($this->data->exists()) { ?>
		<div>
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
			<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
		</div>
		<?php } ?>
	</form>
</div>