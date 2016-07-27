<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle($this->_request->g ? $this->_request->g : '兑换名单');
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
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="75" class="text-center">兑换商品</th>
					<th>商品信息</th>
					<th width="300">收货地址</th>
					<th width="90">会员</th>
					<th width="90">状态</th>
					<th width="160">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="8"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td class="text-center">
						<a href="<?=$this->url('action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">
							<img src="<?=$this->img($row['thumb'], '160x160')?>" class="img-thumbnail"></a>
					</td>
					<td><a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$this->highlight($row['title'], $this->_request->q)?>
						</a><br>
						价格：<?=$row['market_price']?>元<br>
						积分：<?=$row['points']?>点
					</td>
					<td>
						地址：<?=$row['area_text']?> <?=$row['address']?> (ZIP:<?=$row['zipcode']?>)<br>
						收货人：<?=$row['consignee']?> (电话：<?=$row['phone']?>)
					</td>
					<td><?=$row['nickname']?></td>
					<td><a href="<?=$this->url('action=change&m=status&id='.$row['id'].'&ref='.$this->_request->url)?>">
						<?=$row['status'] ? '<span class="label label-success">已发货</span>' : '<span class="label label-default">未发货</span>'?>
						</a></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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
