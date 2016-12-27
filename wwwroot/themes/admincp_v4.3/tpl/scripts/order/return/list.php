<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '退款申请';
$this->head()->setTitle($this->title);
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
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20"><input type="checkbox" role="chk-all" /></th>
					<th width="70">购买订单号</th>
					<th width="70">产品图片</th>
					<th width="200">产品信息</th>
					<th>退款原因</th>
					<th width="80" class="text-center">退款金额</th>
					<th width="100" class="text-center">申请人</th>
					<th width="130">申请时间</th>
					<th width="80">状态</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr><td colspan="8" class="notfound">没有找到符合条件的信息。</td></tr>
				<?php } else { foreach ($this->datalist as $row) {?>
				<tr>
					<td><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td valign="top">
						<a href="<?=$this->url('controller=order&action=detail&id='.$row['order_id'])?>" target="_blank">
							<?php echo $row['oCode']?>
						</a>
					</td>
					<td valign="top">
						<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['goods_id'])?>" target="_blank">
							<img src="<?=$this->img($row['thumb'], '100x100')?>" class="img-thumbnail" /></a>
					</td>

					<td>
						<div style="overflow:hidden">
							<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['goods_id'])?>" target="_blank"><?=$row['title']?></a>
							<div style="font-size:11px; color:#666;"><?=$row['buychoose'] ? $row['buychoose'] : ''?></div>
						</div>
					</td>
					<td valign="top">
						退单号：<a href="<?=$this->url('action=detail&id='.$row['id'])?>">#RF-<?=$row['code']?></a>
						<?php if ($row['reason']) { ?>
						<div class="well well-sm">
							<p><?=$row['reason']?></p>
							<footer><?=nl2br($row['description'])?></footer>
						</div>
						<?php } ?>
					</td>
					<td valign="top" class="text-center"><?=$this->currency($row['refund_amount'])?></td>
					<td valign="top" class="text-center"><?=$row['username']?></td>
					<td valign="top"><?=date(DATETIME_FORMAT, $row['create_time'])?></td>
					<td valign="top">
						<?php switch ($row['status']) {
							case 0: echo '<span class="label label-default">待处理</span>'; break;	
							case 1: echo '<span class="label label-danger">拒绝</span>'; break;	
							case 2: echo '<span class="label label-warning">退换中</span>'; break;	
							case 3: echo '<span class="label label-success">已完成</span>'; break;	
						}?>
					</td>
					<td valign="top">
						<?php if ($row['status'] == 0) { ?>
						<a href="<?=$this->url('action=accept&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要同意退款吗?')">同意</a>
						<a href="<?=$this->url('action=refuse&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要拒绝退款吗?')">拒绝</a>
						<br />
						<?php } elseif ($row['status'] == 2) { ?>
						<a href="<?=$this->url('action=refund&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return cofirm('确定要退款给卖家吗？')">完成</a>
						<?php } ?>
						<a href="<?=$this->url('action=detail&id='.$row['id'].'&ref='.$this->_request->url)?>">详情</a>
					</td>
					<?php } ?>
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
</div>