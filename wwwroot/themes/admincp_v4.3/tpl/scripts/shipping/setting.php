<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = $this->data['name'].' - 物流设置';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="well">
	<h5><?=$this->head()->getTitle()?></h5>
	<?php if ($this->data['logo']) { ?>
	<div class="pull-right"><a class="thumbnail">
	<img src="<?=$this->baseUrl($this->data['logo'])?>" width="100" /></a></div>
	<?php } ?>
	<div>
		<label>名称：</label>
		<?=$this->data['name']?>
		<a href="<?=$this->url('action=edit&id='.$this->data['id'].'&ref='.$this->_request->ref)?>">编辑</a>
	</div>
	<div>
		<label>减免：</label>
		<?=$this->data['discount']?>
	</div>
	<div>
		<label>简介：</label>
		<?=$this->data['description']?>
	</div>
</div>
<div class="sui-box">
	<div class="sui-page-header"><h1>运费设置</h1></div>
	<div class="sui-toolbar">
		<ul class="pagination pagination-sm pull-right">
			<?=$this->paginator($this->datalist)?>
		</ul>
		<button type="submit" name="act" value="update" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
		<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> 
			<i class="fa fa-trash-o"></i> 删除</button>
		<a class="btn btn-default btn-sm" href="<?=$this->url('controller=shipping_freight&action=add&sid='.$this->_request->id.'&ref='.$this->_request->url)?>"> 
			<i class="fa fa-plus-circle"></i> 添加区域运费</a>
	</div>
	<table id="_setting" class="table table-bordered" data-plugin="chk-group">
		<thead>
			<tr>
				<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
				<th>目的地</th>
				<th width="300">运费</th>
				<th width="100">配送时间</th>
				<th width="80">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!count($this->datalist)) { ?>
			<tr align="center">
				<td colspan="4"><div class="notfound">找不到相关信息</div></td>
			</tr>
			<?php } else { foreach ($this->datalist as $row) { ?>
			<tr>
				<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
				<td><?=$row->parseDest()?></td>
				<td><div class="input-group input-group-sm">
						<span class="input-group-addon">首重：</span>
						<input type="text" class="form-control" name="data[<?=$row['id']?>][first_weight]" value="<?=$row['first_weight']?>" />
						<span class="input-group-addon">公斤</span>
						<input type="text" class="form-control" name="data[<?=$row['id']?>][first_freight]" value="<?=$row['first_freight']?>" />
						<span class="input-group-addon">元</span></div>
					
					<div class="input-group input-group-sm">
						<span class="input-group-addon">继重：</span>
						<input type="text" class="form-control" name="data[<?=$row['id']?>][second_weight]" value="<?=$row['second_weight']?>" />
						<span class="input-group-addon">公斤</span>
						<input type="text" class="form-control" name="data[<?=$row['id']?>][second_freight]" value="<?=$row['second_freight']?>" />
						<span class="input-group-addon">元</span></div>
					</td>
				<td><div class="input-group input-group-sm">
				<input type="text" class="form-control" name="data[<?=$row['id']?>][estimated_delivery]" value="<?=$row['estimated_delivery']?>" />
					<span class="input-group-addon">天</span></div></td>
				<td><a href="<?=$this->url('controller=shipping_freight&action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">编辑</a> 
					<a href="<?=$this->url('controller=shipping_freight&action=delete&id='.$row['id'].'&ref='.$this->_request->url)?>">删除</a></td>
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
</div>