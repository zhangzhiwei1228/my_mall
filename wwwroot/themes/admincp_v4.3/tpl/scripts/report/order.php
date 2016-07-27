<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('订单统计报表');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-page-header">
	<h1> <?=$this->head()->getTitle()?></h1>
</div>

<div class="row" style="margin-top: 20px;">
	<div class="col-sm-6">
		<div class="sui-report-stat">
			<div class="heading" style="background: #09C">今日</div>
			<div class="col-sm-4">
				<big><?=(float)$this->todayStat['total_amount']?> 元</big>
				成交额
			</div>
			<div class="col-sm-4">
				<big><?=(float)$this->todayStat['total_quantity']?> 件</big>
				销售量
			</div>
			<div class="col-sm-4">
				<big><?=(float)$this->todayStat['total_orders']?> 笔</big>
				订单数
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="sui-report-stat">
			<div class="heading" style="background: #096">历史</div>
			<div class="col-sm-4">
				<big><?=(float)$this->historyStat['total_amount']?> 元</big>
				成交额
			</div>
			<div class="col-sm-4">
				<big><?=(float)$this->historyStat['total_quantity']?> 件</big>
				销售量
			</div>
			<div class="col-sm-4">
				<big><?=(float)$this->historyStat['total_orders']?> 笔</big>
				订单数
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">最近7天订单数据</div>
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
					<?php for($i=7; $i>=0; $i--) { 
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
</div>