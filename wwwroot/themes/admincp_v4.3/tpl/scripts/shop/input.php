<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '商家';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>商家名称:</label>
			<div class="col-sm-7">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">行业分类:</label>
			<div class="col-sm-7">
				<select name="category_id" class="form-control">
					<option value="">请选择</option>
					<?php 
					$cid = isset($this->data['category_id']) ? $this->data['category_id'] : $this->_request->cid;
					foreach ($this->categories as $row) { ?>
					<option value="<?=$row['id']?>" <?php if ($cid == $row['id']) echo 'selected';?>> <?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?> </option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商家封面图片:</label>
			<div class="col-sm-7">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1"
					 data-ipt="ref_img_bg" data-ref="bgshop">
					<div class="sui-img-value"><?=$this->data['ref_img_bg']?></div>
					<div class="sui-img-selector-box clearfix"></div>
					<div class="sui-img-selector-btns clearfix">
						<button type="button" class="btn" role="btn">选择图片</button>
						<span class="help-block" style="display:inline">至少需要一张图片，首张图片将做商品缩略图。(支持拖拽)</span>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商家图片:</label>
			<div class="col-sm-7">
				<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="10" 
					data-ipt="ref_img" data-ref="shop">
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
			<label class="control-label col-sm-2">电话:</label>
			<div class="col-sm-7">
				<input type="text" name="tel" value="<?=$this->data['tel']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">地址:</label>
			<div class="col-sm-7">
				<div class="JS_Dmenu form-inline">
					<input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
					<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
				</div>
				<input type="text" name="addr" value="<?=$this->data['addr']?>" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商家描述:</label>
			<div class="col-sm-7">
				<textarea name="description" rows="4" class="form-control" data-plugin="editor"><?=stripcslashes($this->data['description'])?></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">特殊商家:</label>
			<div class="col-sm-7">
				<label><input type="radio" name="is_special" value="1" <?=$this->data['is_special']==1 ? 'checked' : ''?>> 是</label>
				<label><input type="radio" name="is_special" value="0" <?=$this->data['is_special']==0 ? 'checked' : ''?>> 否</label>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
function selectGroup(val) {
	if (parseInt(val) == -1) {
		$('#addnew-group').show();
		$('input[name=group]').val('');
	} else {
		$('#addnew-group').hide();
		$('input[name=group]').val(val);
	}
}
</script> 
<script type="text/javascript">
    seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
        dmenu.init('.JS_Dmenu', {
            rootId: 1,
            script: '/misc.php?act=area',
			htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
            firstText: '请选择所在地',
            defaultText: '请选择',
            selected: $('input[name=area_id]').val(),
            callback: function(el, data) { 
                var location = $('.JS_Dmenu>select>option:selected').text();
				$('input[name=area_id]').val(data.id > 0 ? data.id : 0); 
				$('input[name=area_text]').val(location);
				$('input[name=zipcode]').val(data.zipcode > 0 ? data.zipcode : '');
            }
        });
         
    });
</script>