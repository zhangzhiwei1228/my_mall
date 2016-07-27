<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '投诉/建议';
$this->head()->setTitle($this->title);
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
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<td width="20" align="center"><input type="checkbox" class="JS_AllChecked JS_Checkbox" /></td>
					<td width="70">类型</td>
					<td>留言内容</td>
					<td width="160">用户</td>
					<td width="120">发表时间</td>
					<td width="80">操作</td>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="6"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" class="JS_OneChecked JS_Checkbox" value="<?=$row['id']?>" /></td>
					<td><?=$row['type']?></td>
					<td>
						<div style="margin-right:100px; line-height:1.8em;">
						<?=nl2br($row['content'])?>
						</div>
						<?php if ($row['reply']) { ?>
						<blockquote style="color:#f30; font-size:13px"><strong>回复:</strong>
							<?=nl2br($row['reply'])?>
							<div>
								by <strong><?=$row['admin_name']?></strong>
								[<?=date(DATETIME_FORMAT, $row['reply_time'])?>]
							</div>
						</blockquote>
						<?php } ?>
					</td>
					<td>
						<?php if ($row['user_id']) { ?>
						<a href="<?=$this->url('controller=user&action=detail&id='.$row['user_id'])?>" target="_blank">
							<?=$row['user_name']?></a> 
						<?php } else { echo '游客'; }?>
						<p><?=long2ip($row['client_ip']) ?></p>
					</td>
					<td><?=date(DATETIME_FORMAT, $row['create_time'])?></td>
					<td valign="top">
						<a href="<?=$this->url('action=reply&id=' . $row['id'].'&ref='.$this->_request->url)?>">回复</a>
						<a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
					</td>
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