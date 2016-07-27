<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '广告代码';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>

	<div class="panel-body">
		<div class="form-group">
			<textarea style="width:600px; height:100px" class="form-control"><script type="text/javascript" src="<?='misc.php?action=advert&code='.$this->data['code']?>"></script></textarea>
		</div>
		<dl style="margin-top:5px">
			<button type="button" class="btn btn-primary">复制</button>
			<button type="button" class="btn" onclick="window.location= '<?=$ref?>'">返回</button>
		</div>
	</div>
</div>