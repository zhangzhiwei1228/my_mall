<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle($this->_request->g ? $this->_request->g : '合作商家');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
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
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
				<i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> 
				<i class="fa fa-plus-circle"></i> 添加商家</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="30" class="text-center">ID</th>
					<th width="75" class="text-center">商家图片</th>
					<th>商家名称</th>
					<th width="100">电话</th>
					<th width="260">地址</th>
					<th width="160">商家类型</th>
					<th width="80" class="text-center">推荐</th>
					<th width="160">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="6"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?php echo $row['id']?></td>
					<td class="text-center">
						<a href="<?=$this->url('action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">
							<img src="<?php echo $row['ref_img_bg']? $this->img($row['ref_img_bg'], '160x160') : $this->img($row['thumb'], '160x160')?>" class="img-thumbnail"></a>
					</td>

					<td>
						[<?=$row['cate_name']?>]
						<a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['name'], $this->_request->q)?>
						</a>
					</td>
					<td><?=$row['tel']?></td>
					<td><?=$row['addr']?></td>
					<td>
						<?php foreach ($this->clotypes as $key=>$val) {?>
							<?php if($key == $row['is_special']) echo $val['name']?>
						<?php }?>
					</td>
					<td class="text-center">
						<a href="<?=$this->url('action=toggle_status&t=is_rec&id='.$row['id'].'&v='.$row['is_rec'].'&ref='.$this->_request->url)?>">
						<?=$row['is_rec'] ? '<span class="label label-success">是</span>' : '<span class="label label-default">否</span>'?>
						</a></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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
