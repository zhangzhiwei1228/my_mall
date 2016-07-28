<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '订单详情';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<!-- <div class="sui-page-options">
	<?php if ($this->data->getPrevItem()->exists()) { ?>
	<a href="<?=$this->url('&action=detail&id='.$this->data->getPrevItem()->id.'&ref='.$this->_request->ref)?>" class="btn btn-sm btn-default">
		&lt;&lt; 查看上一笔</a>
	<?php } ?>
	<?php if ($this->data->getNextItem()->exists()) { ?>
	<a href="<?=$this->url('&action=detail&id='.$this->data->getNextItem()->id.'&ref='.$this->_request->ref)?>" class="btn btn-sm btn-default">
		查看下一笔 &gt;&gt;</a>
	<?php } ?>
</div> -->

<div class="sui-box">
	<ul class="nav nav-justified nav-wizard" style="margin-bottom:15px">
		<li class="done"><a href="#"><i>1</i> 提交订单 
			<small><?=date(DATETIME_FORMAT, $this->data['create_time'])?></small></a>
		</li>
		<li class="<?=$this->data['pay_time']?'done':''?>"><a href="#"><i>2</i> 已付款 
			<small>
				<?php if ($this->data['expiry_time'] != 0 && $this->data['expiry_time'] < time() 
				&& $this->data['status'] == 1) { echo '付款超时';
				} else { echo $this->data['pay_time'] ? date(DATETIME_FORMAT, $this->data['pay_time']) : 'N/A'; } ?>
			</small></a>
		</li>
		<li class="<?=$this->data['delivery_time']?'done':''?>"><a href="#"><i>3</i> 已发货 
			<small>
				<?php if ($this->data['expiry_time'] != 0 && $this->data['expiry_time'] < time() 
				&& $this->data['status'] == 2) { echo '发货超时';
				} else { echo $this->data['delivery_time'] ? date(DATETIME_FORMAT, $this->data['delivery_time']) : 'N/A'; } ?>
			</small></a>
		</li>
		<li class="<?=$this->data['confirm_time']?'done':''?>"><a href="#"><i>4</i> 已签收 
			<small>
				<?php if ($this->data['expiry_time'] != 0 && $this->data['expiry_time'] < time() 
				&& $this->data['status'] == 3) { echo '签收超时';
				} else { echo $this->data['confirm_time'] ? date(DATETIME_FORMAT, $this->data['confirm_time']) : 'N/A'; } ?>
			</small></a>
		</li>
		<li class="<?=$this->data['confirm_time']?'done':''?>"><a href="#"><i>5</i> 完成 <small>N/A</small></a></li>
		<script type="text/javascript">
			$('li.done:last-child').addClass('active');
		</script>
	</ul>
	
	<div class="row">
		<div class="col-sm-9">
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">订单资料</h4></div>
				<div class="panel-body">
					<table class="table table-bordered">
						<tr>
							<th width="80">订单号:</th>
							<td>#<?=$this->data['code']?></td>
							<th width="80">成交时间:</th>
							<td><?=$this->data['create_time'] ? date(DATETIME_FORMAT, $this->data['create_time']) : '未成交'?></td>
							<th width="80">付款时间:</th>
							<td><?=$this->data['pay_time'] ? date(DATETIME_FORMAT, $this->data['pay_time']) : '未付款'?></td>
						</tr>
						<tr>
							<th>运单号:</th>
							<td><?=$this->data->delivery['code'] ? $this->data->delivery['code'] : 'N/A'?></td>
							<th>发货时间:</th>
							<td><?=$this->data['delivery_time'] ? date(DATETIME_FORMAT, $this->data['delivery_time']) : '未发货'?></td>
							<th>签收时间:</th>
							<td><?=$this->data['confirm_time'] ? date(DATETIME_FORMAT, $this->data['confirm_time']) : '未签收'?>		</td>
						</tr>
						<tr>
							<th>收件人:</th>
							<td><?=$this->data['consignee']?></td>
							<th>联系电话:</th>
							<td><?=$this->data['phone'] ? $this->data['phone'] : 'N/A'?></td>
							<th>买家帐户:</th>
							<td><?php if ($this->data['buyer_id']) { ?>
								<a href="<?=$this->url('controller=user&action=detail&id='.$this->data['buyer_id'])?>" target="_blank">
								<?=$this->data->buyer['username']?></a>
							<?php } else { echo '非注册买家'; } ?></td>
						</tr>
						<tr>
							<th>收货地址:</th>
							<td colspan="5"><?=$this->data['area_text']?> <?=$this->data['address']?> 
									<?=$this->data['zipcode'] ? '(邮编:'.$this->data['zipcode'].')' : null?></td>
						</tr>
					</table>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">商品清单</h4></div>
				<div class="panel-body">
					<table class="table table-striped">
						<thead>
							<tr>
								<th colspan="2">商品信息</th>
								<th width="90" class="text-right">成交价</th>
								<th width="90" class="text-right">数量</th>
								<th width="90" class="text-right">可获积分</th>
								<th width="90" class="text-right">小计</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total = 0;
							$goods = $this->data->goods;
							foreach ($goods as $col) {
								$total += $col['subtotal_amount'] + - $col['subtotal_save'];
							?>
							<tr>
								<td valign="top" width="90">
									<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$col['goods_id'])?>" target="_blank">
										<img src="<?=$this->img($col['thumb'], '160x160')?>" class="img-thumbnail"></a>
								</td>
								<td>
									<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$col['goods_id'])?>" target="_blank">
									<?=$col['title']?></a>
									<div>#<?=$col['code']?></div>
									<div><?=$col['spec'] ? $col['spec'] : ''?></div>
								</td>
								<td width="60" class="text-right" valign="top">
								<?php if ($col['final_price']!=$col['selling_price']) { ?>
									<del><?=$this->currency($col['selling_price'])?></del><br><?=$this->currency($col['final_price'])?>
								<?php } else { ?>
									<?=$this->currency($col['selling_price'])?>
								<?php } ?>
								
								<?php if ($col['promotion']) { ?>
								<br><span class="label label-danger"><?=$col['promotion']?></span>
								<?php } ?>
									<?php if ($col['is_return'] == 1) { ?>
									<br><span style="color:red">申请退款</span> [<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
									<?php } elseif ($col['is_return'] == 2) { ?>
									<br><span style="color:red">已退款</span> [<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
									<?php } elseif ($col['is_return'] == 3) { ?>
									<br><span style="color:red">拒绝退款</span> [<a href="<?=$this->url('controller=order_return&action=detail&opid='.$col['id'])?>" target="_blank">查看</a>]
									<?php } ?></td>
								<td width="60" class="text-right" valign="top"><?=(int)$col['purchase_quantity']?> <span class="unit"><?=$col['unit']?></span></td>
								<td width="60" class="text-right" valign="top"><?=(int)$col['subtotal_earn_points']?>点</td>
								<td width="60" valign="top" class="text-right">
									<?php if ($col['subtotal_save'] > 0) { ?>
									<del><?=$this->currency($col['subtotal_amount'])?></del><br>
									<?=$this->currency($col['subtotal_amount'] + - $col['subtotal_save'])?>
									<?php } else { ?>
									<?=$this->currency($col['subtotal_amount'])?>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr style="background:#f0f0f0;">
								<th>合计</th>
								<th class="text-right"></th>
								<th class="text-right"></th>
								<th class="text-right"><?=(int)$this->data['total_quantity']?> 件</th>
								<th class="text-right"><?=(int)$this->data['total_earn_points']?>点</th>
								<th class="text-right">
									<?php if ($this->data['total_save']>0) { ?>
									<del><?=$this->currency($this->data['total_amount'])?></del><br>
									<?=$this->currency($this->data['total_amount']-$this->data['total_save']>0?$this->data['total_amount']-$this->data['total_save']:0)?>
									<?php } else { ?>
									<?=$this->currency($this->data['total_amount'])?>
									<?php } ?>
								</th>
							</tr>
							<tr>
								<td colspan="2">
									<?php if ($this->data['joined_activity']) { ?>
									<span style="margin-right:10px;">
									【已参加的活动：<strong style="color:#C00"><?=$this->data['joined_activity']?></strong>】
									</span>
									<?php } ?>
									<?php if ($this->data['coupon_amount']>0) { ?>
									<span style="margin-right:10px;">
									【已使用<strong style="color:#C00">优惠券</strong>抵扣 <?=$this->data['coupon_amount']?>】
									</span>
									<?php } ?>

									<?php if ($this->data['remark']) { ?>
									<div>
										<strong>买家留言:</strong>
										<blockquote>
											<?=nl2br($this->data['remark'])?>
										</blockquote>
									</div>
									<?php } ?>
								</td>
								<td colspan="6" class="text-right" style="position:relative; line-height:25px;">
									+ 运费：<strong>&yen;<?=$this->data['total_freight']?></strong>
									<?php if ($this->data['total_fee']>0) { ?>
									+ 手续费: <strong>&yen;<?=$this->data['total_fee']?></strong>
									<?php } ?>
									<?php if ($this->data['total_use_points']!=0) { ?> 
									- 积分抵扣: <strong>&yen;<?=abs($this->data['total_fee'])?></strong>
									<?php } ?>
									<?php if ($this->data['total_use_coupon']!=0) { ?>
									- 礼券抵扣: <strong>&yen;<?=abs($this->data['total_use_coupon'])?></strong>
									<?php } ?>
									<?php /* if ($this->data['total_save']!=0) { ?>
									- 活动优惠: <strong>&yen;<?=number_format($this->data['total_save']-$total,2)?></strong>
									<?php } */ ?>
									<?php if ($this->data['adjustment_amount']>0) { ?>
									+ 价格调整：<strong>&yen;<?=$this->data['adjustment_amount']?></strong>
									<?php } elseif ($this->data['adjustment_amount']<0) { ?>
									- 价格调整：<strong>&yen;<?=abs($this->data['adjustment_amount'])?></strong>
									<?php } ?>
									
									<div>
									= 总计：<strong style="font-size:22px;">&yen;<?=$this->data['total_pay_amount']?></strong>
									</div>
									<?php if ($this->data['status'] == 1) { ?>
									[<a href="javascript:void(0);" onclick="$('#order-adjustment-amount').show()">调整</a>]
									<form id="order-adjustment-amount" method="post" action="<?=$this->url('action=adjustment')?>" style="display:none; background:#fff; position:absolute; top:0px; right:0px; width:300px;" class="form-inline well" role="form">
										<input type="hidden" name="id" value="<?=$this->data['id']?>" />
										<select name="algorithm" class="form-control input-xs">
											<option value="-1" <?php if ($this->data['adjustment_amount'] < 0) echo 'selected'?>>减免</option>
											<option value="1" <?php if ($this->data['adjustment_amount'] > 0) echo 'selected'?>>增加</option>
										</select>
										<input type="text" name="amount" value="<?=abs($this->data['adjustment_amount'])?>" class="form-control input-xs" style="width:80px" />
										<button type="submit" class="btn btn-primary btn-xs">确定</button>
										<button type="button" class="btn btn-xs" onclick="$('#order-adjustment-amount').hide()">取消</button>
									</form>
									<?php } ?>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>	

			<?php if ($this->data['delivery_time']) { ?>
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">物流信息</h4></div>
				<div class="panel-body">
					<div class="express-tracking"><p class="loading">正在查询,请稍后...</p></div>
					<a href="//www.kuaidi100.com/all/yt.shtml" target="_blank">圆通快递查询单号</a>
					<script type="text/javascript">
						$.get('/callback/kuaidi100/', {com:'ems',un:'9753404307401'}, function(data){
							$('.express-tracking').html(data);
						});
					</script>
				</div>
			</div>
			<?php } ?>
			
			<?php if ($this->data['invoice']) { ?>
			<h4>发票信息</h4>
			<div id="invoice">
				<div class="form-group">
					<label class="control-label col-sm-2">发票抬头:</label>
					<div class="col-sm-9"><?=$this->data['invoice'] ? $this->data['invoice'] : 'N/A'?> </div>
				</div>
			</div>
			<?php } ?>
		</div>

		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">详细信息</h4></div>
				<div class="panel-body">
					<div>
						<label>订单编号：</label>
						#<?=$this->data['code']?>
					</div>
					<div>
						<label>当前状态：</label>
						<?php switch($this->data['status']) { 
							case 0: echo '<span class="label label-danger">关闭</span>'; break; 
							case 1: echo '<span class="label label-warning">待付款</span>'; break; 
							case 2: echo '<span class="label label-warning">待发货</span>'; break; 
							case 3: echo '<span class="label label-warning">待签收</span>'; break; 
							case 4: echo '<span class="label label-success">完成</span>'; break; 
						}?>
						<?php if ($this->data['expiry_time'] == 0 && $this->data['status'] != 4 && $this->data['status'] != 0) { ?>
							<div><font color="red">已冻结</font></div>
						<?php } ?>
					</div>
					<div>
						<label>过期时间：</label>
						<?=$this->data['expiry_time'] ? date(DATETIME_FORMAT, $this->data['expiry_time']) : 'N/A'?>
					</div>
					<div>
						<label>买家帐户：</label>
						<?php if ($this->data['buyer_id']) { ?>
						<a href="<?=$this->url('controller=user&action=detail&id='.$this->data['buyer_id'])?>" target="_blank"><?=$this->data->buyer['username']?></a>
						<?php } else { ?>
						非注册买家
						<?php } ?>
					</div>
					<div>
						<label>虚拟订单：</label>
						<?=$this->data['virtual'] ? '是' : '否'?>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">相关操作</h4></div>
				<ul class="list-group">
					<li class="list-group-item"><a href="javascript:;" onclick="window.open('<?=$this->url('&action=print&v=goods&ref='.$this->_request->url)?>')"><i class="fa fa-print"></i> 打印配货单</a></li>
					<li class="list-group-item"><a href="javascript:;" onclick="window.open('<?=$this->url('&action=print&v=shipping&ref='.$this->_request->url)?>')"><i class="fa fa-truck"></i> 打印快递单</a></li>
					<!--<li class="list-group-item"><a href="<?=$this->url('&action=print&ref='.$this->_request->url)?>"><i class="fa fa-print"></i> 打印发票</a></li>-->
					<?php if ($this->data['expiry_time']!=0) { ?>
					
					<?php if ($this->data['status'] == 2) { ?>
					<li class="list-group-item"><a href="<?=$this->url('&action=delivery&ref='.$this->_request->url)?>"><i class="fa fa-pencil"></i> 填写发货单</a></li>
					<?php } ?>
					<?php if ($this->data['status'] == 1) { ?>
					<li class="list-group-item"><a href="<?=$this->url('&action=pay&ref='.$this->_request->ref)?>" onclick="return confirm('确定要将此订单标为已收款吗?')"><i class="fa fa-check"></i> 已收款</a></li>
					<?php } ?>

					<?php } ?>
					<?php if ($this->data['status'] == 1 || $this->data['status'] == 2) { ?>
					<li class="list-group-item"><a href="<?=$this->url('&action=consignee&ref='.$this->_request->url)?>"><i class="fa fa-edit"></i> 修改收货地址</a></li>
					<?php } ?>
					<?php if ($this->data['status'] == 0) { ?>
					<li class="list-group-item"><a href="<?=$this->url('&action=delete&ref='.$this->_request->ref)?>" onclick="return confirm('确定要删除这条记录吗?')"><i class="fa fa-trash-o"></i> 删除订单</a></li>
					<?php } ?>
				</ul>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><h4 class="panel-title">数据操作日志</h4></div>
				<div class="panel-body">
					<pre><?=$this->data['logs'] ? nl2br($this->data['logs']) : 'N/A'?></pre>
				</div>
			</div>
		</div>
	</div>
</div>