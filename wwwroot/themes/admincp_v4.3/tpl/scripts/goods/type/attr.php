<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<script>
var cusnum = <?=$this->customs ? (int)count($this->customs) : 0?>;
</script>

<?php if (!$this->category->exists()) { ?>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-9">
		<div class="alert alert-warning" style="width:400px;">您还没有选择商品类目，请先选择类目。</div>
	</div>
</div>
<?php } ?>
<script src="/assets/js/colorpicker/colpick.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/colorpicker/colpick.css" type="text/css"/>
<?php if(count($this->attributes)) {
	foreach ((array)$this->attributes as $k => $row) {
	$key = md5('a_'.strtolower(str_replace(' ', '-', $k)));
	$attr_values = $row['attr_values']; ?>
<input type="hidden" name="attributes[<?=$key?>][attr_name]" value="<?=$row['attr_name']?>" id="ipt_name_<?=$key?>" >
<input type="hidden" name="attributes[<?=$key?>][attr_type]" value="<?=$row['attr_type'] ? $row['attr_type'] : 'text'?>" />
<div class="form-group">
	<div class="col-sm-2 control-label"><?=$row['attr_name']?>:</div>
	<?php if ($row['input_type'] == 'text') { ?>
	<div class="col-sm-9">
		<input type="text" name="attributes[<?=$key?>][attr_value]" value="<?=$row['attr_value'][0]?>" class="form-control" style="width:300px">
	</div>
	<?php } elseif ($row['input_type'] == 'textarea') { ?>
	<div class="col-sm-9">
		<textarea name="attributes[<?=$key?>][attr_value]" style="width:300px" class="form-control"><?=$row['attr_value'][0]?></textarea>
	</div>
	<?php } elseif ($row['input_type'] == 'checkbox') { ?>
	<div class="col-sm-9">
		<div class="checkbox">
		<?php foreach ($attr_values as $item) { $item = trim($item); ?>
		<label style="margin-right:6px;">
			<input type="checkbox" name="attributes[<?=$key?>][attr_value][]" class="ipt_<?=$key?>" value="<?=$item?>"<?=@in_array($item, $row['attr_value']) ? 'checked' : ''?>><?=$item?></label>
		<?php } ?>
		</div>
	</div>
	<?php } elseif ($row['input_type'] == 'radio') { ?>
	<div class="col-sm-9">
		<div class="radio">
		<?php foreach ($attr_values as $item) { $item = trim($item); ?>
		<label style="margin-right:6px;">
			<input type="radio" name="attributes[<?=$key?>][attr_value]" class="ipt_<?=$key?>" value="<?=$item?>"<?php if ($item == $row['attr_value'][0]) echo ' checked';?>><?=$item?></label>
		<?php } ?>
		</div>
	</div>
	<?php } elseif ($row['input_type'] == 'select') { ?>
	<div class="col-sm-9">
		<select name="attributes[<?=$key?>][attr_value]" class="ipt_<?=$key?> form-control" style="width:auto">
			<option value="">---------</option>
			<?php foreach ($attr_values as $item) { $item = trim($item); ?>
			<option value="<?=$item?>"<?php if ($item == $row['attr_value'][0]) echo ' selected';?>><?=$item?></option>
			<?php } ?>
		</select>
	</div>
	<?php } elseif ($row['input_type'] == 'buychoose') {
	$vals = (array)$row['attr_value'];
	$items = array_merge($row['attr_values'], $vals);
	$items = array_unique($items); $k = 0; ?>
	<div class="col-sm-9">
	<input type="hidden" name="attributes[<?=$key?>][is_sku]" value="1" />
	<div class="buychoose-item form-inline" attr_name="<?=$row['attr_name']?>" group="<?=$key?>">
	<?php $j=0; foreach ($items as $k => $item) { $j++; ?>
		<span style="margin-right:10px">
			<label>
				<input type="checkbox" id="chk_<?=$key?>_<?=$k?>" <?=in_array($item, $vals) ? 'checked' : ''?> class="chk_<?=$key?>" value="<?=$item?>" onclick="$.buychoose('<?=$key?>', <?=$k?>, this); $.makeSkuTable()">
			</label>
			<?php if (substr($item,0,1)=='#') { list($c,$t) = explode('|',$item); ?>
			<a style="background:<?=$c?>;" class="sui-color cc-<?=$k?>">&nbsp;</a>
			<input type="text" value="<?=$t?>" class="color_title_<?=$k?> ipt_<?=$key?>_<?=$k?> form-control input-sm" disabled="disabled" onchange="$.setColorCode(<?=$k?>);" style="width:60px" />
			<input type="hidden" value="<?=$c?>" class="color_code_<?=$k?>" />
			<input type="hidden" name="attributes[<?=$key?>][attr_value][]" value="<?=$item?>" class="ipt_<?=$key?>_<?=$k?> color_value_<?=$k?>" />
			<script>
				$('.cc-<?=$k?>').colpick({
					layout:'hex',
					submit:0,
					onChange:function(hsb,hex,rgb,el,bySetColor) {
						$(el).css('background-color','#'+hex);
						$('.color_code_<?=$k?>').val('#'+hex);
						$.setColorCode(<?=$k?>);
					}
				});
			</script>
			<?php } else { ?>
			<input type="text" name="attributes[<?=$key?>][attr_value][]" value="<?=$item?>" class="ipt_<?=$key?>_<?=$k?> form-control input-sm" disabled="disabled" onchange="$.makeSkuTable()" style="width:60px" />
			<?php } ?>
		</span>
		<script type="text/javascript">
			$.buychoose('<?=$key?>', <?=(int)$k?>);
		</script>
		<?php if ($j/5 == intval($j/5)) echo '<br>'; } ?></div>
	<?php } ?>
	</div>
	</div>
</div>
<?php } ?>
<hr class="line" />
<?php } ?>
<div class="form-group">
	<div class="col-sm-2 control-label">自定义属性:</div>
	<div class="col-sm-9">
		<div id="custom-input-box">
			<?php foreach ((array)$this->customs as $key => $row) { $key = 'c'.$key; ?>
			<div id="custom-item-<?=$key?>">
			<div class="input-group" style="margin-bottom:3px; width:330px;">
				<input type="text" name="attributes[<?=$key?>][attr_name]" value="<?=$row['attr_name']?>" class="form-control" placeholder="属性名" />
				<span class="input-group-addon">=</span>
				<input type="text" name="attributes[<?=$key?>][attr_value]" value="<?=$row['attr_value']?>" class="form-control" placeholder="属性值" />
				<span class="input-group-btn">
				<a class="btn btn-danger" onclick="$.removeCustomItem('<?=$key?>')">移除</a>
				</span>
			</div></div>
			<?php } ?>
		</div>
		<div class="form-control-static"><a class="icon-append" onclick="$.appendCustomItem()">添加属性</a></div>
	</div>
</div>
<dl id="_custpl" style="display:none">
	<dd id="custom-item-c@id">
	<div class="input-group" style="margin-bottom:3px; width:330px;">
		<input type="text" name="attributes[n@id][attr_name]" value="" class="form-control" placeholder="属性名" />
		<span class="input-group-addon">=</span>
		<input type="text" name="attributes[n@id][attr_value]" value="" class="form-control" placeholder="属性值" />
		<span class="input-group-btn">
		<a class="btn btn-danger" onclick="$.removeCustomItem('c@id')">移除</a>
		</span>
	</div>
	</dd>
</dl>
<script type="text/javascript">
$.makeSkuTable();
seajs.use('/assets/js/dragsort/jquery.dragsort-0.5.2.js', function(dragsort){
	$('#custom-input-box').dragsort({
		dragSelectorExclude: "input, textarea, select, a, button",
		dragSelector:'dd', 
	});
});

</script>