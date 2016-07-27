<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '数据库修复工具';
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		 <h1><?=$this->head()->getTitle()?></h1>
	</div>

	<div class="alert alert-warning" style="margin-top:15px">
		<strong>注意!</strong> 
		<ul>
			<li>请勿过于频繁的进行数据库修复操作，请在您的数据库出现致命错误时进行操作，例如：.MYI索引文件缺失；</li>
			<li>如果在修复过程中，服务器停机，则在重新启动之后，在执行其它操作之前，请您尽量先对表再执行一此修复。</li>
		</ul>
	</div>
	

	<?php if ($this->result) { ?>
	<h3 class="heading">SQL执行结果！ 
		<small>[<a href="<?=$this->url('&')?>">返回</a>]</small>
		</h3>
	<table class="table table-striped" data-plugin="chk-group">
		<thead>
			<tr>
				<th>数据表</th>
				<th width="300">状态</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->result as $row) { ?>
			<tr>
				<td><?=$row['Table']?></td>
				<td><?=$row['Msg_text']?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>

	<?php } else { ?>
	<form method="post" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<div class="btn-group btn-group-sm">
				<button type="submit" name="act" value="repair" class="btn btn-default"> <i class="fa fa-repeat"></i> 修复</button>
				<button type="submit" name="act" value="analyze" class="btn btn-default"> <i class="fa fa-space-shuttle"></i> 优化</button>
			</div>
		</div>
		<table class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" class="text-center"><input type="checkbox" role="chk-all" /></th>
					<th>数据表</th>
					<th width="200">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->tbsChk as $row) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="tbs[]" role="chk-item" value="<?=$row['Table']?>" /></td>
					<td><?=$row['Table']?></td>
					<td><?=$row['Msg_text']?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="sui-toolbar">
			<script type="text/javascript">
				var toolbar = $('.sui-toolbar').clone();
				document.write(toolbar.html());
			</script>
		</div>
	</form>
	<?php } ?>
</div>