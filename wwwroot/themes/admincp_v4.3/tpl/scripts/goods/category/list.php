<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '商品分类';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="alert alert-warning">
	<strong>小提示！</strong> 
	当发现分类中显示的商品数量与实际不符时，请点击这里<a href="<?=$this->url('action=goods_count')?>">重新计算商品数量</a><br />
	请每次对分类设置完成后，点击这里<a href="<?=$this->url('action=make_cache')?>">更新分类缓存</a>。<br />
</div>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	
	<form method="post" action="<?=$this->url('action=batch')?>" class="form-inline sui-datalist">
		<div class="sui-toolbar">
			<button type="submit" name="act" value="update" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
			<div class="btn-group btn-group-sm">
			<button type="submit" name="act" value="enabled" class="btn btn-default"> <i class="fa fa-check-circle-o"></i> 启用</button>
			<button type="submit" name="act" value="disabled" class="btn btn-default"> <i class="fa fa-times-circle-o"></i> 禁用</button>
			</div>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 创建类目</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
			<tr>
				<th width="30"><input type="checkbox" role="chk-all" /></th>
				<th width="30">#ID</th>
				<th>分类名称</th>
				<th width="120" align="center">绑定类型</th>
				<th width="60" align="center">商品数</th>
				<th width="40">状态</th>
				<th width="160">更新时间</th>
				<th width="140">操作</th>
			</tr>
			</thead>
			<tbody data-plugin="dragsort">
				<?php 
				if (!$this->_request->t && $this->_request->cid) { $_SESSION[__CLASS__][$this->_request->cid] = 1; } 
				else { unset($_SESSION[__CLASS__][$this->_request->cid]); }
				if ($this->_request->isAjax()) {
				$this->fragmentStart(); }
				foreach ($this->datalist as $row) { 
					$paths = explode(',', $row['path_ids']); $class = array();
					foreach ($paths as $item) { $class[] = 'cat_'.$item; }
					$class = implode(' ', $class); 
				?>
				<tr class="<?=$class?>" data-id="<?=$row['id']?>">
					<td><input type="checkbox" name="ids[]" value="<?=$row['id']?>" role="chk-item" /></td>
					<td align="center"><?=$row['id']?></td>
					<td><span style="color:#ccc;"> <?=$row['level'] > 1 ? (str_repeat('　&nbsp;&nbsp;&nbsp;　', $row['level'] - 2) . '└──') : null?> </span> <?php if ($row['childs_num']) { ?> <a href="javascript:" id="btn-<?=$row['id']?>" onclick="$.openNote(this, <?=(int)$row['id']?>)" style="color:#888"> <i class="fa fa-plus-square-o"></i></a> <?php } else { ?> <a href="javascript:" id="btn-<?=$row['id']?>" style="color:#888"><i class="fa fa-square-o"></i></a> <?php } ?> <?php if (isset($_SESSION[__CLASS__][$row['id']])) { ?> 
					<script type="text/javascript">$('#btn-<?=$row['id']?>').trigger("click");</script> 
					<?php } ?>&nbsp;
						<input name="data[<?=$row['id']?>][name]" value="<?=$row['name']?>" class="form-control input-xs" style="width:150px;" /></td>
					<td align="center">
						<select name="data[<?=$row['id']?>][type_id]" id="type_id_<?=$row['id']?>" onkeydown="et(<?=$row['id']?>)" onchange="et(<?=$row['id']?>)" class="form-control input-xs" style="width:100px">
							<option value="0">未绑定</option>
							<?php foreach ($this->types as $col) { ?>
							<option value="<?=$col['id']?>" <?php if ($row['type_id'] == $col['id']) echo 'selected'?>><?=$col['name']?></option>
							<?php } ?>
						</select>
					</td>
					<td align="center"><a href="<?=$this->url('controller=goods&action=list&t=onsale&cid='.$row['id'])?>"><?=$row['goods_num']?></a></td>
					<td><?php if ($row['is_enabled'] == 1) { ?>
						<a href="<?=$this->url('action=change&m=is_enabled&id=' . $row['id'])?>" class="label label-success">启用</a>
						<?php } elseif ($row['is_enabled'] == 0) { ?>
						<a href="<?=$this->url('action=change&m=is_enabled&id=' . $row['id'])?>" class="label label-danger">禁用</a>
						<?php } ?></td>
					<td><?=$row['update_time'] ? date(DATETIME_FORMAT, $row['update_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=add&pid=' . $row['id'])?>">加子类</a> | 
						<a href="<?=$this->url('action=edit&id=' . $row['id'])?>">编辑</a> | 
						<?php if ($row['is_locked']) { ?>
						<span style="color:#888">锁定</span>
						<?php } else { ?>
						<a href="<?=$this->url('action=delete&id=' . $row['id'])?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
						<?php } ?>
					</td>
				</tr>
				<?php } if ($this->_request->isAjax()) { $this->fragmentEnd(); } ?>
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
