<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle('订单统计报表');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<style type="text/css">
	.sui-page-header .sui-searchbox {
		left: 140px;
	}
</style>
<div class="sui-page-header">
	<h1> <?=$this->head()->getTitle()?></h1>
	<form method="get" class="sui-searchbox form-inline">
		<div class="form-group">
			<div class="input-group">
				<input type="text" name="sd" value="<?=$this->_request->sd?>" placeholder="起始时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
				<span class="input-group-addon">~</span>
				<input type="text" name="ed" value="<?=$this->_request->ed?>" placeholder="结束时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
			</div>
		</div>
		<div class="form-group">
			<label class=" control-label">订单状态:</label>

			<select name="status" class="form-control">
				<option value="5" <?=$this->_request->status == '5' ? 'selected' : ''?>>全部</option>
				<!--<option value="0" <?/*=$this->_request->status == '0' ? 'selected' : ''*/?>>废单</option>-->
				<option value="1" <?=$this->_request->status == '1' ? 'selected' : ''?>>待付款</option>
				<option value="2" <?=$this->_request->status == '2' ? 'selected' : ''?>>待发货</option>
				<option value="3" <?=$this->_request->status == '3' ? 'selected' : ''?>>待签收</option>
				<option value="4" <?=$this->_request->status == '4' ? 'selected' : ''?>>已完成</option>
			</select>

		</div>
		<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
	</form>
</div>
<div class="row" style="margin-top: 20px;">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">查询的订单数据</div>
			<div class="panel-body">
				<table width="100%" class="table table-striped" data-plugin="chk-group">
					<thead>
					<tr>
						<th>成交额</th>
						<th>销售量</th>
						<th>订单数</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach($this->check_orders as $order) {?>
						<tr>
							<td><?php echo $order['a']?>(元)</td>
							<td><?php echo $order['o']?>(件)</td>
							<td><?php echo $order['q']?>(笔)</td>
						</tr>
					<?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
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
