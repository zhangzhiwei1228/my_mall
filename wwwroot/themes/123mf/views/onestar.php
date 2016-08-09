<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <div class="z-jifenheader bgcolor<">
		<div class="header-jifen">
			<div class="main w90">
				<?php switch($this->user['resale_grade']) {
					case 1: echo '一星分销商'; break;
					case 2: echo '二星分销商'; break;
					case 3: echo '三星分销商'; break;
					case 4: echo '四星分销商'; break;
				} ?>
			</div>
			<div class="hnshart">
				<img width="25" height="23" src="<?php echo static_file('mobile/img/icon.png'); ?> " alt="">
			</div>
		</div>
	</div>
	<div class="admin-box bgwhite">
		<div class="merc-admin w90">
<!--			<div class="pic fl"><img src="--><?php //echo static_file('m/img/pic17.png'); ?><!-- "></div>-->
			<div class="pic fl"><img src="<?php echo $this->baseUrl('uploads/avatar/6.png'); ?> "></div>
			<div class="intro fl">
				<p class="admin-p">
				<?php switch($this->user['resale_grade']) {
					case 1: echo '创业一星分销商'; break;
					case 2: echo '创业二星分销商'; break;
					case 3: echo '创业三星分销商'; break;
					case 4: echo '创业四星分销商'; break;
				} ?>
				</p>
				<p class="admin-name"><?=$this->user['username']?></p>
				<p class="login-info"><a href="<?=$this->url('passport/logout')?>">退出</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?=$this->url('security/reset_login_pwd')?>">修改密码</a></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="staff-jifen bgwhite"><p class="w90">创业财富再升级如下：</div>
	<?php if ($this->user['resale_grade'] < 2) { ?>
	<div class="star-upgrade bgwhite"><div class="w90"><p class="star-r fl"><em class="star01"></em>升级二星分销商</p><a href="<?=$this->url('vip/level?t=2')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>
	<?php if ($this->user['resale_grade'] < 3) { ?>
	<div class="star-upgrade bgwhite"><div class="w90"><p class="star-r fl"><em class="star02"></em>升级三星分销商</p><a href="<?=$this->url('vip/level?t=3')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>
	<?php if ($this->user['resale_grade'] < 4) { ?>
	<div class="star-upgrade bgwhite" style="margin-bottom:10px;"><div class="w90"><p class="star-r fl"><em class="star03"></em>升级四星分销商</p><a href="<?=$this->url('vip/level?t=4')?>" class="upgrade fr">点击升级</a></div></div>
	<?php } ?>

	<div class="staff-jifen bgwhite"><p class="w90">本月发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['last1']['vip']?></em>&nbsp;个</span></p></div>
    <div class="staff-jifen bgwhite"><p class="w90">历史发展的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的一级会员总数：<span class="fr"><em><?=(int)$this->bonus['history1']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">本月激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['last2']['vip']?></em>&nbsp;个</span></p></div>
    <div class="staff-jifen bgwhite"><p class="w90">历史发展的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['num']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">历史激活的二级会员总数：<span class="fr"><em><?=(int)$this->bonus['history2']['vip']?></em>&nbsp;个</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的一级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin1']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的二级会员消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin2']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<?php if ($this->user['resale_grade'] >= 2) { ?>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的一级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin3']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<div class="staff-jifen bgwhite"><p class="w90">我的商家的二级会员本月消费积分币：<span class="fr"><em><?=(float)$this->bonus['coin4']['credit_coin']['total']?></em>&nbsp;币</span></p></div>
	<?php } ?>
	<div class="staff-jifen bgwhite">
		<a href="<?=$this->url('/index/shoplist')?> ">
			<p class="w90">发展的商家本月使用免费积分(点击查看)：<span class="fr"><em><?=(float)$this->bonus['seller']['credit']['total']?></em>&nbsp;分</span></p>
		</a>
	</div>
	<div class="month-income bgwhite">
		<p class="income01 w90">我的本月收益</p>
		<p class="income01 w90"><span><?=$this->bonus['amount']?></span>&nbsp;元</p>
	</div>

	<div class="member-rank bgwhite">
		<p class="w90">一级会员：我直接邀请注册的会员</p>
		<p class="w90">二级会员：我的一级会员邀请注册的会员</p>
	</div>
	<div class="ffxx-box">
	    <div class="ffxx">
			<div class="ffxx-tips">
				<dl>
					<!-- <dd>分享作用：(可以把分享做为爱心分享和创业致富）</dd>
					<dd>1,你用下列工具分享给你朋友，然后你朋友点击注册并成功激活后（在免费商城首次兑换物品），你朋友可以获得20免费积分，同时你也获得20免费积分。</dd>
					<dd>2,如果你升级成为星级分销商，你推荐的朋友和你朋友推荐的朋友在本平台全球低价商城购物时消费的积分币，你将获得5%至10%的现金提成收益。</dd>
					<dd>分享工具使用方法：</dd>
					<dd>1，QQ：选择帐号密码登陆，登陆后选择好友点击发送</dd>
					<dd>2，QQ空间：输入空间账号密码，登陆后点击分享</dd>
					<dd>3，新浪微博：输入微博账号密码，登陆后点击分享</dd>
					<dd>4，腾讯微博：输入微博账号密码，登陆后点击分享</dd>
					<dd>5，微信：截图二维码，打开微信发送给好友</dd>
					<dd>6，朋友圈：点击即复制地址，打开微信粘贴分享</dd> -->
					<dd>
						分享致富：1、使用下列工具将网站分享给朋友，朋友通过您的链接注册并成功激活后，您可获得20免费积分，同时您朋友也获得20免费积分。2、升级成为星级分销商，分享可获得更多现金提成。
					</dd>
					<dd>1、QQ：选择帐号密码登陆，登陆后选择好友点击发送</dd>
					<dd>2、QQ空间、新浪微博、腾讯微博：输入帐号密码，登陆后直接点击分享</dd>
					<dd>3、微信：截图二维码，打开微信将图片发送好友扫一扫</dd>
					<dd>4、朋友圈：点击图标即复制分享链接，打开微信粘贴分享（苹果手机暂不支持，其它手机都可以）</dd>
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
	<input type="hidden" id="login_user_id" value="<?php echo $_SESSION['login_user_id'];?>" />
	<script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script>
    <?php //include_once VIEWS.'inc/footer_retailer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
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