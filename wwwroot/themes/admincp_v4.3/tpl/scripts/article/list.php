<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = $this->_request->search ? '文章搜索' : ($this->category->exists() ? $this->category['name'] : '文章列表');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" action="<?=$this->url('action=search')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<select name="cid" class="form-control input-sm">
					<option value="0">全部分类</option>
					<?php foreach ($this->categories as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($this->_request->cid == $row['id']) echo 'selected'; ?>>
					<?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?>
					</option>
					<?php } ?>
				</select>
			</div>
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
			<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#move-box"> <i class="fa fa-exchange"></i> 移动</button>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&cid='.$this->_request->cid.'&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 添加文章</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" class="text-center"><input type="checkbox" role="chk-all" /></th>
					<th>ID</th>
					<th>标题</th>
					<th width="120">所属分类</th>
					<!--<td width="120">出处</td>-->
					<th width="50" class="text-center hidden-xs">审核</th>
					<th width="50" class="text-center hidden-xs">置顶</th>
					<th width="50" class="text-center hidden-xs">原创</th>
					<th width="150" class="hidden-xs">修改时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr class="text-center">
					<td colspan="8"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?php echo $row['id']?></td>
					<td><a id="img_<?=$row['id']?>" href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['title'], $this->_request->q)?><?php if ($row['thumb']) { echo '(图)'; }?></a>
												</td>
					<td><a href="<?=$this->url('action=list&cid='.$row['cid'])?>" style="color:#333"><?=$this->cutstr($row['name'], 16)?></a></td>
					<!--<td>
						<?php 
						if ($row['ref_url']) {
							echo '<a href="'.$this->url($row['ref_url']).'" target="_blank">'.$this->cutstr($row['source'], 16).'</a>';
						} else {
							echo $this->cutstr($row['source'], 16);
						} ?></td>-->
					<td class="text-center hidden-xs"><a href="<?=$this->url('action=change&m=is_checked&id='.$row['id'].'&s='.$row['is_checked'])?>">
						<?php switch ($row['is_checked']) { 
							case 0; echo '<font class="label label-default">待审</font>'; break;
							case 1; echo '<font class="label label-danger">不通过</font>'; break;
							case 2; echo '<font class="label label-success">通过</font>'; break;
						} ?>
					</a></td>
					<td class="text-center hidden-xs"><a href="<?=$this->url('action=change&m=is_best&id='.$row['id'])?>">
						<?=$row['is_best'] ? '<font class="label label-success">是</font>' : '<font class="label label-danger">否</font>' ?></a></td>
					<td class="text-center hidden-xs"><a href="<?=$this->url('action=change&m=is_original&id='.$row['id'])?>">
						<?=$row['is_original'] ? '<font class="label label-success">是</font>' : '<font class="label label-danger">否</font>' ?></a></td>
					<td class="hidden-xs"><?=$row['update_time'] ? date(DATETIME_FORMAT, $row['update_time']) : 'N/A'?></td>
					<td>
						<a href="<?=$this->url('action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">编辑</a>
						<a href="<?=$this->url('action=delete&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
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
		
		<!-- Modal -->
		<div class="modal fade" id="move-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" style="width:400px">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">移动至...</h4>
				</div>
				<div class="modal-body">
				<select name="cid" class="form-control">
					<option value="0">请选择分类</option>
					<?php foreach ($this->categories as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($this->_request->cid == $row['id']) echo 'selected'; ?>>
					<?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?>
					</option>
					<?php } ?>
				</select>
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="submit" class="btn btn-primary" name="act" value="move">保存设置</button>
				</div>
			</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->	
		
	</form>
</div>