<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<form class="n-personal-center" method="post">
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		个人中心
	</div>
	<div class="n-pic">
		<div class="n-head-pic"><img src="<?=$this->user['avatar']?$this->baseUrl($this->user['avatar']):static_file('mobile/img/img-23.png'); ?>" alt=""></div>
		<span><?=$this->user['nickname']?></span>
		<p>手机号：<?=$this->user['mobile']?></p>
	</div>
	<div class="n-personal-center-list">
		<div class="n-h44 end-n-h44"><a href="">账户信息</a></div>
		<ul class="clear">
			<li>
				<span class="sspan"><a href="<?php echo site_url('b_phone'); ?> ">绑定手机</a></span>
				<input class="gg-input" type="text" name="mobile" value="<?=$this->user['mobile']?>">
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">昵称</a></span>
				<input class="gg-input" type="text" name="nickname" value="<?=$this->user['nickname']?>">
			</li>
			<?php foreach($this->extFields as $name => $item) {
				list($type, $label, $opts) = $item;
			?>
			<li>
				<span class="sspan"><a href="javascript:;"><?=$label?></a></span>
				<input type="hidden" name="ext[<?=$name?>][name]" value="<?=$label?>">
				<?php if (trim($type) == 'text') { ?>
					<input type="text" name="ext[<?=$name?>][value]" value="<?=$this->user->getExtField($name)?>" class="gg-input">
				<?php } elseif (trim($type) == 'select') { ?>
					<select name="ext[<?=$name?>][value]" class="gg-input">
						<?php
						foreach($opts as $v) { ?>
						<option <?=$this->user->getExtField($name) == trim($v)?'selected':''?>><?=trim($v)?></option>
						<?php } ?>
					</select>
				<?php } elseif (trim($type) == 'textarea') { ?>
					<textarea name="ext[<?=$name?>][value]" class="gg-input" rows="4"><?=$this->user->getExtField($name)?></textarea>
				<?php } elseif (trim($type) == 'birthday') { ?>
				<?php list($y,$m,$d) = explode(',', $this->user->getExtField($name)); ?>
					<select name="ext[<?=$name?>][value][year]" class="g-select">
						<?php $year = date('Y');
						for($i=$year; $i>=$year-100; $i--) { ?>
						<option value="<?=$i?>" <?=$i==$y ? 'selected' : ''?>><?=$i?>年</option>
						<?php } ?>
					</select>
					<select name="ext[<?=$name?>][value][month]" class="g-select">
						<?php for($i=1; $i<=12; $i++) { ?>
						<option value="<?=$i?>" <?=$i==$m ? 'selected' : ''?>><?=$i?>月</option>
						<?php } ?>
					</select>
					<select name="ext[<?=$name?>][value][day]" class="g-select">
						<?php for($i=1; $i<=31; $i++) { ?>
						<option value="<?=$i?>" <?=$i==$d ? 'selected' : ''?>><?=$i?>号</option>
						<?php } ?>
					</select>
				<?php } elseif (trim($type) == 'gender') { ?>
					<span style="width:75%;">
					<?php
					foreach($opts as $v) { ?>
					<span style="width:20%;line-height:40px;" class="fl">
					<input type="radio" name="ext[<?=$name?>][value]" value="<?=trim($v)?>" <?=$this->user->getExtField($name)==trim($v)?'checked':''?>/> <?=trim($v)?>
					</span>
					<?php } ?>
					</span>
				<?php } elseif (trim($type) == 'area') { ?>
				<span class="form-inline JS_Dmenu">
					<input type="hidden" name="ext[<?=$name?>][value]" value="<?=$this->user->getExtField($name)?>" />
				</span>
				<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
				<script>
					var avId = new Array();
					var str_avId;
					var k;
					seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
						dmenu.init('.JS_Dmenu', {
							rootId: 1,
							script: '/misc.php?act=area',
							htmlTpl: '<select class="g-select"></select>',
							firstText: '请选择所在地',
							defaultText: '请选择',
							selected: $('input[name="ext[<?=$name?>][value]"]').val(),
							callback: function(el, data) {
								var location = $('.JS_Dmenu>select>option:selected').text();
								$('input[name="ext[<?=$name?>][value]"]').val(location);
								$('#area_val').val(data.id);
							}
						});
					});
				</script>
				<?php } ?>
			</li>
			<?php } ?>
			<input name="areas" id="area_val" type="hidden" />
		</ul>
	</div>
	<div style="height:50px;width:100%;overflow: hidden;" class="h-50"></div>
	<div class="tt-end"><input value="保 存" type="submit"></div>
</form>
</body>
</html>