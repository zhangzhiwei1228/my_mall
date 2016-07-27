<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<form class="n-weblist" method="post" action="<?=$this->url('action=place_order')?>">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		收货地址管理
		<input class="n-keep" value="管理" type="button" onclick="window.location = '<?=$this->url('usercp/address')?>'">
	</div>
	<div class="n-h5"></div>
	<?php if ($this->addrs->total()) { ?>
	<?php foreach($this->addrs as $row) { ?>
	<div class="n-shopping-box">
		<div class="n-shopping-box-top">
			<input type="checkbox" name="addr_id" value="<?=$row['id']?>" />  
		</div>
		<div class="n-shopping-box-down">
			<div class="n-web-top">
				<p class="n-web-topl"><?=$row['consignee']?></p>
				<p class="n-web-topr"><?=$row['phone']?></p>
			</div>
			<div class="n-web-down">
				<p><?=$row['area_text']?> <?=$row['address']?></p>
			</div>
		</div>
	</div>
	<?php } ?>
	<?php } else { ?>
	<table width="100%">
			<tr>
				<td width="30%"><p>收货人：</p></td>
				<td><input type="text" name="consignee" value="<?=$this->data['consignee']?>"></td>
			</tr>
			<tr>
				<td width="30%"><p>手机号码 :</p></td>
				<td><input type="text" name="phone" value="<?=$this->data['phone']?>"></td>
			</tr>
			<tr>
				<td width="30%"><p>邮政编码：</p></td>
				<td><input type="text" name="zipcode" value="<?=$this->data['zipcode']?>"></td>
			</tr>
			<tr class="click-address">
				<td width="30%"><p>所在地区 :</p></td>
				<td class="sw" width="70%">
					<div class="n-addweb-sp-down">
						<!-- <ul style="width:100%;">
							<li>
								<select name="" id="">
									<option value="">某某省</option>
									<option value="">某某省</option>
								</select>
							</li>
							<li>
								<select name="" id="">
									<option value="">某某市</option>
									<option value="">某某市</option>
								</select>
							</li>
							<li>
								<select name="" id="">
									<option value="">某某区</option>
									<option value="">某某区</option>
								</select>
							</li>
						</ul> -->
						<div class="JS_Dmenu form-inline">
							<input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
							<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td width="30%"><p>具体：</p></td>
				<td><textarea name="address" id="" cols="30" rows="10"><?=$this->data['address']?></textarea></td>
			</tr>
		</table>
		<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
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

			seajs.use('/assets/js/validator/validator.sea.js', function(validator){
				validator('form', {
					rules: {
						'[name=consignee]': { valid: 'required', errorText: '请填写收件人姓名' },
						'[name=area_text]': { valid: 'required', errorText: '请选择省市区' },
						'[name=address]': { valid: 'required', errorText: '请填写收货地址' },
						'[name=zipcode]': { valid: 'required', errorText: '请填写邮编' },
						'[name=phone]': { valid: 'required|numeric', errorText: '请填写联系电话|请正确填写电话号码' }
					}
				});
			});
		</script>
	<?php } ?>
	<div class="n-addlist">
		<input value="完成" type="submit">
	</div>
</form>
</body>
<script>
$(function(){
	$(".n-shopping-hide").click(function(){
		$(this).parents(".n-shopping-box").remove();
	})
})
</script>
</html>
