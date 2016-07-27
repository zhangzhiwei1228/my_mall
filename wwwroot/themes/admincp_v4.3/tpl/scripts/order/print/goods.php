<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('配货单');
$this->setLayout(false);
?>

<h1><?=$this->head()->getTitle()?>  #TS-<?=$this->data['code']?></h1>

<table>
	<tr>
		<td>订单号：</td>
		<td>#TS-<?=$this->data['code']?></td>
	</tr>
	<tr>
		<td>买家ID：</td>
		<td><?=$this->data->buyer['username']?></td>
	</tr>
	<tr>
		<td>收货人：</td>
		<td><?=$this->data['consignee']?></td>
	</tr>
	<tr>
		<td>联系电话：</td>
		<td><?=$this->data['phone']?></td>
	</tr>
	<tr>
		<td>收货地址：</td>
		<td><?=$this->data['area_text']?> <?=$this->data['address']?></td>
	</tr>
	<tr>
		<td>邮编：</td>
		<td><?=$this->data['zipcode']?></td>
	</tr>
</table>

<h3>商品明细</h3>
<table width="100%" border="1" cellpadding="6" cellspacing="0">
	<tr>
		<th align="center" width="40">序号</th>
		<th align="center" width="160">商品编码</th>
		<th align="left">商品名称/规格</th>
		<th align="center" width="60">数量</th>
	</tr>
	<?php
	$goods = $this->data->goods;
	foreach ($goods as $col) { $i++; ?>
	<tr>
		<td align="center"><?=$i?></td>
		<td align="center"><?=$col['code']?></td>
		<td align="left"><?=$col['title']?> <?=$col['spec']?></td>
		<td align="center"><?=$col['purchase_quantity']?> <span class="unit"><?=$col['unit']?></span></td>
	</tr>
	<?php } ?>
</table>
