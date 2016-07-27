<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '区域设置';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist form-inline">
		<div class="sui-toolbar">
			<button type="submit" name="act" value="update" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 添加区域</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="40">#ID</th>
					<th>区名</th>
					<th width="100" class="hidden-xs">邮编</th>
					<th width="160" class="hidden-xs">更新时间</th>
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
				<tr class="<?=$class?>">
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><?=$row['id']?></td>
					<td><span style="color:#ccc;"> <?=$row['level'] > 1 ? (str_repeat('　&nbsp;&nbsp;&nbsp;　', $row['level'] - 2) . '└──') : null?> </span> <?php if ($row['childs_num']) { ?> <a href="javascript:" id="btn-<?=$row['id']?>" onclick="$.openNote(this, <?=(int)$row['id']?>)" style="color:#888"> <i class="fa fa-plus-square-o"></i></a> <?php } else { ?> <a href="javascript:" id="btn-<?=$row['id']?>" style="color:#888"><i class="fa fa-square-o"></i></a> <?php } ?> <?php if (isset($_SESSION[__CLASS__][$row['id']])) { ?> 
							<script type="text/javascript">$('#btn-<?=$row['id']?>').trigger("click");</script> 
							<?php } ?>&nbsp;
						<input name="data[<?=$row['id']?>][name]" value="<?=$row['name']?>" class="form-control input-xs" style="width:160px;" />
					</td>
					<td class="hidden-xs"><input name="data[<?=$row['id']?>][zipcode]" value="<?=$row['zipcode'] ? $row['zipcode'] : ''?>" class="form-control input-xs" <?php if ($row['level'] <= 2) { echo 'readonly'; } ?> /></td>
					<td class="hidden-xs"><?=$row['update_time'] ? date(DATETIME_FORMAT, $row['update_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=add&pid=' . $row['id'])?>">加子域</a> | <a href="<?=$this->url('action=edit&id=' . $row['id'])?>">编辑</a> | <a href="<?=$this->url('action=delete&id=' . $row['id'])?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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