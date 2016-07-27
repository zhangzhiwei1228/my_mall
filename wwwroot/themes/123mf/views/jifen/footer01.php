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
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url('default/goods/page?t=banr'); ?> ';" class="li1 lkl"><a href="<?php echo site_url('default/goods/page?t=banr'); ?> ">商城</a></div>
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url('default/goods/page?t=banl'); ?> ';" class="li2 lkl"><a href="<?php echo site_url('default/goods/page?t=banl'); ?> ">积分</a></div>
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url('usercp/passport/login?success=1'); ?> ';" class="li3 lkl"><a href="">创业</a></div>
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url('usercp/vip/level?t=6'); ?> ';" class="li4 lkl"><a href="<?php echo site_url('usercp/vip/level?t=6'); ?> ">商家入驻</a></div>
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='<?php echo site_url('default/goods/page?t=jump'); ?> ';" class="li5 lkl"><a href="<?php echo site_url('default/goods/page?t=jump'); ?> ">充值</a></div>
		<div onmouseover="this.style.cursor='pointer'" onclick="document.location='http://wpa.qq.com/msgrd?v=3&uin=1392586315&site=qq&menu=yes';" class="li6 lkl"><a href="http://wpa.qq.com/msgrd?v=3&uin=1392586315&site=qq&menu=yes">客服</a></div>
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
				<a href="<?=$this->url('/default/news/list?cid=14')?>"><img width="19" height="17" src="<?php echo static_file('mobile/img/ws-01.png'); ?> " alt=""></a>
				<a href="<?=$this->url('/default/news/list?cid=14')?>"><p>消息</p></a>
			</li>
			<li class="n-footer-sp">
				<a href="javascript:;"><img width="19" height="17" src="<?php echo static_file('mobile/img/icon.png'); ?> " alt=""></a>
				<a href="javascript:;"><p>分享赚钱</p></a>
			</li>
		</ul>
	</div>

	<div class="ffxx-box">
	    <div class="ffxx">
	        <!-- JiaThis Button BEGIN -->
	            <div class="jiathis_style_32x32">
	            <a class="jiathis_button_cqq">
	                <p>QQ</p>
	            </a>
	            <a class="jiathis_button_qzone">
	                <p>QQ空间</p>
	            </a>
	            <a class="jiathis_button_weixin">
	                <p>微信</p>
	            </a>
	            <a class="jiathis_button_tsina">
	                <p>新浪微博</p>
	            </a>
	            <a class="jiathis_button_tqq">
	                <p>腾讯微博</p>
	            </a>
	            </div>
	            <div class="friend">
	                <span></span>
	                <p>朋友圈</p>
	            </div>
	        <!-- JiaThis Button END -->
	    </div>
	</div>
</div>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
<script>
	$(function(){
		var jiathis_config={
			summary:"",
			shortUrl:false,
			hideMore:false
		}

		$(".ban-ra").click(function(){
			if ($(this).hasClass('cur')) {
				$(".lkl").fadeOut();
				$(this).removeClass('cur');
			}else{
				$(".lkl").fadeIn();
				$(this).addClass('cur');
			}
		})

		// $(document).mouseup("click",function(e){
  //           var _con = $('.ffxx');//这个目标区域就是弹框
  //           if(!_con.is(e.target) && _con.has(e.target).length === 0){
  //               $(".ffxx-box").fadeOut();
  //               $(".ffxx").fadeOut();
  //           }
  //       })
		$(".ffxx-box").click(function(){
        	$(this).hide();
        })

        $(".n-footer-sp").click(function(){
        	$(".ffxx-box").show();
        })
	})
</script>