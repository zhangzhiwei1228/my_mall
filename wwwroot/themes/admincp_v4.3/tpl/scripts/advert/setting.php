<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '广告设置';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
$this->paths[] = array(
	'name' => $this->data['name'],
	'url' => '&',
);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>

	<div class="well" style="margin-top:15px">
		<div class="row">
			<div class="col-sm-4">
				<label>广告位名称:</label>
				<?=$this->data['name']?> [<a href="<?=$this->url('&action=edit&ref='.$this->_request->url)?>">编辑</a>]
			</div>
			<div class="col-sm-4">
				<label>同时显示:</label>
				<strong> <?=$this->data['limit']?> </strong> 组
			</div>
			<div class="col-sm-4">
				<label>广告尺寸:</label>
				<?php if ($this->data['withh'] && $this->data['height']) { echo $this->data['withh'].' x '.$this->data['height'].' (PX)'; } else { echo '不限'; } ?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<label>广告位代码:</label>
				<?=$this->data['code']?>
			</div>
			<div class="col-sm-4">
				<label>创建时间:</label>
				<?=date(DATETIME_FORMAT, $this->data['create_time'])?>
			</div>
		</div>
	</div>

	<form class="form-batch form-inline" method="post" action="<?=$this->url('controller=advert_element&action=batch')?>">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<button type="submit" name="act" value="update" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('controller=advert_element&action=add&aid='.$this->data['id'].'&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 添加广告</a>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th>广告主题</th>
					<th width="80" class="text-center">展现形式</th>
					<th width="60" class="text-center hidden-xs">展示</th>
					<th width="60" class="text-center hidden-xs">点击</th>
					<th width="200" class="hidden-xs">投放时间</th>
					<th width="50">状态</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody data-plugin="dragsort">
				<?php if (count($this->elements)) { foreach ($this->elements as $row) { ?>
				<tr>
					<td><a href="<?=$this->url('controller=advert_element&action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>"> <?=$row['theme']?> </a></td>
					<td class="text-center">
					<input type="hidden" name="data[<?=$row['id']?>][rank]" value="<?=$row['rank']?>" />
					<?php switch ($row['type']) {
						case 'image': echo '图片'; break;
						case 'text': echo '文本'; break;
						case 'html': echo 'HTML'; break;
					} ?></td>
					<td class="text-center hidden-xs"><?=$row['display_num']?></td>
					<td class="text-center hidden-xs"><?=$row['clicks_num']?></td>
					<td class="hidden-xs"><?=date(DATE_FORMAT, $row['start_time'])?> 至 <?=$row['end_time'] ? date(DATE_FORMAT, $row['end_time']) : '永久'?></td>
					<td valign="top"><a href="<?=$this->url('controller=advert_element&action=change&m=is_enabled&id='.$row['id'].'&ref='.$this->_request->url)?>"> <?=$row['is_enabled'] ? '<font class="label label-success">启用</font>' : '<font class="label label-danger">禁用</font>'?> </a></td>
					<td><a href="<?=$this->url('controller=advert_element&action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('controller=advert_element&action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
				</tr>
				<?php } } else { echo '<tr><td colspan="8" class="text-center">找不到相关信息</td></tr>'; } ?>
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
