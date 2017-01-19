<div class="footer">
<!-- <div class="click-top"></div> -->
	<div class="footer-t w1190">
		<ul>
			<li style="background:url(<?php echo static_file('web/img/img-04.png'); ?> ) no-repeat 48px center"><a href="">关于我们</a></li>
			<li style="background:url(<?php echo static_file('web/img/img-05.png'); ?> ) no-repeat 48px center"><a href="">联系我们</a></li>
			<li style="background:url(<?php echo static_file('web/img/img-06.png'); ?> ) no-repeat 48px center"><a href="<?php echo site_url('cooperation_shop/cooperation_shop'); ?> ">合作加盟</a></li>
			<li style="background:url(<?php echo static_file('web/img/img-07.png'); ?> ) no-repeat 48px center"><a href="">购物积分</a></li>
			<li style="background:url(<?php echo static_file('web/img/img-08.png'); ?> ) no-repeat 48px center"><a href="">人才加盟</a></li>
		</ul>
	</div>
	<div class="footer-d w1190">
		<ul class="clear">
			<li>
				<span>新手入门</span>
				<a href="<?php echo site_url('text'); ?> ">积分说明</a>
				<a href="<?php echo site_url('text'); ?> ">发票制度</a>
				<a href="<?php echo site_url('text'); ?> ">服务承诺</a>
				<a href="<?php echo site_url('text'); ?> ">购物流程</a>
			</li>
			<li>
				<span>支付方式</span>
				<a href="">充值账户</a>
				<a href="">积分商城</a>
			</li>
			<li>
				<span>配送方式</span>
				<a href="<?php echo site_url('text'); ?> ">配送方式</a>
				<a href="<?php echo site_url('text'); ?> ">配送时间</a>
				<a href="<?php echo site_url('text'); ?> ">如何签收</a>
			</li>
			<li>
				<span>我的账户</span>
				<a href="">账户管理</a>
				<a href="">资金管理</a>
				<a href="">免责声明</a>
			</li>
			<li>
				<span>销售后服务</span>
				<a href="<?php echo site_url('text'); ?> ">退换货原则</a>
				<a href="<?php echo site_url('text'); ?> ">退换货流程</a>
			</li>
			<li>
				<span>帮助信息</span>
				<a href="<?php echo site_url('text'); ?> ">正品保证</a>
				<a href="<?php echo site_url('text'); ?> ">注册协议</a>
			</li>
		</ul>
		<div class="wel-num">
			<span>400-100-0000</span>
			<p>周一至周日8:00-18:00</p>
			<p>(仅收市话费)</p>
			<a><img src="<?php echo static_file('web/img/img-03.jpg'); ?> " alt=""></a>
		</div>
	</div>
	<!-- <p><a target="_blank" title="网站建设" href="http://www.bocweb.cn/" class="t-c">网站建设</a>：<a target="_blank" title="网站建设" href="http://www.bocweb.cn/" class="t-c">博采网络</a></p> -->
</div>
<div style="float:center; font-size:12px; margin-top:8px; margin-bottom:8px; text-align:center; margin-right:13px; display:none"><?php echo isset($site_description)?$site_description:''?></div>
<script>
	$(function(){
		$(".footer-t li").eq(4).find('a').css("background","none");

		
	})
</script>