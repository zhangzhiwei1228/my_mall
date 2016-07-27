<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = $this->data['name'];
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="panel panel-default">
	<div class="sui-page-header">
		<h2> <?=$this->head()->getTitle()?></h2>
	</div>

	<div class="row">
		<div class="col-xs-9">
			<img src="<?=$this->data['src']?>" style="max-width:700px" />
		</div>
		<div class="col-xs-3">
			<div class="panel panel-default panel-shadow">
				<div class="sui-page-header"><h4>图片属性</h4></div>
				<div class="panel-body">
					<div class="form-group">
						<label class="control-label col-sm-2">上传时间:</label>
						<div class="col-sm-9"><?=date(DATETIME_FORMAT, $this->data['create_time'])?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">原图大小:</label>
						<div class="col-sm-9"><?=round($this->data['size']/1024, 2)?>KB</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">图片分类:</label>
						<div class="col-sm-9">默认分类</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default panel-shadow">
				<div class="sui-page-header"><h4>相关操作</h4></div>
					<ul class="list-group">
						<?php if ($this->prevItem->exists()) { ?>
						<li class="list-group-item"><a href="<?=$this->url('&id='.$this->prevItem['id'])?>">查看上一张</a></li>
						<?php } ?>
						<?php if ($this->nextItem->exists()) { ?>
						<li class="list-group-item"><a href="<?=$this->url('&id='.$this->nextItem['id'])?>">查看下一张</a></li>
						<?php } ?>
						<li class="list-group-item"><a href="<?=$this->url('&action=delete&ref='.$this->_request->ref)?>" onclick="return confirm('确定要删除这条记录吗?')">删除图片</a></li>
						<li class="list-group-item"><a href="<?=$ref?>">返回上一页</a></li>
					</ul>
			</div>
		</div>
	</div>
</div>