<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
<style type="text/css">
	/*.n-dealer-list{width: 100%;float: none;}
	.n-dealer-list li{float: none;width: 100%;}*/
</style>
</head>
<body style="background:#ebebeb;">
<div class="n-proxy">
	<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			<?php if ($this->parent['role'] == 'agent') { ?>
			代理商员工
			<?php } elseif ($this->parent['role'] == 'seller') { ?>
			商家员工
			<?php } elseif ($this->parent['role'] == 'resale') { ?>
			创业四星分销商员工
			<?php } ?>
			<div class="hnshart">
				<img width="25" height="23" src="<?php echo static_file('mobile/img/icon.png'); ?> " alt="">
			</div>
	</div>
	<div style="background:#fff;" class="n-proxy-pic clear">
			<div class="n-dealer-head">
<!--				<div class="n-dealer-head-info"><img src="--><?php //echo static_file('mobile/img/img-23.png'); ?><!-- " alt=""></div>-->
				<div class="n-dealer-head-info"><img src="<?php echo $this->baseUrl('uploads/avatar/6.png'); ?> " alt=""></div>
			</div>
			<?php if ($this->parent['role'] == 'agent') { ?>
			<span>创业代理商员工</span>
			<?php } elseif ($this->parent['role'] == 'seller') { ?>
			<span>创业商家员工</span>
			<?php } elseif ($this->parent['role'] == 'resale') { ?>
			<span>创业四星分销商员工</span>
			<?php } ?>
			<p><?=$this->user['username']?></p>
			<div class="n-proxy-sp">
				<a class="n-proxy-spa" href="<?=$this->url('passport/logout')?>">退出</a>
				<a class="n-proxy-spa1" href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a>
			</div>
			
	</div>
	<div style="background:#fff;" class="n-dealer-list">
		<ul class="clear">
			<li>
				<span class="n-dealer-span1">本月发展的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last1']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月激活的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last1']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史发展的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history1']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史激活的一级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history1']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月发展的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last2']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">本月激活的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['last2']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史发展的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history2']['num']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">历史激活的二级会员总数：</span>
				<span class="n-dealer-span3">个</span>	
				<span class="n-dealer-span2"><?=(int)$this->bonus['history2']['vip']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的一级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin1']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的二级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin2']['credit_coin']['total']?></span>
			</li>

			<li>
				<span class="n-dealer-span1">我的一级会员商城消费使用抵用券：</span>
				<span class="n-dealer-span3">券</span>
				<span class="n-dealer-span2"><?=(float)$this->bonus['vouchers1']['vouchers']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的二级会员商城消费使用抵用券：</span>
				<span class="n-dealer-span3">券</span>
				<span class="n-dealer-span2"><?=(float)$this->bonus['vouchers2']['vouchers']['total']?></span>
			</li>
			<?php if ($this->parent['role'] == 'agent' || $this->parent['role'] == 'resale') { ?>
			<li>
				<span class="n-dealer-span1">我的商家的一级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin3']['credit_coin']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的商家的二级会员本月消费积分币：</span>
				<span class="n-dealer-span3">币</span>	
				<span class="n-dealer-span2"><?=(float)$this->bonus['coin4']['credit_coin']['total']?></span>
			</li>

			<li>
				<span class="n-dealer-span1">我的商家的一级会员商城消费使用抵用券：</span>
				<span class="n-dealer-span3">币</span>
				<span class="n-dealer-span2"><?=(float)$this->bonus['vouchers3']['vouchers']['total']?></span>
			</li>
			<li>
				<span class="n-dealer-span1">我的商家的二级会员商城消费使用抵用券：</span>
				<span class="n-dealer-span3">币</span>
				<span class="n-dealer-span2"><?=(float)$this->bonus['vouchers4']['vouchers']['total']?></span>
			</li>
			<li style="height:auto;line-height:20px;">
				<a href="<?=$this->url('/index/shoplist/?t=credit')?>" style="display:block;">
					<span class="n-dealer-span1">发展的商家本月使用免费积分(点击查看)：</span>
					<span class="n-dealer-span3">分</span>	
					<span class="n-dealer-span2"><?=(float)$this->bonus['seller']['credit']['total']?></span>
				</a>
			</li>
			<li style="height:auto;line-height:20px;">
				<a href="<?=$this->url('/index/shoplist/?t=vouchers')?>" style="display:block;">
					<span class="n-dealer-span1">我的商家本月赠送抵用券(点击查看)：</span>
					<span class="n-dealer-span3">券</span>
					<span class="n-dealer-span2"><?=(float)$this->bonus['seller_v']['vouchers']['total']?></span>
				</a>
			</li>
			<li style="height:auto;line-height:20px;">
				<a href="<?=$this->url('/index/shoplist/?t=worth_gold')?>" style="display:block;">
					<span class="n-dealer-span1">我的商家本月收到抵用金(点击查看)：</span>
					<span class="n-dealer-span3">金</span>
					<span class="n-dealer-span2"><?=(float)$this->bonus['seller_w']['worth_gold']['total']?></span>
				</a>
			</li>
			<?php } ?>
		</ul>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的本月收益</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1"><?=$this->bonus['amount']?></p>
				<p class="n-dealer-end-down-p2">元</p>
			</div>
		</div>
		<div class="n-proworker">
			<p style="color:#333;line-height:28px;">一级会员：我直接邀请注册的会员</p>
			<p style="color:#333;line-height:28px;">二级会员：我的一级会员邀请注册的会员</p>
		</div>
	</div>
</div>
<div class="ffxx-box">
	<div class="ffxx">
		<div class="ffxx-tips">
			<dl>
				<dd>分享作用：(可以把分享做为爱心分享和创业致富）</dd>
				<dd>1,你用下列工具分享给你朋友，然后你朋友点击注册并成功激活后（在免费商城首次兑换物品），你朋友可以获得20免费积分，同时你也获得20免费积分。</dd>
				<dd>2,如果你升级成为星级分销商，你推荐的朋友和你朋友推荐的朋友在本平台全球低价商城购物时消费的积分币，你将获得5%至10%的现金提成收益。</dd>
				<dd>分享工具使用方法：</dd>
				<dd>1，QQ：选择帐号密码登陆，登陆后选择好友点击发送</dd>
				<dd>2，QQ空间：输入空间账号密码，登陆后点击分享</dd>
				<dd>3，新浪微博：输入微博账号密码，登陆后点击分享</dd>
				<dd>4，腾讯微博：输入微博账号密码，登陆后点击分享</dd>
				<dd>5，微信：截图二维码，打开微信发送给好友</dd>
				<dd>6，朋友圈：点击即复制地址，打开微信粘贴分享</dd>
			</dl>
			<br><input type="text" value="<?php echo $this->url()."index.php/?invite_id=".$_SESSION['login_user_id'];?>" id="urlBox" style="opacity: 0; height: 1px;">
		</div>
		<!-- JiaThis Button BEGIN -->
		<div class="jiathis_style_32x32 f-cb">
			<a class="jiathis_button_cqq" onmouseover="setShare('让我们一块分享赚钱', '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$this->user['id'];?>');">
				<p>QQ</p>
			</a>
			<a class="jiathis_button_qzone" onmouseover="setShare('让我们一块分享赚钱', '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$this->user['id'];?>');">
				<p>QQ空间</p>
			</a>
			<a class="jiathis_button_weixin" onmouseover="setShare('让我们一块分享赚钱', '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$this->user['id'];?>');">
				<p>微信</p>
			</a>
			<a class="jiathis_button_tsina" onmouseover="setShare('让我们一块分享赚钱', '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$this->user['id'];?>');">
				<p>新浪微博</p>
			</a>
			<a class="jiathis_button_tqq" onmouseover="setShare('让我们一块分享赚钱', '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$this->user['id'];?>');">
				<p>腾讯微博</p>
			</a>
		</div>
		<div class="friend">
			<span></span>
			<p>朋友圈</p>
		</div>
	</div>
	</div>
<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
</body>
<script type="text/javascript">
	function setShare(title, url) {
		jiathis_config.title = title;
		jiathis_config.url = url;
	}
	var jiathis_config = {}
	//朋友圈
	$('.friend').click(function(){
		var url = '<?php echo 'http://'.$_SERVER['HTTP_HOST']."/index.php/?invite_id=".$_SESSION['login_user_id'];?>';
		copyToClipboard(document.getElementById("urlBox"));
		$('.login-tips').text('复制成功').show();
		setTimeout(function(){
			$('.login-tips').hide();
		},800);
	});
	function copyToClipboard(elem) {
		// create hidden text element, if it doesn't already exist
		var targetId = "_hiddenCopyText_";
		var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
		var origSelectionStart, origSelectionEnd;
		if (isInput) {
			// can just use the original source element for the selection and copy
			target = elem;
			origSelectionStart = elem.selectionStart;
			origSelectionEnd = elem.selectionEnd;
		} else {
			// must use a temporary form element for the selection and copy
			target = document.getElementById(targetId);
			if (!target) {
				var target = document.createElement("textarea");
				target.style.position = "absolute";
				target.style.left = "-9999px";
				target.style.top = "0";
				target.id = targetId;
				document.body.appendChild(target);
			}
			target.textContent = elem.textContent;
		}
		// select the content
		var currentFocus = document.activeElement;
		target.focus();
		target.setSelectionRange(0, target.value.length);

		// copy the selection
		var succeed;
		try {
			succeed = document.execCommand("copy");
		} catch(e) {
			succeed = false;
		}
		// restore original focus
		if (currentFocus && typeof currentFocus.focus === "function") {
			currentFocus.focus();
		}

		if (isInput) {
			// restore prior selection
			elem.setSelectionRange(origSelectionStart, origSelectionEnd);
		} else {
			// clear temporary content
			target.textContent = "";
		}
		return succeed;
	}
</script>
<script>
	var jiathis_config={
		summary:"",
		shortUrl:false,
		hideMore:false
	}
	$(".ffxx-box").click(function(){
        $(this).hide();
    })

   	$(".hnshart").click(function(){
    	//console.log('jjd');
    	$(".ffxx-box").show();
    })
</script>
</html>