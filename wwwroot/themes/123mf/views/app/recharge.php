<!DOCTYPE html>
<head>
<?php include_once VIEWS.'app/inc/head.php'; ?>
</head>

<body>
    <?php include_once VIEWS.'app/inc/header.php'; ?>
    
    <div class="n-recharge">
    	<div class="text">
    		<h2>
				<?php switch($this->type) {
					case 'credit_happy':
						echo '快乐积分';
						break;
					case 'credit':
						echo '帮帮币';
						break;
					case 'credit_coin':
						echo '积分币';
						break;
					case 'vouchers':
						echo '抵用券';
						break;
				}?>充值说明
			</h2>
    		<p><?php echo $this->name?>充值比例为1:<?php echo $this->data['r_digital']?>,，1元=<?php echo $this->data['r_digital']?><?php echo $this->name?>，<br>
			充值后可在商城兑换商品哦~</p>
    	</div>
    </div>
    <?php include_once VIEWS.'app/inc/footer.php'; ?>
<?php
	echo static_file('app/m/js/main.js');
?>
<script>
$(function(){
	
})
</script>
</body>
</html>