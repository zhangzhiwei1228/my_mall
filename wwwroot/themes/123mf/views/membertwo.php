<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		升级二星分销商
	</div>
	<div class="n-member">
		<div class="n-member-banner">
			<img src="<?php echo static_file('mobile/img/img-21.jpg'); ?> " alt="">
		</div>
		<div class="n-member-te">
			<br>
			<p style="color:#b40000;font-size:14px;">创业升级条件：</p>
			<br>
			<p style="color:#333;line-height:24px;">会员升级为一星分销商，升级服务费为500元，同时可获得500帮帮币。会员升级后，可获得推荐的会员每次消费积分币的相应提成（该提成可提现）。</p>
			<br>
			<p style="color:#b40000;font-size:14px;">规则说明：</p>
			<p style="color:#333;line-height:24px;">A:当您推荐的朋友每次在消费积分币时，您可以获得相应的提成。</p>
			<br>
			<p style="color:#333;line-height:24px;">B：您推荐的朋友，他再推荐他的朋友，他的朋友在消费个人积分币时，你同样可以获得相应的提成。</p>
			<br>
			<p style="color:#333;line-height:24px;">C：二星分销商可推荐商家合作，推荐合作的商家的会员（一、二级会员），每个会员每次消费积分币时，二星分销商可获得相应的提成。</p>

			
			
			
		</div>
		<div class="n-member-input">
			<input value="立即申请" class="n-member-input1" type="submit" onclick="window.location='<?=$this->url('./apply?vip=2')?>'">
		</div>
	</div>
	<div class="n-h56"></div>
	<?php if(!isset($this->agent)) {?>
		<?php include_once VIEWS.'inc/footer01.php'; ?>
	<?php }?>

</body>
</html>
