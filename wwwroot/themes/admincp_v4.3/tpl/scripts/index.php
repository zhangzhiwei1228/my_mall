<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<div class="row">
	<div class="col-md-8">
		<div class="sui-site-stat row">
			<div class="col-xs-3">
				<div class="stat-block" style="border-color:#09C">
					<strong>&yen;<?=(float)$this->orderStat['total_amount']?> 元</strong>
					今日成交额
				</div>
			</div>
			<div class="col-xs-3">
				<div class="stat-block" style="border-color:#096">
					<strong><?=(int)$this->orderStat['total_orders']?> 笔</strong>
					今日订单量
				</div>
			</div>
			<div class="col-xs-3">
				<div class="stat-block" style="border-color:#F90">
					<strong><?=(int)$this->orderStat['total_quantity']?> 件</strong>
					今日成交量
				</div>
			</div>
			<div class="col-xs-3">
				<div class="stat-block" style="border-color:#C30">
					<strong><?=(int)$this->userStat['total']?> 位</strong>
					今日注册
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">近10天成交额</div>
			<div class="panel-body">
				<div id="chartdiv" style="width: 100%; height: 334px;"></div>
				<script src="/assets/js/amcharts/amcharts.js" type="text/javascript"></script>
				<script src="/assets/js/amcharts/serial.js" type="text/javascript"></script>
				<script src="/assets/js/amcharts/themes/light.js" type="text/javascript"></script>

				<script type="text/javascript">
				AmCharts.makeChart("chartdiv", {
									type: "serial",
									theme: "light",
									dataProvider: [
					<?php for($i=10; $i>=0; $i--) { 
						$d = strtotime('-'.$i.' days');
						$k = date('Ymd', $d);
						$row = $this->orders[$k]?>
					{
											"year": '<?=date('m/d', $d)?>',
													"amount": <?=(float)$row['a']?>,
													"qty": <?=(int)$row['q']?>,
							"order": <?=(int)$row['o']?>
									},
					<?php } ?>],
									categoryField: "year",
									graphs: [{
											type: "column",
											title: "成交额",
											valueField: "amount",
											lineAlpha: 0,
											fillAlphas: 0.8,
											balloonText: "[[category]][[title]]:<b>&yen;[[value]]</b>"
									},
					{
						type: "line",
						title: "成交量",
						valueField: "qty",
						lineThickness: 2,
						fillAlphas: 0,
						bullet: "round",
						balloonText: "[[category]][[title]]:<b>[[value]]</b>"
					},
					{
						type: "line",
						title: "订单量",
						valueField: "order",
						lineThickness: 2,
						fillAlphas: 0,
						bullet: "round",
						balloonText: "[[category]][[title]]:<b>[[value]]</b>"
					}],
									legend: {
											useGraphSettings: true
									}
							});
				</script>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">本月热销商品</div>
			<table class="table">
				<tbody>
					<?php if(count($this->hotSale)) { $i=0; foreach($this->hotSale as $row) { $i++; ?>
					<tr>
						<td><span class="label label-<?=$i>3?'default':'danger'?>"><?=$i?></span></td>
						<td width="40"><div class="thumb">
							<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['goods_id'])?>" target="_blank">
								<img src="<?=$this->img($row['thumb'], '160x160')?>" width="40"></a>
						</div></td>
						<td><a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['goods_id'])?>" target="_blank">
							<?=$row['title']?></a><br />
							&yen;<?=$row['selling_price']?></td>
						<td><?=$row['sales_num']?> <span class="package-unit">件</span></td>
					</tr>
					<?php } } else { echo '<tr><td colspan="4" class="text-center notfound">无相关销售数据</td></tr>'; } ?>
				</tbody>
			</table>
		</div>
	</div>
	
</div>