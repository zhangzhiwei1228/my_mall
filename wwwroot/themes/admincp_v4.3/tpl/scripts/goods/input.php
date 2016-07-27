<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle(($this->_request->getActionName() == 'add' ? '添加' : '修改'). '商品');
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"><?=$this->head()->getTitle()?></h1>
		<ul class="nav nav-pills">
			<li class="active"><a href="#base" data-toggle="tab">基本信息</a></li>
			<li><a href="#desc" data-toggle="tab">商品详情</a></li>
			<li><a href="#attribute" data-toggle="tab">商品属性</a></li>
			<li><a href="#package" data-toggle="tab">商品包装</a></li>
			<li><a href="#price" data-toggle="tab">价格与库存</a></li>
		</ul>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<input type="hidden" name="goods_id" value="<?=$this->data['id']?>" />
		<div class="tab-content">
			<div id="base" class="tab-pane fade active in">
				<div class="form-group">
					<label class="control-label col-sm-2"><span class="required">*</span>商品标题:</label>
					<div class="col-sm-7">
						<input type="text" name="title" value="<?=$this->data['title']?>" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">商品货号:</label>
					<div class="col-sm-7">
						<input type="text" name="code" value="<?=$this->data['code']?>" class="form-control" />
						<div class="help-block">用于您对商品的管理，不会对买家展示。如果不输入，系统将自动生成。</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">所属分类:</label>
					<div class="col-sm-7">
						<div class="input-group">
							<input type="text" name="path_text" value="" class="form-control" />
							<input type="hidden" name="category_id" value="<?=$this->data['category_id']?>" />
							<span class="input-group-btn">
								<button class="btn" type="button" data-toggle="modal" data-target="#cate-box">选择</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">商品图片:</label>
					<div class="col-sm-7">
						<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="10" 
							data-ipt="ref_img" data-ref="goods">
							<div class="sui-img-value"><?=$this->data['ref_img']?></div>
							<div class="sui-img-selector-box clearfix"></div>
							<div class="sui-img-selector-btns clearfix">
								<button type="button" class="btn" role="btn">选择图片</button>
								<span class="help-block" style="display:inline">至少需要一张图片，首张图片将做商品缩略图。(支持拖拽)</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">简要描述:</label>
					<div class="col-sm-7">
						<textarea name="summary" rows="4" class="form-control"><?=stripcslashes($this->data['summary'])?></textarea>
						<div class="help-block">请使用简短的文本描述商品特性</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">商品标签:</label>
					<div class="col-sm-7">
<!--						data-plugin="tagsinput"-->
						<textarea name="tags" class="form-control" rows="3" ><?=$this->data['tags']?></textarea>
						<div class="help-block">用户在搜索商品时,除了对标题匹配外还会对关键词进行匹配<br />多个关键词请用半角逗号(,)分隔</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">角标:</label>
					<div class="col-sm-7">
						<div class="input-group">
							<span class="input-group-addon">左上</span>
							<input type="text" name="sup[lt]" value="<?=@$this->data['sup']['lt']?>" class="form-control" />
							<span class="input-group-addon">左下</span>
							<input type="text" name="sup[ld]" value="<?=@$this->data['sup']['ld']?>" value="" class="form-control" />
							<span class="input-group-addon">右上</span>
							<input type="text" name="sup[rt]" value="<?=@$this->data['sup']['rt']?>" value="" class="form-control" />
							<span class="input-group-addon">右下</span>
							<input type="text" name="sup[rd]" value="<?=@$this->data['sup']['rd']?>" value="" class="form-control" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">可得积分:</label>
					<div class="col-sm-7">
						<input type="text" name="earn_points" value="<?=isset($this->data['earn_points'])?$this->data['earn_points']:'-1'?>" class="form-control" />
						<div class="help-block">-1表示按系统默认比率计算，详情请查看<a href="<?=$this->url('controller=setting')?>">系统设置</a></div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">备注:</label>
					<div class="col-sm-7">
						<input type="text" name="notes" value="<?=isset($this->data['notes'])?$this->data['notes']:''?>" class="form-control" placeholder="备注信息" />

					</div>
				</div>
				<div class="form-group" style="display:none">
					<label class="control-label col-sm-2">有效期:</label>
					<div class="col-sm-7">
						<?php $setEndDate = $this->data['expiry_time'] ? 1 : 0?>
						<div class="radio">
							<label><input type="radio" name="set_end_date" value="0" <?php if ($setEndDate == 0) echo 'checked'?> /> 永久有效</label>
							<label><input type="radio" name="set_end_date" value="1" <?php if ($setEndDate == 1) echo 'checked'?> class="JS_SetEndDate" /> 设定时间</label>
						</div>
						<input type="text" name="expiry_time" class="form-control" style="width:200px; display:none" value="<?=$this->data['expiry_time'] ? date(DATETIME_FORMAT, $this->data['expiry_time']) : ''?>" data-plugin="datetime-picker" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">上架销售:</label>
					<div class="col-sm-7">
						<div class="checkbox">
							<label><input type="checkbox" id="selling-checkbox" onchange="$('[name=is_selling]').val($(this).is(':checked') ? 1 : 0)" <?=$this->data['is_selling'] ? 'checked' : ''?> />
							打勾表示允许销售,否则不允许销售</label>
						</div>
						<input type="hidden" name="is_selling" value="<?=$this->data['is_selling'] ? 1 : 0?>" />
					</div>
				</div>
			</div>

			<div id="desc" class="tab-pane fade">
				<textarea name="description" rows="22" class="form-control" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['description'])?></textarea>
			</div>
			<div id="attribute" class="tab-pane fade">
				<div class="sc-attr-box">加载中,请稍后...</div>
			</div>
			<div id="package" class="tab-pane fade">
				<div class="form-group">
					<label class="control-label col-sm-2">最小计量单位:</label>
					<div class="col-sm-2">
						<select name="package_unit" class="form-control">
							<?php 
							$units = array('件', '个', '支', '根', '瓶', '只', '头', '斤', '公斤', '条', '包', '箱', '两', '双', '套', '克', '千克', '毫升', '升', '毫米', '厘米', '米', '千米');
							foreach($units as $unit) {
							?>
							<option value="<?=$unit?>"<?php if ($unit == $this->data['package_unit']) echo 'selected';?>><?=$unit?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">发货点:</label>
					<div class="col-sm-2">
						<select name="shipping_id" class="form-control">
							<?php
								$shippings = M('Shipping')->select('id, name')->where('is_enabled<>0')->order('id DESC')->fetchRows();
								if(count($shippings) > 0) {?>
								<?php if(!$this->data['shipping_id']) {?>
									<option selected>请选择发货点</option>
								<?php }?>
								<?php foreach($shippings as $shipping) {?>

									<option value="<?php echo $shipping['id']?>" <?php if($this->data['shipping_id'] == $shipping['id']) {?> selected <?php }?>><?php echo $shipping['name']?></option>
								<?php }?>
							<?php } else {?>
								<option value="0">暂无</option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">销售方式:</label>
					<div class="col-sm-7">
						<label><input type="radio" name="package_method" value="1" <?=$this->data['package_quantity'] == 0 ? 'checked="checked"' : '' ?> /> 按<span class="package-unit-text"></span>出售</label>
						<label><input type="radio" name="package_method" value="2" <?=$this->data['package_quantity'] > 0 ? 'checked="checked"' : '' ?> /> 打包出售</label><br>
						<div id="package-setting" class="input-group" style="width:260px; display:none">
						<input type="text" name="package_quantity" class="form-control" value="<?=$this->data['package_quantity']?>" />
						<span class="input-group-addon"><span class="package-unit-text"></span> / 每</span>
						<input type="text" name="package_lot_unit" class="form-control" value="<?=$this->data['package_lot_unit'] ? $this->data['package_lot_unit'] : '包'?>" />
						</div> 
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">包装后重量:</label>
					<div class="col-sm-3"><div class="input-group">
						<input type="text" name="package_weight" value="<?=$this->data['package_weight']?>" title="重量" placeholder="重量" class="form-control" />
						<span class="input-group-addon">KG / <span class="package-unit"></span></span></div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">包装尺寸:</label>
					<div class="col-sm-5">
						<div class="input-group">
						<input type="text" name="package_length" value="<?=$this->data['package_length']?>" title="长度" placeholder="长度" class="form-control" />
						<span class="input-group-addon">*</span>
						<input type="text" name="package_width" value="<?=$this->data['package_width']?>" title="宽度" placeholder="宽度" class="form-control" />
						<span class="input-group-addon">*</span>
						<input type="text" name="package_height" value="<?=$this->data['package_height']?>" title="高度" placeholder="高度" class="form-control" /><span class="input-group-addon">(mm)</span>
						</div>
						<div class="help-block">商品包装后的重量和体积将作为计算运费的依据，为避免交易纠纷，请如实填写；</div>
					</div>
				</div>
			</div>
			<div id="price" class="tab-pane fade">
				<input type="hidden" name="min_price" value="<?=$this->data['min_price']?>" />
				<input type="hidden" name="max_price" value="<?=$this->data['max_price']?>" />
				<div class="JS_SkuSettings">加载中,请稍后...</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-7">
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
					<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
				</div>
			</div>
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="cate-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">选择类目</h4>
					</div>
					<div class="modal-body">
						<div class="JS_Dmenu form-inline"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$.loadAttribute($('[name=category_id]').val(), $('[name=goods_id]').val())">确定</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
	</form>
</div>
<script type="text/javascript" src="./js/goods.js"></script> 
<script type="text/javascript">

$('input[name=set_end_date]').click(function(){
	if ($(this).hasClass('JS_SetEndDate')) { $('input[name=expiry_time]').show(); } 
	else { $('input[name=expiry_time]').val('').hide(); }
});

$('[name=package_unit], [name=package_method], [name=package_lot_unit], [name=shipping_id]').on('change', function(){
	$.changeUnit();
});

//初始化开始
$.changeUnit();
$.loadAttribute(<?=(int)$this->data['category_id']?>, <?=(int)$this->data['id']?>);
if ($('input[name=expiry_time]').val()) {
	$('.JS_SetEndDate').attr('checked', true);
	$('input[name=expiry_time]').show();
}

seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
	dmenu.init('.JS_Dmenu', {
		script: '<?=$this->url('controller=goods_category&action=getJson')?>',
		htmlTpl: '<select size="8" class="form-control" style="margin-right:6px"></select>',
		selected: $('input[name=category_id]').val(),
		callback: function(el, data) {
			var path = new Array();
			$('.JS_Dmenu>select').each(function(){
				path.push($('option:selected', this).text());
			});
			$('input[name=path_text]').val(path.join(' > '));
			$('input[name=category_id]').val(data.id);
		}
	});
});

seajs.use('/assets/js/validator/validator.sea.js', function(validator){
	validator('form', {
		rules: {
			'title': { valid: 'required', errorText: '请填写商品标题' }
		}
	});
});
</script>
