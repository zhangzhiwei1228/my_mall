<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('我的站内信');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" action="<?=$this->url('action=search')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入关键词查询" />
				<?php if ($this->_request->q) { ?>
				<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
				<?php } ?>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">

		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
				<i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=new&ref='.$this->_request->url)?>"> 
				<i class="glyphicon glyphicon-envelope"></i> 发送站内信</a>
		</div>
		<table width="100%" class="table table-striped sui-my-msgs" data-plugin="chk-group">
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
						<td colspan="6"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr class="<?=$row['is_read']?'':'warning'?>">
					<td width="20" align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td width="40">
						<img src="<?=$row['sender_avatar']?$this->baseUrl($row['sender_avatar']):'./img/no-avatar.png'?>" class="avatar" width="40" /></td>
					<td>
						<div style="margin-bottom:5px;"><?=$row['sender_name']?></div>
						<div style="color:#888">
							<strong style="color:#555"><?=$this->highlight($row['title'], $this->_request->q)?></strong>
							<?=nl2br($row['content'])?></div>
					</td>
					<td width="180" class="text-center"><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td width="50">
						<!-- <a href="<?=$this->url('action=change&m=is_read&id=' . $row['id'].'&ref='.$this->_request->url)?>" class="btn btn-default btn-xs"><?=$row['is_read']?'未读':'已读'?></a> -->
						<a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')" class="btn btn-default btn-xs">删除</a></td>
				</tr>
				<?php } } ?>
			</tbody>
		</table>
		<div class="sui-toolbar">
			<script type="text/javascript">
				var toolbar = $('.sui-toolbar').clone();
				document.write(toolbar.html());
			</script>
		</div>
	</form>
</div>
