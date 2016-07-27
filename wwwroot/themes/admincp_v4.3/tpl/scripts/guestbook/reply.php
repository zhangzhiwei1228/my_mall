<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '回复留言';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2">用户:</label>
			<div class="col-sm-9">
				<div class="form-control-static">
					<?=$this->data['user_id'] ? $this->data->user['username'] : '游客'?>
					[<a href="<?=$this->url('controller=user&action=detail&id='.$this->data['id'])?>" target="_blank">查看</a>]
					<br><?=long2ip($this->data['client_ip'])?>
					(<?=new Ip_Location(long2ip($this->data['client_ip']))?>)
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">留言:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><pre><?=$this->data['content']?></pre></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">回复:</label>
			<div class="col-sm-9"><textarea name="reply" class="form-control" rows="6"><?=$this->data['reply']?></textarea></div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
			<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>