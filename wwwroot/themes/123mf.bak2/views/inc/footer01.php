<!-- <div class="footer">
	<p>
		<a target="_blank" title="网站建设" href="http://www.bocweb.cn/">网站建设</a>：
		<a target="_blank" title="网站建设" href="http://www.bocweb.cn/">博采网络</a>
	</p>
</div> -->
<!-- <div class="n-h56"></div> -->
<div class="n-h148"></div>
<div class="mm-big">
	
	<!-- <div class="add-border">
		<ul>
			<li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=banr') ?> ';" class="li1">商城</li>
			<li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=banl') ?> ';" class="li2">积分</li>
			<li class="li3">创业</li>
			<li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('usercp/vip/apply') ?> ';" class="li4">商家入驻</li>
			<li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/goods/page?t=jump') ?> ';" class="li5">充值</li>
			<li class="li6"><a style="color:#fff;" href="http://wpa.qq.com/msgrd?v=3&uin=1392586315&site=qq&menu=yes">客服</a></li>
			<div class="ban-ra"></div>
		</ul>
	</div>  -->
	<div class="exex-box">
		<div class="ban-ra"></div>
		<div class="li1 lkl"><a href="<?php echo site_url('default/goods/page?t=banr'); ?> ">商城</a></div>
		<div class="li2 lkl"><a href="<?php echo site_url('default/goods/page?t=banl'); ?> ">积分</a></div>
		<div class="li3 lkl"><a href="">创业</a></div>
		<div class="li4 lkl"><a href="<?php echo site_url('usercp/vip/apply'); ?> ">商家入驻</a></div>
		<div class="li5 lkl"><a href="<?php echo site_url('default/goods/page?t=jump'); ?> ">充值</a></div>
		<div class="li6 lkl"><a href="http://wpa.qq.com/msgrd?v=3&uin=1392586315&site=qq&menu=yes">客服</a></div>
	</div>
	<div class="n-footer">
		<ul>
			<li>
				<a href="<?=$this->url('/default/index')?>"><img width="20" height="18" src="<?php echo static_file('mobile/img/img-03.png'); ?> " alt=""></a>
				<a href="<?=$this->url('/default/index')?>"><p>首页</p></a>
			</li>
			<li>
				<a href="<?=$this->url('/default/cart')?>"><img width="17" height="17" src="<?php echo static_file('mobile/img/img-04.png'); ?> " alt=""></a>
				<a href="<?=$this->url('/default/cart')?>"><p>购物车</p></a>
			</li>
			<!-- <li>
				<a href="<?php echo site_url('shopping/good_list'); ?> "><img width="23" height="17" src="<?php echo static_file('mobile/img/img-05.png'); ?> " alt=""></a>
				<a href="<?php echo site_url('shopping/good_list'); ?> "><p>我能购买</p></a>
			</li> -->
			<li>
				<a href="<?=$this->url('/usercp/index')?>"><img width="13" height="17" src="<?php echo static_file('mobile/img/img-06.png'); ?> " alt=""></a>
				<a href="<?=$this->url('/usercp/index')?>"><p>会员中心</p></a>
			</li>
			<li>
				<a href="<?=$this->url('/default/news/list?cid=14')?>"><img width="19" height="17" src="<?php echo static_file('mobile/img/img-07.png'); ?> " alt=""></a>
				<a href="<?=$this->url('/default/news/list?cid=14')?>"><p>消息</p></a>
			</li>
			<li class="n-footer-sp">
				<a href=""><img width="19" height="17" src="<?php echo static_file('mobile/img/img-07.png'); ?> " alt=""></a>
				<a href=""><p>如何赚钱</p></a>
			</li>
		</ul>
	</div>
</div>

<script>
	$(function(){
		$(".ban-ra").click(function(){
			if ($(this).hasClass('cur')) {
				$(".lkl").fadeOut();
				$(this).removeClass('cur');
			}else{
				$(".lkl").fadeIn();
				$(this).addClass('cur');
			}
		})
	})
</script>