<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle($this->_request->g ? $this->_request->g : '商品销售报告');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?></h1>
	</div>

	<div style="margin-top: 20px;">
		<div class="panel panel-default">
			<div class="panel-heading">商品价格区间成交量</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-7">
						<div id="chartdiv1" style="width: 100%; height: 360px;"></div>
						<script src="/assets/js/amcharts/amcharts.js" type="text/javascript"></script>
						<script src="/assets/js/amcharts/pie.js" type="text/javascript"></script>
						<script src="/assets/js/amcharts/themes/light.js" type="text/javascript"></script>
						<script type="text/javascript">
						var chart1 = AmCharts.makeChart("chartdiv1", {
							"type": "pie",
							"theme": "light",
							"dataProvider": [
								<?php 
								foreach($this->pricestat as $key => $val) { ?>
								{
								"name": "<?php if ($key=='s50') { echo '50元以内'; } elseif ($key=='e5000') { echo '5000元以上'; } else { echo str_replace(array('s','e'), array('', '-'), $key).'元'; } ?>",
								"value": <?=$val?>
								},
								<?php } ?>
							],
							"valueField": "value",
							"titleField": "name",
							"startDuration": 0,
							"pullOutRadius": 10,
							"marginTop": 10,
							"marginBottom": 10,
							"marginLeft": 10,
							"marginRight": 10,
							"balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]] 笔订单</b> ([[percents]]%)</span>",
						} );
						</script>
					</div>
					<div class="col-sm-5">
						<h5>区间订单量</h5>
						<table width="100%" class="table table-striped " data-plugin="chk-group">
							<tbody>
								<?php foreach($this->pricestat as $key => $val) { ?>
								<tr>
									<td>
										<?php if ($key=='s50') { echo '50元以内'; } elseif ($key=='e5000') { echo '5000元以上'; } else { echo str_replace(array('s','e'), array('', '-'), $key).'元'; } ?>
									</td>
									<td class="text-right"><?=$val?> 笔</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row" style="margin-top: 20px;">
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">商品浏览量 TOP10</div>
				<div class="panel-body">
					<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
						<table width="100%" class="table table-striped " data-plugin="chk-group">
							<thead>
								<tr>
									<th>名次</th>
									<th>商品</th>
									<th class="text-right">点击量</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($this->topclicks as $i => $row) { ?>
								<tr>
									<td><?=$i+1?></td>
									<td><a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>"><?=$row['title']?></a></td>
									<td class="text-right"><?=$row['clicks_num']?> 次</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading">商品购买量 TOP10</div>
				<div class="panel-body">
					<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
						<table width="100%" class="table table-striped " data-plugin="chk-group">
							<thead>
								<tr>
									<th>名次</th>
									<th>商品</th>
									<th class="text-right">销售量</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($this->topsales as $i => $row) { ?>
								<tr>
									<td><?=$i+1?></td>
									<td><a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>"><?=$row['title']?></a></td>
									<td class="text-right"><?=$row['sales_num']?> <?=$row['unit']?></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
