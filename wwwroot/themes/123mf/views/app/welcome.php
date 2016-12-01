<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'inc/header.php'; ?>
	<!-- <a href="<?php echo site_url('problem'); ?> ">常见问题</a> -->
	<a href="<?php echo site_url('news_info'); ?> ">消息详情</a>
	<a href="<?php echo site_url('recharge'); ?> ">充值说明</a>
	<a href="<?php echo site_url('distributor'); ?> ">升级成为分销商</a>
	<a href="<?php echo site_url('agent'); ?> ">升级成为代理商</a>
	<a href="<?php echo site_url('settled'); ?> ">申请商家入驻</a>
	<a href="<?php echo site_url('member_one'); ?> ">会员已激活</a>
	<a href="<?php echo site_url('member_two'); ?> ">会员激活</a>
	<a href="<?php echo site_url('products'); ?> ">产品详情</a>
	<a href="<?php echo site_url('business'); ?> ">商家详情</a>
	<a href="<?php echo site_url('registration'); ?> ">用户协议</a>
	<a href="<?php echo site_url('shopping_mall'); ?> ">商场须知</a>
    <?php include_once VIEWS.'inc/footer.php'; ?>
<?php
	echo static_file('m/js/main.js');
?>
<script>
$(function(){
	
})
</script>
</body>
</html>