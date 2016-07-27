<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle('SKU 管理');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?><small>(共<?=$this->datalist->total()?>条记录)</small></h1>

		<form method="get" action="<?=$this->url('action=sku')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入货号或SKU查询" />
				<?php if ($this->_request->q) { ?>
				<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
				<?php } ?>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<form method="post" action="<?=$this->url('action=batch')?>" data-plugin="chk-group" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<button type="submit" name="act" value="update_sku" class="btn btn-default btn-sm"> <i class="fa fa-refresh"></i> 更新设置</button>
		</div>

		<table width="100%" class="table table-striped">
			<thead>
				<tr>
					<th width="20" class="text-right"><input type="checkbox" role="chk-all" /></th>
					<th width="70" class="text-center">商品</th>
					<th></th>
					<th width="240">SKU编号</th>
					<?php if ($this->_request->show_cost) { ?>
					<th width="160">供货价</th>
					<?php } ?>
					<!-- <th width="160">销售价</th> -->
					<th width="120">当前库存</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="9"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td class="text-center">
						<input type="hidden" name="data[<?=$row['id']?>][goods_id]" value="<?=$row['goods_id']?>" />
						<input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td class="text-center">
						<a href="<?=$this->url('action=edit&id='.$row['goods_id'].'&ref='.$this->_request->url)?>">
							<img src="<?=$this->img($row['thumb']?$row['thumb']:$row['goods_thumb'], '160x160')?>" class="img-thumbnail"></a>
					</td>
					<td><a href="<?=$this->url('action=edit&id='.$row['goods_id'])?>">
						<?=$row['title']?></a>
						<div style="margin-top:8px">
							货号：<?=$row['goods_code']?><br />
							规格：<?=$row['spec']?$row['spec']:'N/A'?>
						</div>
					</td>
					<td style="padding-right: 20px"><input name="data[<?=$row['id']?>][code]" value="<?=$row['code']?>" class="form-control input-sm" /></td>
					<?php if ($this->_request->show_cost) { ?>
					<td style="padding-right: 20px"><div class="input-group input-group-sm">
							<input name="data[<?=$row['id']?>][cost_price]" value="<?=$row['cost_price']?>" class="form-control" />
							<span class="input-group-addon">元</span>
						</div></td>
					<?php } ?>
					<!-- <td style="padding-right: 20px"><div class="input-group input-group-sm">
							<input name="data[<?=$row['id']?>][selling_price]" value="<?=$row['selling_price']?>" class="form-control" />
							<span class="input-group-addon">元</span>
						</div></td> -->
					<td><div class="input-group input-group-sm">
						<input name="data[<?=$row['id']?>][quantity]" value="<?=$row['quantity']?>" class="form-control" />
						<span class="input-group-addon">
							<?=$row['package_quantity'] > 0 ? $row['package_lot_unit'] : $row['unit']?>
						</span>
						</div></td>
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