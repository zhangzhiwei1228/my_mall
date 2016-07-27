<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		升级四星分销商
	</div>
	<div class="n-member">
		<div class="n-member-banner">
			<img src="<?php echo static_file('mobile/img/img-001.jpg'); ?> " alt="">
		</div>
		<div class="n-member-te">
			<br>
			<p style="color:#b40000;font-size:14px;">创业升级条件：</p>
			<br>
			<p style="color:#333;line-height:24px;">升级为四星分销商可获得更加广阔的（区域）创业平台！</p>
			<br>
			
			<p style="color:#333;line-height:24px;">这是一个比任何创业机会都更加有利的平台！（机会来了，你还在等什么！这里是您创业大发展的首选平台！马上升级，选择先机）</p>		
		</div>
		<div class="n-member-input">
			<input value="立即申请" class="n-member-input1" type="submit" onclick="window.location='<?=$this->url('./apply?vip='.$this->_request->t)?>'">
		</div>
	</div>
	<div class="n-h56"></div>
	<?php if(!isset($this->agent)) {?>
		<?php include_once VIEWS.'inc/footer01.php'; ?>
	<?php }?>
</body>
</html>
