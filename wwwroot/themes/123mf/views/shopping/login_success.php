<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <!--?php include_once VIEWS.'inc/header_shopping.php'; ?-->
    <div class="ik">
        <?php if ($_SERVER['HTTP_REFERER']) { ?>
        <span class="ikoj">
        <a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="<?php echo static_file('mobile/img/olk.png'); ?> " alt=""></a><!-- 返回 -->
        </span>
        <?php } ?>
        帮帮网：一个免费、特惠和创业的网站！
    </div>
    <div style="height:40px;overflow: hidden;"></div>
    <div class="bgwhite shoptips-box">
    	<dl class="w90 shop-tips">
    		<dt>温馨提示：本页面三个内容</dt>
    		<dd>一：推荐会员获取更多帮帮币</dd>
    		<dd>二：会员创业财富升级</dd>
    		<dd>三：会员确认登陆平台</dd>
    	</dl>
    </div>
    <div class="bgwhite">
    	<div class="logined-title ">
    		<p class="w90 icon01">一、推荐会员获取更多帮帮币</p>
    	</div>
	    <div class="logined-info">	
	    	<p class="w90">您的邀请码为<?=$this->user['mobile']?>，只要您推荐的朋友在注册时，邀请人栏里填写您的号码，您朋友成功在商城兑换商品后，您就可以获得<?=$this->setting['credit_reg']?>帮帮币，您朋友也得<?=$this->setting['credit_reg']?>帮帮币。</p>
		</div>
    </div>
    <div class="bgwhite">
    	<div class="logined-title ">
    		<p class="w90 icon02">二、会员创业财富升级</p>
    	</div>
		<dl class="shop-tip02">
			<dd>
				<p class="w90"><span class="fl">升级一星分销商</span><a class="fr" href="<?=$this->url('vip/level?t=1')?>">点击升级</a><p class="clear"></p></p>
			</dd>
			<dd>
				<p class="w90"><span class="fl">升级二星分销商</span><a class="fr" href="<?=$this->url('vip/level?t=2')?>">点击升级</a><p class="clear"></p></p>
			</dd>
			<dd>
				<p class="w90"><span class="fl">升级三星分销商</span><a class="fr" href="<?=$this->url('vip/level?t=3')?>">点击升级</a><p class="clear"></p></p>
			</dd>
			<dd>
				<p class="w90"><span class="fl">升级四星分销商</span><a class="fr" href="<?=$this->url('vip/level?t=4')?>">点击升级</a><p class="clear"></p></p>
			</dd>
		</dl>
    </div>
     <div class="bgwhite">
    	<div class="logined-title shop-tip03-title">
    		<p class="w90 icon03">三、创业升级条件分别如下：（把时间，关系，资源变财富）</p>
    	</div>
	    <div class="logined-info bgwhite">	
	    	<div class="w90">
	    		<p class="title">一星创业升级条件：</p>
	    		<p class="detail">会员升级为一星分销商，升级服务费为500元，同时可获得500帮帮币。会员升级后，可获得推荐的会员每次消费积分币的相应百分比提成，你同时也把各百业商家推荐给本台合作，你可获得更多的收益。(只要你介绍一个商家，月收几千元，甚至更多)。</p>
	    		<br />
				<p class="title">规则说明：</p>
	    		<p class="detail">A:当您推荐的朋友每次在消费积分币时，您可以获得相应的百分比提成。当你推荐的商家，每次消费你都有提成。并且有额外的更多的奖励。(帮帮网:帮你赚钱！让天下没有不成功的人)。</p>
	    	</div>
		</div>
    </div>
    <div class="logined-info bgwhite">	
	    	<div class="w90">
	    		<p class="title">二星创业升级条件：</p>
	    		<p class="detail">会员升级为一星分销商，升级服务费为5000元，同时可获得5000帮帮币。会员升级后，可获得推荐的会员每次消费积分币的相应百分比提成，你同时也把各百业商家推荐给本台合作，你有会员积分币的提成，你更可获得更多的收益。同时该星级还有更多更高的报酬，详情与客服联系！(只要你介绍一个商家，月收几千元，甚至更多)。</p>
	    		<br />
				<p class="title">规则说明：</p>
	    		<p class="detail">
					A:当您推荐的朋友每次在消费积分币时，您可以获得相应的百分比提成。
					<br><br>
					B：您推荐的朋友，他再推荐他的朋友，他的朋友在消费个人积分币时，你同样可以获得相应的百分比提成，并且可获的该商家所有帮帮币和积分币的提成。(帮帮网:让你行动一陈子，幸福一辈子)。
	    		</p>
	    	</div>
	</div>
	<div class="logined-info bgwhite">	
    	<div class="w90">
    		<p class="title">三星创业升级条件：</p>
    		<p class="detail">会员升级为一星分销商，升级服务费为20000元，同时可获得20000帮帮币。会员升级后，可获得推荐的会员每次消费积分币的相应百分比提成，你同时也把各百业商家推荐给本台合作，你有会员积分币的提成，你更可获得更多的收益。同时该星级还有更多更高的报酬，详情与客服联系！(只要你介绍一个商家，月收几千元，甚至更多)。</p>
    		<br />
			<p class="title">规则说明：</p>
    		<p class="detail">A:当您推荐的朋友每次在消费积分币时，您可以获得相应的百分比提成。
    			<br><br>
				B：您推荐的朋友，他再推荐他的朋友，他的朋友在消费个人积分币时，你同样可以获得相应的百分比提成，并且可获的该商家所有帮帮币和积分币的提成。(帮帮网:让你行动一陈子，幸福一辈子)。
    		</p>
    	</div>
	</div>
	<div style="margin-bottom:50px;" class="logined-info bgwhite">	
    	<div class="w90">
    		<p class="title">四星创业升级条件：</p>
    		<p class="detail">
				升级为四星分销商可获得更加广阔的（区域）创业平台！你的财富会象滚雪球一样倍增。
				<br /><br />
				这是一个比任何创业机会都更加有利的平台！（机会来了，你还在等什么！这里是您创业大发展的首选平台！马上升级，选占先机）。
				<br /><br />
				备注:以上的创业财富升级，可以说是你人生中最好的创富机会。即省时，省力，无风险，收益高的创业好机会。月收入几万是你收入的起步点，你想做一个即有钱，又有闲的幸福生活人生，创业请你选择帮帮网创业平台，这里会给你一个富有的人生和有成就的人生。帮帮网，大众创业，万众创新，你只要有(一点时间，一点关系，一点资源)，帮帮网平台帮你，让天下资源，为你所用，让你的财富取之不尽，用之不尽，这里创业是可以永续和可传承的事业。帮帮网团队欢迎你的加入，让我们一起走向成功。帮帮网:帮你赚钱！帮你免费！帮你做生意！让天下没有不成功的人！
				<br/><br/>
				(以上创业创富升级，详情请在创业财富升级里了解或与客服联系)。
    		</p>
    	</div>
	</div>
	<?php if(isset($_SESSION['confirm_login'])) {?>
    <div style="height:50px;width:100%;background:#ff6600;  position: fixed;bottom: 0px;left: 0px;text-align: center;" class="">
		<?php if($_COOKIE['ref_url']) {?>
			<a class="sure-platform" href="<?php echo $_COOKIE['ref_url']?>">会员确认登陆平台</a>
		<?php } else {?>
			<a class="sure-platform" href="<?php echo isset($_SESSION['confirm_login_url']) ? $this->url('/usercp/index'):$this->url('default')?>">会员确认登陆平台</a>
		<?php }?>
    </div>
	<?php }?>
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>







