<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic21.jpg'); ?> " width="100%"></div>
    <form class="jifen-step02" method="post">
    	<input type="hidden" name="uid" value="<?=$this->account['id']?>">
	    <table class="step02-main w90 bgwhite">
	    	<tr>
	    		<td align="left">会员账号</td>
	    		<td align="right"><?=$this->account['username']?></td>
	    	</tr>
	    	<tr>
	    		<td align="left">会员名</td>
	    		<td align="right"><?=$this->account['nickname']?></td>
	    	</tr>
	    	<tr>
	    		<td align="left">赠送金额</td>
	    		<td class="goon" align="right">
	    		<input type="text" name="<?php echo $this->_request->type?>" placeholder="100" style="width: 50px; text-align: right; border:none; background: none;">
	    		<a href="#"></a>
	    		</td>
	    	</tr>
	    </table>
	    
	    <a style="background:#ff6600;margin:45px auto 10px;" href="javascript:;" class=" btn next">下一步</a>
	    <a style="background:#707070;margin:0px auto;" href="<?=$this->url('./default')?>" class=" btn prev">上一步</a>
	</form>
    <div class="n-h56"></div>
    <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
	$('.next').on('click', function(){
		$('.jifen-step02').submit();
	});
</script>
</body>
</html>




                       
