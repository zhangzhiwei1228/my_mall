<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="<?=$this->url($_SERVER['HTTP_REFERER'])?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		个人中心
	</div>
	<div class="n-member-banner">
		<img src="<?php echo static_file('mobile/img/img-19.jpg'); ?> " alt="">
	</div>
	<form method="post">
	<div class="member_act">
		<ul>
			<li>
				<span class="sp1">公司或商家名称</span>
				<input class="fl" type="text" name="company">
			</li>
			<li>
				<span class="sp1">经营类别</span>
				<select name="type" id="">
					<option value="酒店">酒店</option>
					<option value="酒店">酒店</option>
				</select>
			</li>
			<li>
				<span class="sp1">场地面积</span>
				<span class="fr">平方米</span>
				<input class="fr" type="text" name="area">
			</li>
			<li>
				<span class="sp1">经营地址</span>
				<div class="JS_Dmenu">
					<input type="hidden" name="location" value="<?=$this->data['location']?>" />
					<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
				</div>
			</li>
			<li>
				<span class="sp1">详情地址</span>
				<input class="fl" type="text" name="address">
			</li>
			<li>
				<span class="sp1">日营业额</span>
				<span class="fr">元</span>
				<input class="fr" type="text" name="day_selas">
			</li>
			<li>
				<span class="sp1">日客流量</span>
				<span class="fr">人</span>
				<input class="fr" type="text" name="day_volume">
			</li>
			<li>
				<span class="sp1">日客流量</span>
				<div class="fl fll">
					<input class="fl" type="radio" name="day_volume_stat" value="很好">
					很好
				</div>
				<div class="fl fll">
					<input class="fl" type="radio" name="day_volume_stat" value="好">
					好
				</div>
				<div class="fl fll">
					<input class="fl" type="radio" name="day_volume_stat" value="一般">
					一般
				</div>
				<div class="fl fll">
					<input class="fl" type="radio" name="day_volume_stat" value="差">
					差
				</div>
			</li>
			<li>
				<span class="sp1">联系人</span>
				<input class="fl" type="text" name="contact">
			</li>
			<li>
				<span class="sp1">联系电话</span>
				<input class="fl" type="text" name="phone">
			</li>
			<li>
				<span class="sp1">客户留言</span>
				<input class="fl" type="text" name="remark">
			</li>
		</ul>
	</div>
	<div class="tt-end"><input value="提交" type="submit"></div>
	</form>
</body>
<script type="text/javascript" src="/assets/js/seajs/sea.js"></script>
<script type="text/javascript">
	seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
		dmenu.init('.JS_Dmenu', {
			rootId: 1,
			script: '/misc.php?act=area',
			htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px; float: none"></select>',
			firstText: '请选择所在地',
			defaultText: '请选择',
			selected: $('input[name=area_id]').val(),
			callback: function(el, data) { 
				var location = $('.JS_Dmenu>select>option:selected').text();
				$('input[name=area_id]').val(data.id > 0 ? data.id : 0); 
				$('input[name=location]').val(location);
				$('input[name=zipcode]').val(data.zipcode > 0 ? data.zipcode : '');
			}
		});
	});

</script>
</html>