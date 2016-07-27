<div class="n-h60"></div>
<ul class="footer-merchants footer-jifen">
	<li>
		<a href="<?=$this->url('credit')?> ">
			<img src="<?php echo static_file('m/img/icon15.png'); ?> ">
			<p>积分赠送</p>
		</a>
	</li>
	<li >
		<a href="<?=$this->url('index')?> ">
			<img src="<?php echo static_file('m/img/icon16.png'); ?> ">
			<p>我的收益</p>
		</a>
	</li>
	<li>
		<a href="<?=$this->url('staff')?> ">
			<img src="<?php echo static_file('m/img/icon17.png'); ?> ">
			<p>员工管理</p>
		</a>
	</li>
</ul>
<div class="clear"></div>
<script>
	$('.footer-buying-box li').eq(2).css('borderRight','none');
</script>