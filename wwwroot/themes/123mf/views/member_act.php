<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<?php
	$t = array(
		1 => '一星分销商',
		2 => '二星分销商',
		3 => '三星分销商',
		4 => '四星分销商',
		5 => '申请商家入驻',
		6 => '申请代理商',
	);
	?>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		<?=$t[$this->_request->vip]?>
	</div>
	<div class="n-member-banner">
		<img src="<?php echo static_file('mobile/img/img-19.jpg'); ?> " alt="">
	</div>
	<form method="post">
	<div class="member_act">
		<ul>
			<?php if ($this->_request->vip == 5) {?>
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
				<span class="sp1">所在地址</span>
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
					<input style="width:auto;" class="fl" type="radio" name="day_volume_stat" value="很好">
					很好
				</div>
				<div class="fl fll">
					<input style="width:auto;" class="fl" type="radio" name="day_volume_stat" value="好">
					好
				</div>
				<div class="fl fll">
					<input style="width:auto;" class="fl" type="radio" name="day_volume_stat" value="一般">
					一般
				</div>
				<div class="fl fll">
					<input style="width:auto;" class="fl" type="radio" name="day_volume_stat" value="差">
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
			<?php } else { ?>
			<li style="width: 100%">
				<span class="sp1">所在地址</span>
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
				<span class="sp1">联系人</span>
				<input class="fl maxlength" type="text" name="contact">
			</li>
			<li>
				<span class="sp1">联系电话</span>
				<input class="fl ijk" type="text" name="phone">
			</li>
			<li>
				<span class="sp1">客户留言</span>
				<input class="fl" type="text" name="remark" placeholder="原有星级">
			</li>
			<?php } ?>
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
			htmlTpl: '<select class="form-control" style="width:auto; margin-right:0px; float: none"></select>',
			firstText: '请选择',
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

	$(function(){
		$(".tt-end").click(function(){
			if ($(".maxlength").val() == "") {
				alert("请输入姓名")
				return false;
			}
			var myreg = /^((1[3-8][0-9]{1})+\d{8})$/;
			if(!myreg.test($(".ijk").val()))
			{
				alert('请输入有效的手机号码！');
				return false;
			}
		})
	})

</script>
</html>