<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-trans">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			抵用卷转换
		</div>
		<div class="n-recharge-pic clear">
			<div class="n-recharge-head">
				<div class="n-recharge-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
			</div>
			<span>我的昵称</span>
			<p>手机号：12568964321</p>
		</div>
		<div class="n-recharge-pic-te">
			<p style="color:#b40000;font-size:14px;">转换说明：</p>
			<p style="color:#555;">积分可按<font style="color:#b40000;">3 : 1</font>转换成积分币，<font style="color:#b40000;">10000</font>积分起转按<font style="color:#b40000;">1000</font>整数倍增加。例<font style="color:#b40000;">12000</font>积分可转换成<font style="color:#b40000;">6000</font>积分币。</p>
		</div>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的抵用卷余额</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1">10000</p>
				<p class="n-dealer-end-down-p3">卷</p>
			</div>
		</div>	
		<div class="n-h5"></div>
		<div class="n-recharge-sp">
			<p>输入转换抵用卷</p>		
			<a href=""></a>
			<input type="text">
		</div>
		<div class="n-h1"></div>
		<div class="n-trans-chose">
			<div class="n-trans-chose-top">
				<label><span data-price="100" class="n-trans-1"><input name="name" type="radio" value="">获得免费积分</span></label>
				<label><span data-price="200" class="n-trans-2"><input name="name" type="radio" value="">获得积分币</span></label>
				<label><span data-price="300" class="n-trans-3"><input name="name" type="radio" value="">获得商城现金</span></label>
			</div>
			<div class="n-trans-chose-down">
				
			</div>
		</div>
		<div class="n-t-money-submit">
			<input value="我要转换" type="submit">
		</div>
	</div>
</body>
<script>
	$(function(){
		$(".n-trans-chose-top span").click(function(){
			$(".n-trans-chose-down").html($(this).attr("data-price"));
		});
	})
</script>
</html>