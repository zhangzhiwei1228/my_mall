<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '专题';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>



<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"> 主题: </label>
			<div class="col-sm-7">
				<input type="text" name="theme" value="<?=$this->data['theme']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 封面: </label>
			<div class="col-sm-7">
				<div id="remote-pic">
					<input type="text" name="thumb" value="<?=$this->data['thumb']?>" class="form-control" />
					<p class="help-block"><a href="javascript:void(0)" onclick="$('#local-pic').show(); $('#remote-pic').hide(); $('#attachment').val('')">本地上传</a></p>
				</div>
				<div id="local-pic" style="display:none">
					<input type="file" name="attachment" class="form-control" />
					<p class="help-block"><a href="javascript:void(0)" onclick="$('#remote-pic').show(); $('#local-pic').hide(); $('#thumb').val('')">远程链接</a></p>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 内容: </label>
			<div class="col-sm-7"><div class="input-group">
				<textarea name="html" rows="20" class="form-control" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['html'])?></textarea>
				</div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2"> 自定义CSS: </label>
			<div class="col-sm-7"><textarea name="css" rows="5" class="form-control"><?=stripcslashes($this->data['css'])?></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-7 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
