<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body>
    <div class="z-jifenheader bgcolor<">
		<div class="header-merchants">
			<div class="main"><a href="javascript:void(history.back());"><img src="<?php echo static_file('m/img/icon18.png'); ?> "></a>我的员工</div>
		</div>
	</div>
    <table class="staff-list bgwhite">
    	<tr class="header">
    		<td>员工账号</td>
    		<td>员工名称</td>
    	</tr>
        <?php foreach($this->datalist as $row) { ?>
    	<tr>
    		<td><?=$row['id']?></td>
    		<td><a href="<?=$this->url('staff/detail?id='.$row['id'])?> "><?=$row['username']?></a></td>
    	</tr>
        <?php } ?>
    </table>
	<?php if($this->user['role'] == 'seller') {?>
		<?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
	<?php } else {?>
		<?php include_once VIEWS.'inc/footer_fourstar.php'; ?>
	<?php }?>
<?php
	echo static_file('web/js/main.js');
?>
</body>
</html>