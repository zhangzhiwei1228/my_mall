<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
switch ($this->_request->view) {
	case 'all': $this->title = '全部订单'; break;
	case 'awaiting_payment': $this->title = '待付款订单'; break;	
	case 'shiped': $this->title = '待发货订单'; break;	
	case 'pending_receipt': $this->title = '已发货商品'; break;	
	case 'completed': $this->title = '已完成订单'; break;	
	case 'closed': $this->title = '已关闭订单'; break;
	default: $this->title = '订单管理'; break;
}
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?><small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<form method="get" action="<?=$this->url('action=search')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入订单号查询" />
				<?php if ($this->_request->search) { ?>
				<a href="<?=$this->url('action=list&view='.$this->_request->view)?>" class="fa fa-minus-circle"></a>
				<?php } ?>

			</div>

			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
			<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#adv-search-box" title="高级搜索"><i class="fa fa-external-link"></i></button>
		</form>
	</div>
	<form method="post" action="<?=$this->url('action=readexcel')?>" class="file_submit" enctype="multipart/form-data">
		<input type="file" value="" name="file_excle" id="file_excle" />
		<a class="btn btn-default btn-sm import" href="javascript:;">
			<i class="glyphicon glyphicon-import"></i> 导入
		</a>
	</form>
	<form method="post" action="<?=$this->url('action=batch')?>" data-plugin="chk-group" class="sui-datalist">
		<div class="sui-toolbar">
			<?php if($this->_request->view == 'shiped') {?>
			<a class="btn btn-default btn-sm export" href="javascript:;">
				<i class="glyphicon glyphicon-export"></i> 导出
			</a>
			<?php }?>
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<?php if ($this->_request->view == 'closed') { ?>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选订单吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<?php } elseif ($this->_request->view == 'awaiting_payment') { ?>
			<button type="submit" name="act" value="cancel" class="btn btn-default btn-sm" onclick="return confirm('确定要取消所选订单吗?');"> <i class="fa fa-times-circle-o"></i> 取消订单</button>
			<?php } ?>
		</div>
		<table class="table">
			<thead>
				<tr>
					<th width="20"><input type="checkbox" role="chk-all" /></th>
					<th>产品信息</th>
					<th width="80">成交价</th>
					<th width="60">数量</th>
					<th width="200">买家/收货地址</th>
					<th width="90" class="text-right">商品总额</th>
					<th width="80" class="text-right">手续费</th>
					<th width="80" class="text-right">运费</th>
					<th width="90" class="text-right">实付金额</th>
					<th width="70" class="text-center">状态</th>
					<th width="45" class="text-center">操作</th>
				</tr>
			</thead>
		</table>

		<?php
		if (!count($this->datalist)) {
			echo '<div class="notfound">没有找到符合条件的信息。</div>';
		} else { 
			foreach ($this->datalist as $row) { 
		?>

		<table class="table">
			<tbody>
				<tr class="warning">
					<td colspan="7">
						<input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" style="margin:0" />
						#<?=$row['code']?> [<a href="<?=$this->url('action=detail&id='.$row['id'].'&ref='.$this->_request->url)?>">查看</a>]
						<?php if ($row['joined_activity']) { ?>
						<span style="margin-left:30px;">
						【已参加活动：<strong style="color:#C00"><?=$row['joined_activity']?></strong>】
						</span>
						<?php } ?>

						<?php if ($row['coupon_amount']>0) { ?>
						<span style="margin-left:30px;">
							【已使用<strong style="color:#C00">优惠券</strong>抵扣 <?=$row['coupon_amount']?>】
						</span>
						<?php } ?>
					</td>
					<td colspan="6" class="text-right">
						有效期：<strong><?=$row['expiry_time'] ? $this->countdown($row['expiry_time']) : 'N/A'?></strong>, &nbsp;&nbsp;
						下单时间: <strong><?=date(DATETIME_FORMAT, $row['create_time'])?></strong>
					</td>
				</tr>

				<?php
					$i = 0;
					$rowspan = $row->goods->total();
					foreach ($row->goods as $col) { $i += 1;
				?>
				<tr>
					<td></td>
					<td valign="top" width="70">
						<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$col['goods_id'])?>" target="_blank">
							<img src="<?=$this->baseUrl($col['thumb'])?>" class="img-thumbnail" width="70" /></a>
					</td>
					<td>
						<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$col['goods_id'])?>" target="_blank"><?=$col['title']?></a>
						<div>#<?=$col['code']?></div>
						<div><?=$col['spec'] ? $col['spec'] : ''?></div>
					</td>
					<td width="80" valign="top">
						<?php if ($col['final_price']!=$col['selling_price']) { ?>
						<del><?=$this->currency($col['selling_price'])?></del><br><?=$this->currency($col['final_price'])?>
						<?php } else { ?>
						<?=$this->currency($col['selling_price'])?>
						<?php } ?>
						<?php if ($col['promotion']) { ?>
						<span class="label label-danger"><?=$col['promotion']?></span>
						<?php } ?>

						<?php if ($col['is_return'] == 1) { ?>
						<p style="color:red">申请退款</p>
						[<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
						<?php } elseif ($col['is_return'] == 2) { ?>
						<p style="color:red">已退款</p>
						[<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
						<?php } elseif ($col['is_return'] == 3) { ?>
						<p style="color:red">拒绝退款</p>
						[<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
						<?php } ?>

					</td>
					<td width="60" valign="top">
						<?=$col['purchase_quantity']?><span class="unit"><?=$col['unit']?></span>
					</td>
					<?php if ($i == 1) { ?>
					<td width="200" valign="top" style="border-left:solid 1px #ddd;" rowspan="<?=$rowspan?>">
						<?php if ($row['remark']) { ?>
						<a class="glyphicon glyphicon-flag pull-right" data-toggle="tooltip" data-original-title="备注：<?=$row['remark']?>"></a>
						<?php } ?>
						<a href="<?=$this->url('controller=user&action=detail&id='.$row['buyer_id'])?>" title="<?=$row['buyer_account']?>" target="_blank"><?=$row['buyer_id'] ? $row['buyer_account'] : '非会员购买'?></a>
						<div>
							<?=$row['consignee']?> <br />
							<?=$row['area_text'].' '.$row['address']?>
						</div>
					</td>
					<td width="90" valign="top" class="text-right" style="border-left:solid 1px #ddd;" rowspan="<?=$rowspan?>">
						<?php if ($row['total_save']>0) { ?>
						<del><?=$this->currency($row['total_amount'])?></del><br>
						<?=$this->currency($row['total_amount']-$row['total_save']>0?$row['total_amount']-$row['total_save']:0)?>
						<?php } else { ?>
						<?=$this->currency($row['total_amount'])?>
						<?php } ?>
					</td>
					<td width="80" valign="top" class="text-right" style="border-left:solid 1px #ddd;" rowspan="<?=$rowspan?>">
						<?=$this->currency($row['total_fee'])?>
						<div><strong style="color:#069"><?=$row['payment_name'] ? $row['payment_name'] : ''?></strong></div>
					</td>
					<td width="80" valign="top" class="text-right" style="border-left:solid 1px #ddd;" rowspan="<?=$rowspan?>">
						<?=$this->currency($row['total_freight'])?>
						<div><strong style="color:#069"><?=$row['shipping_name'] ? $row['shipping_name'] : ''?></strong></div>
					</td>
					<td width="90" valign="top" class="text-right" style="border-left:solid 1px #ddd;" rowspan="<?=$rowspan?>">
						<?php if ($row['adjustment_amount'] != 0) { ?>
						<del class="currency highlight">
						<?=$this->currency($row['total_pay_amount']-$row['adjustment_amount'])?>
						</del><br />
						<span style="background:#06C; color:#fff; padding:0px 2px; margin-right:3px;">改</span>
						<?php } ?>
						<span class="currency highlight">
						<?=$this->currency($row['total_pay_amount'])?>
						</span>
					</td>
					<td width="70" valign="top" class="text-center" style="border-left:solid 1px #ddd" rowspan="<?=$rowspan?>">
						<div>
							<?php switch($row['status']) { 
							case 0: echo '<span class="label label-danger">关闭</span>'; break; 
							case 1: echo '<span class="label label-warning">待付款</span>'; break; 
							case 2: echo '<span class="label label-warning">待发货</span>'; break; 
							case 3: echo '<span class="label label-warning">待签收</span>'; break; 
							case 4: echo '<span class="label label-success">完成</span>'; break; 
						}?>
						</div>
						<?php if ($row['expiry_time'] != 0 && $row['expiry_time'] < time() && $row['status'] != 4) { ?>
						<div><font color="red">
							<?php 
							if (!$row['pay_time']) { echo '付款超时'; } 
							elseif (!$row['delivery_time']) { echo '发货超时'; } 
							elseif (!$row['confirm_time']) { echo '签收超时'; }
							?>
						</div>
						<?php } elseif ($row['expiry_time'] == 0 && $row['status'] != 4 && $row['status'] != 0) { ?>
						<div><font color="red">冻结</font></div>
						<?php } ?>
						<?php if ($row['status'] == 1) { ?>
						<div><a href="<?=$this->url('action=change_pay&id='.$row['id'].'&ref='.$this->_request->url)?>" style="display:none">已付款</a></div>
						<?php } ?>
					</td>
					<td width="45" valign="top" class="text-center" style="border-left:solid 1px #ddd" rowspan="<?=$rowspan?>">
						<a href="<?=$this->url('action=detail&id='.$row['id'].'&ref='.$this->_request->url)?>">查看</a><br />
						<?php if ($row['status'] == 0) { ?>
						<a href="<?=$this->url('action=delete&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
						<?php } elseif ($row['status'] == 1) { ?>
						<a href="<?=$this->url('action=cancel&id='.$row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要取消这笔订单吗?')">取消</a>
						<?php } ?></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } } ?>

		<div class="sui-toolbar"> 
			<script type="text/javascript">
				var toolbar = $('.sui-toolbar').clone();
				document.write(toolbar.html());
			</script> 
		</div>
	</form>

	<!-- Modal -->
	<div class="modal fade" id="adv-search-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px">
			<form method="get" class="modal-content">
				<input type="hidden" name="search" value="1" />
				<input type="hidden" name="page" value="1" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">高级搜索</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						订单号：
						<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control" />
					</div>
					<div class="form-group">
						买家ID：
						<input type="text" name="buyer" value="<?=$this->_request->buyer?>" class="form-control" />
					</div>
					<div class="form-group">
						收件人姓名：
						<input type="text" name="consignee" value="<?=$this->_request->consignee?>" class="form-control" />
					</div>
					<div class="form-group">
						下单时间：
						<div class="input-group">
							<input type="text" name="start_time" value="<?=$this->_request->start_time?>" class="form-control" data-plugin="date-picker">
							<span class="input-group-addon">~</span>
							<input type="text" name="end_time" value="<?=$this->_request->end_time?>" class="form-control" data-plugin="date-picker">
						</div>
					</div>
					<div class="form-group">
						状态：
						<select name="view" class="form-control">
							<option value="all" <?php if ($this->_request->view == 'all') echo 'selected'?>>全部状态</option>
							<option value="awaiting_payment" <?php if ($this->_request->view == 'awaiting_payment') echo 'selected'?>>待付款</option>
							<option value="shiped" <?php if ($this->_request->view == 'shiped') echo 'selected'?>>待发货</option>
							<option value="pending_receipt" <?php if ($this->_request->view == 'pending_receipt') echo 'selected'?>>待签收</option>
							<option value="completed" <?php if ($this->_request->view == 'completed') echo 'selected'?>>已完成</option>
							<option value="closed" <?php if ($this->_request->view == 'closed') echo 'selected'?>>已关闭</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="submit" class="btn btn-primary">搜索</button>
				</div>
			</form>
			<!-- /.modal-content --> 
		</div>
		<!-- /.modal-dialog --> 
	</div>
	<!-- /.modal -->
</div>
<form method="post" action="<?=$this->url('action=export')?>" class="ids_submit">
	<input type="hidden" value="" name="ids" id="ids" />
</form>

<script>
	$(document).ready(function() {
		$('.export').click(function() {
			var valArr = new Array;
			$("input[name='ids[]']:checked").each(function(i){
				valArr[i] = $(this).val();
			});
			var ids = valArr.join(',');//数组转换以逗号隔开的字符串
			$('#ids').val(ids);
			$('.ids_submit').submit();
		});
		$('.import').click(function() {
			var val = $('#file_excle').val();
			if(!val){
				alert('请选择文件');
				return false;
			}
			$('.file_submit').submit();
		});
	});
</script>
