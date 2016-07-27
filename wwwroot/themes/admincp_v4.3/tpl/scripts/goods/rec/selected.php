<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<div class="sui-toolbar">
	<div class="pagination pagination-sm pull-right"><?=$this->paginator($this->datalist)->getAjaxBar('gotopage2')->getMiniBar()?></div>
	<strong>已选商品</strong>
		<div class="input-group input-group-sm" style="margin-top:8px">
				<input type="text" name="q2" value="<?=$this->_request->q2?>" class="form-control" placeholder="输入关键词搜索" />
				<span class="input-group-btn">
						<button type="button" class="btn btn-default" onclick="gotopage2(1)">搜索</button>
				</span>
		</div>
</div>

<table class="table" width="100%" style="margin:0">
		<tbody id="goods-list">
		<?php foreach ($this->datalist as $row) { ?>
				<tr id="item-<?=$row['id']?>" style="border-bottom:dotted 1px #ddd">
						<td valign="top" align="center" width="70" style="height:80px; overflow:hidden;">
						<div class="thumb">
								<a id="img_<?=$row['id']?>" href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>" target="_blank"><img src="<?=$this->img($row['thumb'], '160x160')?>" width="65" /></a>
						</div>
						</td>
						<td valign="top"><a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>" target="_blank"><?=$this->highlight($row['title'], $this->_request->q)?></a><br>
								货号：#<?=$row['code']?> (ID:<?=$row['id']?>)<br>
								价格：<span class="highlight"><?=$this->currency($row['price'])?></span>
						</td>
			<!--
			<td class="form-inline">
				<div class="form-group">
					重定价：<input type="text" name="setting[<?=$row['id']?>][price]" value="<?=$row['price']?>" class="form-control input-xs" /> 元,
				</div>
				<div class="form-group">
					限购：<input type="text" name="setting[<?=$row['id']?>][qty_limit]" value="<?=$row['qty_limit'] ? $row['qty_limit'] : 1?>" class="form-control input-xs" />件
				</div>
			</td>-->
						<td align="center" width="50"><a href="javascript:void(0); selected('<?=$row['id']?>');">移除</a></td>
				</tr>
		<?php } ?>
		</tbody>
</table>