<?php
$this->title = t($this->title);
$this->head()->setTitle($this->title);
?>

<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
	<div class="n-wechat">
		<div class="n-personal-center-tit">
			<a href=""><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			系统提示
		</div>
		<div class="n-wechat-box">
			<div class="alert alert-<?=$this->type?>">
			<h3><?=$this->head()->getTitle()?></h3>
					<?=t($this->message)?>
			<?php if ($this->autoback) { ?>
			<?=sprintf(t('AUTO_BACK_DESCRIPTION'), 5, $this->autoback[0])?>
			<script language="javascript" type="text/javascript">	
					var i = 4;	
					var intervalid;	
					intervalid = setInterval("fun()", 1000);	
					function fun() {	
							if (i == 0) {	
									window.location.href = "<?=$this->url($this->autoback[1])?>";	
									clearInterval(intervalid);	
							}	
							$('.second').text(i);
							i--;
					}	
			</script>
			<?php } ?>
			<div style="margin-top:30px">
					<?php $i=0; foreach ((array)$this->links as $row) { ?>
					<a href="<?=$this->url($row[1])?>" class="btn btn-<?=$i==0 ? 'primary' : 'default'?>"><?=t($row[0])?></a>
					<?php $i++; } ?>
			</div>
	</div>
		</div>
	</div>
</body>
</html>