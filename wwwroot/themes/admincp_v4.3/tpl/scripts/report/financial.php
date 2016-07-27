<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle($this->_request->g ? $this->_request->g : '财务统计报表');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?></h1>
	</div>

	<div style="margin-top: 20px;">
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
						<?php foreach($this->datalist as $row) { 
							$balance += $row['val']; ?>
						<tr>
							<td><?=M('User_Money')->getTypeText($row['type'])?></td>
							<td class="text-right"><?=$this->currency($row['val'])?>元</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>资金池</td>
							<td class="text-right"><?=$this->currency($balance)?>元</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
