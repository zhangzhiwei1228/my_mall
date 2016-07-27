<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php //include_once VIEWS.'inc/header.php'; ?>
    <div class="z-jifenheader bgcolor<">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			订单详情
		</div>
	</div>
	<div class="person-info">
		<div class="main w90">
			<em class="fl"></em>
			<div class="fl info-box">
				<p class="info01"><span>收货人：<?=$this->data['consignee']?></span><span class="fr"><?=$this->data['phone']?></span></p>
				<p class="info02">收货地址：<?=$this->data['area_text']?><?=$this->data['address']?></p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<p class="order-number bgwhite"><span class="w90">订单编号：<?=$this->data['code']?></span></p>
	<?php foreach($this->data->goods as $row) { ?>
	<div class="order-pro bgwhite">
		<div class="pro-info w90">
			<p class="pic fl"><img src="<?=$this->baseUrl($row['thumb'])?> " width="100%" /></p>
			<div class="intro fl">
				<p><?=$row['title']?></p>
				<p>x <?=$row['purchase_quantity']?></p>
				<p>价格：<span><?=$row['final_price']?></span>元</p>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<?php } ?>
	<div class="order-number bgwhite">
		<p class="w90">
			<span class="fl">订单时间 :<em><?=date(DATE_FORMAT,$this->data['create_time'])?></em></span>
			<span class="fr">支付状态 :<em><?php switch($this->data['status']) { 
							case 0: echo '关闭'; break; 
							case 1: echo '待付款'; break; 
							case 2: echo '待发货'; break; 
							case 3: echo '待签收'; break; 
							case 4: echo '完成'; break; 
						}?></em></span>
			<div class="clear"></div>
		</p>
	</div>

	<?php if(!empty($this->data->delivery['code'])){ ?>
		<div class="order-number bgwhite">
			<p class="w90">
			<span class="fl">物流公司：<em><?=$this->data->delivery['remark'] ? $this->data->delivery['remark'] : 'N/A'?></em></span>
			<span class="fr">物流单号：<em><?=$this->data->delivery['code'] ? $this->data->delivery['code'] : 'N/A'?></em></span>
				</p><div class="clear"></div>
				
		</div>
	<?php } ?>
	<div class="order-total bgwhite">
		<div class="w90">
			<em class="fl">总计：</em>
			<p class="fl">

				<span><i><?=$this->data['total_credit']?></i>&nbsp;免费积分</span>
				<span><i><?=$this->data['total_credit_happy']?></i>&nbsp;快乐积分</span>
				<span><i><?=$this->data['total_credit_coin']?></i>&nbsp;积分币</span>
				<span><i><?=$this->data['total_amount']?></i>&nbsp;RMB</span>
			</p>
			<?php if ($this->data['status'] == 1) { ?>
			<a class="fr" href="<?=$this->url('/default/cart/pay/?id='.$this->data['id'])?>">立即支付</a>
			<?php } ?>
			<div class="clear"></div>
		</div>
	</div>
	
    <?php //include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>


