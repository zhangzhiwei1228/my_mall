<?php
$t = array(
	1 => '一星分销商',
	2 => '二星分销商',
	3 => '三星分销商',
	4 => '四星分销商',
	5 => '申请商家入驻',
	6 => '申请代理商',
);
?>

<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-personal-center-tit">
		<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
		<?=$t[$this->_request->t]?>
	</div>
	<div class="n-member">
		<div class="n-member-banner">
			<img src="<?php echo static_file('mobile/img/img-19.jpg'); ?> " alt="">
		</div>
		<div class="n-member-te">
			<?php
			$page = M('Page')->getByCode('upgrade-vip'.$this->_request->t);
			echo $page['content'];
			?>
		</div>
		<div class="n-member-input">
			<input value="立即申请" class="n-member-input1" type="submit" onclick="window.location='<?=$this->url('./apply?vip='.$this->_request->t)?>'">
		</div>
	</div>
	<div class="n-h56"></div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
</body>
</html>
