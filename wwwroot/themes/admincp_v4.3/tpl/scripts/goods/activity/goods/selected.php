<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<div class="sui-toolbar">
	<div class="pagination pagination-sm pull-right">
	<?=$this->paginator($this->datalist)->getAjaxBar('loadSelectedPage')->getMiniBar()?>
	</div>
	<strong>已选商品</strong>
	<div class="input-group input-group-sm" style="margin-top:8px">
		<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control" placeholder="输入关键词搜索" />
		<span class="input-group-btn">
			<button type="button" class="btn btn-default" onclick="loadSelectedPage(1)">搜索</button>
		</span>
	</div>
</div>
<table class="table" width="100%" style="margin:0">
	<tbody id="goods-list">
	<?php if (count($this->datalist)) {	foreach ($this->datalist as $row) { ?>
		<tr id="item-<?=$row['id']?>" style="border-bottom:dotted 1px #ddd">
			<td valign="top" align="center" width="70" style="height:80px; overflow:hidden;">
			<div class="thumb" style="width:65px; height:65px; margin:4px;">
				<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>" target="_blank">
					<img src="<?=$this->img($row['thumb'], '160x160')?>" width="65" /></a>
			</div>
			</td>
			<td valign="top">
			<?php if ($this->_request->type == 'discount') {
				$k = $row['id'];
				$priceLabel = $row['price_label'] ? $row['price_label'] : '促销价';
				$discount = $row['discount'] ? $row['discount'] : 10;
				$sellingPrice = $row['selling_price'];
				$promotionPrice = $sellingPrice * ($discount/10);
				$qtyLimit = $row['qty_limit'] ? $row['qty_limit'] : 0;
			?>
			<div class="JS_Item pull-right" style="margin-left:15px; position:relative; width:140px;">
				<input type="hidden" value="<?=$sellingPrice?>" class="JS_SellingPrice" />
				标签：<input type="text" name="setting[goods][<?=$k?>][price_label]" value="<?=$priceLabel?>" class="JS_PriceLabel" style="width:60px; padding:0 5px;" /><br />
				折扣：<input type="text" name="setting[goods][<?=$k?>][discount]" value="<?=$discount?>" class="JS_Discount" style="width:60px; padding:0 5px;"/> 折<br />
				限购：<input type="text" name="setting[goods][<?=$k?>][qty_limit]" value="<?=$qtyLimit?>" class="JS_QtyLimit" style="width:60px; padding:0 5px;"/> 件
				</div>
				<!--<p style="position:absolute; top:0; right:0; width:60px;">
					折后价格：
					<span class="JS_PromotionPrice" style="color:#C00; font-weight:bold">&yen;<?=$promotionPrice?></span>
				</p>-->
			</div>
			<?php } elseif ($this->_request->type == 'kill') { 
				$k = $row['id'];
				$priceLabel = $row['price_label'] ? $row['price_label'] : '秒杀';
				$killPrice = $row['kill_price'] ? $row['kill_price'] : 1;
				$qtyLimit = $row['qty_limit'] ? $row['qty_limit'] : 0;
			?>
			<div class="JS_Item pull-right" style="margin-left:15px; position:relative; width:140px;">
				<input type="hidden" value="<?=$sellingPrice?>" class="JS_SellingPrice" />
				标签：<input type="text" name="setting[goods][<?=$k?>][price_label]" value="<?=$priceLabel?>" class="JS_PriceLabel" style="width:50px; padding:0 5px;" /><br />
				价格：<input type="text" name="setting[goods][<?=$k?>][kill_price]" value="<?=$killPrice?>" class="JS_Discount" style="width:50px; padding:0 5px;"/> 元<br />
				限购：<input type="text" name="setting[goods][<?=$k?>][qty_limit]" value="<?=$qtyLimit?>" class="JS_QtyLimit" style="width:50px; padding:0 5px;"/> 件
				<!--<p style="position:absolute; top:0; right:0; width:50px;">
					折后价格：
					<span class="JS_PromotionPrice" style="color:#C00; font-weight:bold">&yen;<?=$promotionPrice?></span>
				</p>-->
			</div>
			<?php } ?>
		<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>" target="_blank">
			<?=$this->highlight($row['title'], $this->_request->q2)?></a><br>
				货号：#<?=$row['code']?> (ID:<?=$row['id']?>)<br>
				价格：<span class="highlight">&yen;<?=$row['price']?></span>
			</td>
			<td align="right" width="50"><a href="javascript:void(0); selected('<?=$row['id']?>');">移除</a></td>
		</tr>
	<?php } } else { echo '<tr><td colspan="2" align="center">没有选中的商品</td></tr>'; }?>
	</tbody>
</table>