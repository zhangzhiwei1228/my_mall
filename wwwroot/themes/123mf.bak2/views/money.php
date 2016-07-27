<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
<div class="n-money">
	<div class="n-personal-center-tit">
		<a href="<?=$this->url('/usercp')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		我的消费记录
	</div>
	<div class="n-money-name">
		<span>订单号</span>
		<a href=""><img src="<?php echo static_file('mobile/img/img-25.png'); ?> " alt=""></a>
		<p>周星星</p>
	</div>
	<div class="n-rechargerecord-day">
		<span>日期</span><input value="日期插件" type="text"><span>至</span><input value="日期插件" type="text">
	</div>
	<div class="n-rechargerecord-sub">
		<input value="查询" type="submit">
	</div>
	<div class="n-money-box">
		<div class="n-money-pro">
			<div class="n-money-pro-top">
				<span>订单编号：56598765</span>
				<p>已付款</p>
			</div>
			<div class="n-money-pro-m">
				<div class="n-shopping-box-down">
					<div class="n-shopping-down-img">
						<img src="<?php echo static_file('mobile/img/img-18.jpg'); ?> " alt="">
					</div>
					<div class="n-shopping-down-te">
						<a href=""><p class="n-shopping-down-te1">楼兰蜜语玫瑰红葡萄干225g新疆特产无核提子红葡萄</p></a>
						<p class="n-shopping-down-te2">x 1</p>
						<p class="n-shopping-down-te3">2015-09-09</p>
					</div>
				</div>
			</div>
			<div class="n-money-pro-down">
				<span>订单时间 :</span><p>2015-09-09</p>
			</div>
		</div>
		<div class="n-money-pro">
			<div class="n-money-pro-top">
				<span>订单编号：56598765</span>
				<p>已付款</p>
			</div>
			<div class="n-money-pro-m">
				<div class="n-shopping-box-down">
					<div class="n-shopping-down-img">
						<img src="<?php echo static_file('mobile/img/img-18.jpg'); ?> " alt="">
					</div>
					<div class="n-shopping-down-te">
						<a href=""><p class="n-shopping-down-te1">楼兰蜜语玫瑰红葡萄干225g新疆特产无核提子红葡萄</p></a>
						<p class="n-shopping-down-te2">x 1</p>
						<p class="n-shopping-down-te3">2015-09-09</p>
					</div>
				</div>
			</div>
			<div class="n-money-pro-m">
				<div class="n-shopping-box-down">
					<div class="n-shopping-down-img">
						<img src="<?php echo static_file('mobile/img/img-18.jpg'); ?> " alt="">
					</div>
					<div class="n-shopping-down-te">
						<a href=""><p class="n-shopping-down-te1">楼兰蜜语玫瑰红葡萄干225g新疆特产无核提子红葡萄</p></a>
						<p class="n-shopping-down-te2">x 1</p>
						<p class="n-shopping-down-te3">2015-09-09</p>
					</div>
				</div>
			</div>
			<div class="n-money-pro-down">
				<span>订单时间 :</span><p>2015-09-09</p>
			</div>
		</div>
	</div>
	<div class="n-money-show">显示更多</div>
</div>
</body>
<script>
	$(function(){
		var NewUrl = "<?php echo site_url('ajax/money-ajax');?>"
        //新闻加载更多
        $(function () {
            var p = 1; //记录第几页
            $('.n-money-show').click(function () {
            p += 1; //下一页
        //加载下一页
            $.ajax({
            url: NewUrl,
            data: { page: p },
            cache: false,
            dataType: 'html',
        beforeSend: function(){
            $(".n-money-box").html();
        },
            success: function (html) {
            $(".n-money-box").append(html);
        	}
        });
        return false;
            });
        });
       
	})
</script>
</html>