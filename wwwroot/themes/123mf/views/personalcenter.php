<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php' ?>
</head>
<body>
<div class="n-personal-center">
	<div style="position:fixed;top:0px;left:0px;" class="n-personal-center-tit">
		<!--javascript:(history.back());-->
		<a href="<?php echo $this->url('default')?>"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png')?> " alt=""></a>
		个人中心
	</div>
	<div style="height:57px;" class="w-57"></div>
	<div class="n-pic">
		<div class="n-head-pic"><img src="<?php echo $this->user['avatar'] ? $this->baseUrl($this->user['avatar']) : $this->baseUrl('uploads/avatar/6.png'); ?> " alt=""></div>
		<span><?=$this->user['nickname']?></span>
		<p>会员账号：<?=$this->user['mobile']?></p>
	</div>
	<div class="n-personal-center-list">
		<ul class="clear">
			<li onmouseover="this.style.cursor='pointer'" onclick="document.location='<?=$this->url('default/news/list?cid=15')?>';">
				<img src="<?php echo static_file('mobile/img/img-24.png')?> " alt="">
				<span style="width:50px;"><a href="<?=$this->url('default/news/list?cid=15')?>">通知</a></span>
				<?php if($this->news) {?>
					<div class="per-sonnum"><?php echo $this->news?></div>
				<?php }?>
				<a class="n-list-end" href=""></a>
			</li>
			<!-- <li>
				<img src="<?php echo static_file('mobile/img/img-26.png')?> " alt="">
				<span><a href="<?=$this->url('money/recharge?t=balance')?> ">在线充值</a></span>
				<a class="n-list-end" href="<?=$this->url('money/recharge?t=1')?>"></a>
			</li> -->
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-26.png')*/?> " alt="">
				<span><a href="<?/*=$this->url('money/recharge?t=credit_happy')*/?> ">快乐积分充值</a></span>
				<a class="n-list-end" href="<?/*=$this->url('money/recharge?t=credit_happy')*/?>"></a>
			</li>-->
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-26.png'); */?> " alt="">
				<span><a href="<?/*=$this->url('money/recharge?t=credit')*/?>">免费积分充值</a></span>
				<a class="n-list-end" href="<?/*=$this->url('money/recharge?t=credit')*/?>"></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('mobile/img/img-26.png'); ?> " alt="">
				<span><a href="<?=$this->url('money/recharge?t=credit_coin')?>">积分币充值</a></span>
				<a class="n-list-end" href="<?=$this->url('money/recharge?t=credit_coin')?>"></a>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-26.png'); */?> " alt="">
				<span><a href="<?php /*echo site_url('webrecharge'); */?> ">抵用卷充值</a></span>
				<a class="n-list-end" href="<?php /*echo site_url('webrecharge'); */?>"></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('mobile/img/img-27.png')?> " alt="">
				<span><a href="<?=$this->url('credit')?> ">积分转换</a></span>
				<a class="n-list-end" href="<?=$this->url('credit')?> "></a>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('m/img/pic/img_7.jpg')*/?> " alt="">
				<span><a href="<?/*=$this->url('credit')*/?> ">抵用劵转换</a></span>
				<a class="n-list-end" href="<?/*=$this->url('credit')*/?> "></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('m/img/pic/img_7.jpg')?> " alt="">
				<span><a href="<?=$this->url('/usercp/money/purchase')?> ">抵用金购买</a></span>
				<a class="n-list-end" href="<?=$this->url('/usercp/money/purchase')?> "></a>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('mobile/img/img-28.png')*/?> " alt="">
				<span><a href="<?/*=$this->url('coupon')*/?> ">抵用卷转换</a></span>
				<a class="n-list-end" href="<?/*=$this->url('coupon')*/?> "></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('mobile/img/img-29.png')?> " alt="">
				<span><a href="<?=$this->url('money')?> ">我的余额</a></span>
				<a class="n-list-end" href="<?=$this->url('money')?> "></a>
			</li>
		</ul>
		<div class="n-h44"><a href="">会员创业</a></div>
		<ul class="clear">
			<li>
				<img src="<?php echo static_file('mobile/img/img-30.png')?> " alt="">
				<span><a href="<?=$this->url('vip')?> ">会员激活</a></span>
				<a class="n-list-end" href="<?=$this->url('vip')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-31.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=1')?> ">升级一星分销商</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=1')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-32.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=2')?> ">升级二星分销商</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=2')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-33.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=3')?> ">升级三星分销商</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=3')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-34.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=4')?> ">升级四星分销商</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=4')?>"></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-34.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=6')?>">申请代理商</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=6')?>"></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-34.png')?> " alt="">
				<span><a href="<?=$this->url('vip/level?t=5')?>">申请商家入驻</a></span>
				<a class="n-list-end" href="<?=$this->url('vip/level?t=5')?>"></a>
			</li>
		</ul>
		<div class="n-h44"><a href="">个人管理</a></div>
		<ul class="clear">
			<li>
				<img src="<?php echo static_file('mobile/img/img-35.png')?> " alt="">
				<span><a href="<?=$this->url('order')?> ">我的订单</a></span>
				<a class="n-list-end" href="<?=$this->url('order')?> "></a>
			</li>
			<!--<li>
				<img src="<?php /*echo static_file('m/img/pic/img_8.jpg')*/?> " alt="">
				<span><a href="<?/*=$this->url('order')*/?> ">抵用金兑换记录</a></span>
				<a class="n-list-end" href="<?/*=$this->url('order')*/?> "></a>
			</li>-->
			<li>
				<img src="<?php echo static_file('mobile/img/img-04.png'); ?>" alt="" style="background-color: red">
				<span><a href="<?=$this->url('default/cart/default')?> ">我的购物车</a></span>
				<a class="n-list-end" href="<?=$this->url('default/cart/default')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-37.png')?> " alt="">
				<span><a href="<?=$this->url('account/profile')?>">账户信息</a></span>
				<a class="n-list-end" href="<?=$this->url('account/profile')?>"></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-38.png')?> " alt="">
				<span><a href="<?=$this->url('security/reset_login_pwd')?> ">密码修改</a></span>
				<a class="n-list-end" href="<?=$this->url('security/reset_login_pwd')?> "></a>
			</li>
			<li>
				<img src="<?php echo static_file('mobile/img/img-39.png')?> " alt="">
				<span><a href="<?=$this->url('address')?> ">收货地址管理</a></span>
				<a class="n-list-end" href="<?=$this->url('address')?> "></a>
			</li>
			<!-- <li>
				<img src="<?php echo static_file('mobile/img/img-39.png')?> " alt="">
				<span><a href="<?=$this->url('address')?> ">客户留言</a></span>
				<a class="n-list-end" href="<?=$this->url('address')?> "></a>
			</li> -->

		</ul>
		<div class="n-h44"><a href="">帮助说明</a></div>
			<ul style="width:100%;" class="clear">
			<?php 
			$list = M('Article')->select()
				->where('category_id = 1')
				->order('id ASC')
				->fetchRows();
			foreach($list as $row) { ?>
			<li>
				<img src="<?php echo static_file('mobile/img/img-40.png')?> " alt="">
				<span><a href="<?=$this->url('default/news/detail?id='.$row['id'])?>"><?=$row['title']?></a></span>
				<a class="n-list-end" href="<?=$this->url('default/news/detail?id='.$row['id'])?>"></a>
			</li>
			<?php } ?>
			<!-- <li>
				<img src="<?php echo static_file('mobile/img/img-41.png')?> " alt="">
				<span><a href="">权益保护</a></span>
				<a class="n-list-end" href=""></a>
			</li> -->
		</ul>
	</div>
</div>

<div class="n-h56"></div>
	<div class="tt-end"><a href="<?=$this->url('passport/logout')?>" style="color:#fff">退出当前账户</a></div>
</body>
</html>