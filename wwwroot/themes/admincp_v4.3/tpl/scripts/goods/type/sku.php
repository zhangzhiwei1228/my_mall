<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }

global $arr, $thead, $filter;
$arr = array(); $thead = array();
foreach ((array)$this->attributes as $row) {
	if (isset($row['is_sku']) && $row['is_sku']) {
		if (!isset($row['attr_value'])) continue;
		$thead[] = $row['attr_name'];
		$arr[] = $row['attr_value'];
		foreach($row['attr_value'] as $k => $v) {
			if (isset($row['attr_color'][$k])) {
				$colour[$v] = $row['attr_color'][$k];
			}
		}
		$vtype[$row['attr_name']] = $row['attr_type'];
	}
}

$c = count($arr);
function cd($i,$v1 = null) {
	global $arr, $thead, $filter, $c, $s;
	foreach ((array)$arr[$i] as $v2) {
		$v2 = '['.skuEncode($thead[$i]).':'.skuEncode($v2).']';
		if (isset($arr[$i+1])) {
			cd($i+1, $v1 ? $v1.','.$v2 : $v2);
		} else {
			$v = $v1 ? $v1.','.$v2 : $v2;
			$k = md5($v);
			$s[] = $v;
		}
	}
	return $s;
}

$filter = array(array(':',','), array('：','，'));
function skuEncode($str) {
	global $filter;
	return str_replace($filter[0], $filter[1], $str);
}

function skuDecode($str) {
	global $filter;
	return str_replace($filter[1], $filter[0], $str);
}

$specs = cd(0);
?>
<?php 
	$k = 0;
	$sku = $this->skus[$k];
?>
<div class="form-group hide">
	<label class="control-label col-sm-2">SKU编号:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:260px">
			<span class="input-group-addon">#</span>
			<!--<input type="hidden" name="skus[<?/*=$k*/?>][spec]" value="" />
			<input type="hidden" name="skus[<?/*=$k*/?>][key]" value="<?/*=$k*/?>" />
			<input type="text" name="skus[<?/*=$k*/?>][code]" value="<?/*=$sku['code']*/?>" class="form-control" />-->
		</div>
	</div>
</div>
<!-- <div class="form-group">
	<label class="control-label col-sm-2">商品售价:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:260px">
			<span class="input-group-addon">&yen;</span>
			<input type="text" name="skus[<?=$k?>][selling_price]" value="<?=$sku['selling_price']?>" class="form-control JS_SP" />
			<span class="input-group-addon"><span class="package-unit"></span></span>
		</div>
		<div class="help-block">单件出售时，以此价格与买家结算</div>
	</div>
</div> -->
<div class="form-group">
	<label class="control-label col-sm-2">市场参考价:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:260px">
			<span class="input-group-addon">&yen;</span>
			<input type="text" name="skus[<?=$k?>][market_price]" value="<?=$sku['market_price']?>" class="form-control" />
			<span class="input-group-addon"><span class="package-unit"></span></span>
		</div>
		<div class="help-block">仅供用户参考，不参加实际结算</div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-2">快乐积分:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:260px">
			<span class="input-group-addon">&yen;</span>
			<input type="text" name="skus[<?=$k?>][point1]" value="<?=$sku['point1']?>" class="form-control JS_SP" />
			<span class="input-group-addon"><span class="package-unit"></span></span>
		</div>
		<div class="help-block">单件出售时，以此价格与买家结算</div>
	</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-2">免费积分:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:260px">
			<span class="input-group-addon">&yen;</span>
			<input type="text" name="skus[<?=$k?>][point2]" value="<?=$sku['point2']?>" class="form-control JS_SP" />
			<span class="input-group-addon"><span class="package-unit"></span></span>
		</div>
		<div class="help-block">单件出售时，以此价格与买家结算</div>
	</div>
</div>
<?php foreach($this->pointConfig as $field => $row) { ?>
<div class="form-group">
	<label class="control-label col-sm-2"><?=$row['name']?>:</label>
	<div class="col-sm-9">
		<div class="input-group pcf" style="width:260px">
			<input type="text" name="skus[<?=$k?>][exts][<?=$field?>][cash]" value="<?=@$sku['exts'][$field]['cash']?>" class="form-control fd-<?=$field?>" placeholder="现金" />
			<span class="input-group-addon">+</span>
			<input type="text" name="skus[<?=$k?>][exts][<?=$field?>][point]" value="<?=@$sku['exts'][$field]['point']?>" data-rate="<?=$row['rate']?>" class="form-control" placeholder="积分" />
			<span class="input-group-addon">元</span>
		</div>
	</div>
</div>
<?php } ?>

<?php if ($c) { ?>
<div class="form-group">
<label class="control-label col-sm-2">SKU库存:</label>
<div class="col-sm-9">
	<table class="table table-striped sui-sku-table" style="background:#fff; width:auto">
		<thead>
			<tr>
				<!-- <td class="text-center" width="60">图片</td> -->
				<?php foreach ($thead as $v) { ?>
				<td class="text-center"><?=$v?></td>
				<?php } ?>
				<!--<td class="text-center" width="150">SKU编号</td>
				<td align="text-center" width="100">市场价 (<span class="package-unit"></span>)</td>
				<td class="text-center" width="100">销售价 (<span class="package-unit"></span>)</td>-->
				<td class="text-center" width="100">库存 (<span class="package-unit"></span>)</td>
			</tr>
		</thead>
		<tbody>
			<?php $i = 0; foreach ((array)$specs as $k => $v1) { ?>
			<input type="hidden" name="skus[<?=$k?>][spec]" value="<?=$v1?>" />
			<input type="hidden" name="skus[<?=$k?>][key]" value="<?=$k?>" />
			<tr>
				<!-- <td class="text-center sui-sku-thumb" data-plugin="img-selector" data-limit="1" 
					data-ipt="skus[<?=$k?>][thumb]" data-ref="goods" title="单击选择图片">
					<div class="sui-img-value"><?=$this->skus[$k]['thumb']?></div>
					<div class="sui-img-selector-box" role="btn"><img class="sku-thumb-init" /></div>
				</td> -->
				<?php $cc = explode(',', $v1); 
				foreach ($cc as $v2) {
					list($t, $name) = explode(':', $v2);
					$t = substr($t, 1);
					$v = substr($name, 0, -1);
				?>
				<td align="center">
				<?php if (substr($v,0,1)=='#') { 
					list($c,$t) = explode('|', $v); ?>
					<span style="background:<?=$c?>;" class="sui-color <?=$k?>" title="<?=$t?>"></span>
					<?=$t?>
				<?php } else { echo $v; } ?></td>
				<?php } ?>
				<!--<td class="text-center"><input type="text" name="skus[<?/*=$k*/?>][code]" value="<?/*=$this->skus[$k]['code']*/?>" class="form-control input-sm" /></td>
				<td align="center"><input type="text" name="skus[<?/*=$k*/?>][market_price]" value="<?/*=isset($this->skus[$k]['selling_price']) ? $this->skus[$k]['market_price'] : 0*/?>" style="width:80px;" class="form-control input-sm JS_MP" /></td>
				<td class="text-center"><input type="text" name="skus[<?/*=$k*/?>][selling_price]" value="<?/*=isset($this->skus[$k]['selling_price']) ? $this->skus[$k]['selling_price'] : 0*/?>" class="form-control input-sm JS_SP" /></td>-->
				<td class="text-center"><input type="text" name="skus[<?=$k?>][quantity]" class="buychoose-quantity form-control input-sm" onchange="$.processQuantity()" value="<?=isset($this->skus[$k]['quantity']) ? $this->skus[$k]['quantity'] : 1?>" /></td>
			</tr>
			<?php $i++; } ?>
		</tbody>
	</table>
</div></div>
<?php /*} else { */?>
<div class="form-group">
	<label class="control-label col-sm-2">商品库存:</label>
	<div class="col-sm-9">
		<div class="input-group" style="width:400px">
			<input type="text" name="quantity" value="<?=$good['quantity'] ? $this->good['quantity'] : 1?>" class="form-control goods-quantity" />
			<span class="input-group-addon">库存警告</span>
			<input type="text" name="quantity_warning" id="sku-quantity-warning" value="<?=$this->good['quantity_warning']?>" class="form-control" />
			<input type="hidden" name="quantity_warning" id="good-quantity-warning" value="<?=$this->good['quantity_warning']?>" class="form-control" />

			<span class="input-group-addon"><span class="package-unit"></span></span>
		</div>
		<div class="help-block">库存为零时将停止销售. 当商品库存低于警告数值时,系统将会提示</div>
	</div>
</div>
<?php } ?>

<script src="/assets/js/suco-api.js"></script>
<script>
var thumb = $('.JS_ImgItem:first img').prop('src');
$('.sku-thumb-init').prop('src', thumb);
$("#sku-quantity-warning").blur(function(){
	$('#good-quantity-warning').val($(this).val());
});
$.changeUnit();

$('.JS_SP').change(function(){
	var vals = new Array();
	$('.JS_SP').each(function(){
		var p = parseFloat($(this).val());
		vals.push(p);
	});
	var minVal = Math.min.apply(null,vals);
	var maxVal = Math.max.apply(null,vals);
	$('[name=min_price]').val(minVal);
	$('[name=max_price]').val(maxVal);
});

$('.pcf input').on('change', function(){
	var val = $(this).val();
	var rate = $(this).data('rate');
	// console.log(rate);
})
</script>