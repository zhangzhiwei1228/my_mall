<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-personal-center">
	<div class="n-personal-center-tit">
		<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		个人中心
	</div>
	<div class="n-pic">
		<div class="n-head-pic"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
		<span>我的昵称</span>
		<p>手机号：12568964321</p>
	</div>
	<div class="n-personal-center-list">
		<div class="n-h44 end-n-h44"><a href="">账户信息</a></div>
		<ul class="clear">
			<li>
				<span class="sspan"><a href="<?php echo site_url('b_phone'); ?> ">绑定手机</a></span>
				<input class="gg-input" type="text">
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">昵称</a></span>
				<input class="gg-input" type="text">
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">姓名</a></span>
				<input class="gg-input" type="text">
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">性别</a></span>
				<span style="width:75%;">
					<span style="width:20%;line-height:40px;" class="fl"><input class="ra-sp" type="radio">男</span>
					<span style="width:20%;line-height:40px;" class="fl"><input class="ra-sp" type="radio">女</span>
				</span>
				<!-- <input class="gg-input" type="text"> -->
			</li>
			<li>
				<span class="sspan"><a href="javascript:;">所在地区</a></span>
				<select class="g-select" name="" id="">
					<option value="">浙江省</option>
				</select>
				<select class="g-select" name="" id="">
					<option value="">杭州市</option>
				</select>
				<select class="g-select" name="" id="">
					<option value="">西湖区</option>
				</select>
			</li>
		</ul>
	</div>
	<div style="height:50px;width:100%;overflow: hidden;" class="h-50"></div>
</div>
<div class="tt-end"><input value="保 存" type="submit"></div>
</body>
</html>