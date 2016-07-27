<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle($this->region->exists() ? $this->region['name'].'销售统计' : '区域销售统计');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');

if ($this->region->exists()) {
	$this->paths[] = array('name'=>$this->region['name'].'销售统计');
}

?>
<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?></h1>
		<div style="position: absolute; left: 160px; top: 10px;">
			<a href="<?=$this->url('&sd='.urlencode(date('Y/m/d')))?>">今日</a> | 
			<a href="<?=$this->url('&sd='.urlencode(date('Y/m/d',strtotime('-1 monday'))))?>">昨日</a> | 
			<a href="<?=$this->url('&sd='.urlencode(date('Y/m/d')))?>">本周</a> | 
			<a href="<?=$this->url('&sd='.urlencode(date('Y/m/1')))?>">本月</a> | 
			<a href="<?=$this->url('&sd=')?>">全部</a>
		</div>
		<form method="get" action="<?=$this->url('&')?>" class="sui-searchbox form-inline">
			<div class="form-group">
				<div class="input-group">
					<input type="text" name="sd" value="<?=$this->_request->sd?>" placeholder="起始时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
					<span class="input-group-addon">~</span>
					<input type="text" name="ed" value="<?=$this->_request->ed?>" placeholder="结束时间" data-plugin="date-picker" class="form-control input-sm" style="width: 110px">
				</div>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
		</form>
	</div>

	<div class="row" style="margin-top: 20px">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">销售额占比</div>
				<div class="panel-body">
					<?php if ($this->datalist->total()) { ?>
					<div id="chartdiv1" style="width: 100%; height: 334px;"></div>
					<script src="/assets/js/amcharts/amcharts.js" type="text/javascript"></script>
					<script src="/assets/js/amcharts/pie.js" type="text/javascript"></script>
					<script src="/assets/js/amcharts/themes/light.js" type="text/javascript"></script>
					<script type="text/javascript">
					var chart1 = AmCharts.makeChart("chartdiv1", {
						"type": "pie",
						"theme": "light",
						"dataProvider": [
							<?php
							$data = array();
							foreach($this->datalist as $i => $row) {
								if ($i>9 || !$row['name']) {
									$data['其它'] += $row['t_amount'];
								} else {
									$data[$row['name']] = $row['t_amount'];
								}
							}
							foreach($data as $key => $val) { ?>
							{
							"country": "<?=$key?>",
							"value": <?=$val?>
							},
							<?php } ?>
						],
						"valueField": "value",
						"titleField": "country",
						"startDuration": 0,
						"pullOutRadius": 10,
						"marginTop": 15,
						"marginBottom": 15,
						"marginLeft": 15,
						"marginRight": 15,
						"balloonText": "[[title]]<br><span style='font-size:14px'><b>&yen;[[value]]</b> ([[percents]]%)</span>",
					} );
					</script>
					<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
						<table width="100%" class="table table-striped " data-plugin="chk-group">
							<thead>
								<tr>
									<th>区域</th>
									<th class="text-right">销售额</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($this->datalist as $row) { ?>
								<tr>
									<td><a href="<?=$this->url('&sd=&ed=&pid='.$row['province_id'])?>"><?=$row['name']?$row['name']:'其它'?></a></td>
									<td class="text-right"><?=$row['t_amount']?>元</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
					<?php } else { ?>
						<div class="notfound">暂无数据</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">订单量占比</div>
				<div class="panel-body">
					<?php if ($this->datalist->total()) { ?>
					<div id="chartdiv2" style="width: 100%; height: 334px;"></div>
					<script src="/assets/js/amcharts/amcharts.js" type="text/javascript"></script>
					<script src="/assets/js/amcharts/pie.js" type="text/javascript"></script>
					<script src="/assets/js/amcharts/themes/light.js" type="text/javascript"></script>
					<script type="text/javascript">
					var chart2 = AmCharts.makeChart("chartdiv2", {
						"type": "pie",
						"theme": "light",
						"dataProvider": [
							<?php
							$data = array();
							foreach($this->datalist as $i => $row) {
								if ($i>9 || !$row['name']) {
									$data['其它'] += $row['t_orders'];
								} else {
									$data[$row['name']] = $row['t_orders'];
								}
							}
							foreach($data as $key => $val) { ?>
							{
							"country": "<?=$key?>",
							"value": <?=$val?>
							},
							<?php } ?>
						],
						"valueField": "value",
						"titleField": "country",
						"startDuration": 0,
						"pullOutRadius": 10,
						"marginTop": 15,
						"marginBottom": 15,
						"marginLeft": 15,
						"marginRight": 15,
						"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]] 笔</b> ([[percents]]%)</span>",
					} );
					</script>
					<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
						<table width="100%" class="table table-striped " data-plugin="chk-group">
							<thead>
								<tr>
									<th>区域</th>
									<th class="text-right">订单量</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($this->datalist as $row) { ?>
								<tr>
									<td><a href="<?=$this->url('&sd=&ed=&pid='.$row['province_id'])?>"><?=$row['name']?$row['name']:'其它'?></a></td>
									<td class="text-right"><?=$row['t_orders']?>笔</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
					<?php } else { ?>
						<div class="notfound">暂无数据</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
