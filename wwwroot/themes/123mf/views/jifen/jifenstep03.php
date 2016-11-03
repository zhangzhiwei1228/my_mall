<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic22.jpg'); ?> " width="100%"></div>
    <div class="jifen-step03 bgwhite">
	    <div class="step03-main ">
	    	<dl>
	    		<dt></dt>
	    		<dd class="sure-info">您确认要给<?=$this->account['nickname']?>(<?=$this->account['username']?>)用户</dd>
	    		<dd class="sure-point">
					<?php echo $this->_request->type == 'credit' ? '赠送帮帮币' : '赠送抵用券' ?>
					<span><?=$_POST[$this->_request->type]?></span>
					<?php echo $this->_request->type == 'credit' ? '点' : '券' ?>

				</dd>
	    	</dl>
	    </div>
	</div>
	<div class="jifen-step02">
	    <a href="javascript:;" class="btn sure">确  认</a>
	    <a href="<?=$this->url('./default')?>" class=" btn cancel">取  消</a>
    </div>
    <div class="n-h56"></div>
     <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
     <!--?php include_once VIEWS.'inc/footer01.php'; ?-->
<?php
	echo static_file('web/js/main.js');
?>

<form method="post" action="<?=$this->url('./pay')?>" class="pay-form">
<input type="hidden" name="uid" value="<?=$this->account['id']?>">
<input type="hidden" name="<?php echo $this->_request->type?>" value="<?=$_POST[$this->_request->type]?>">
<input type="hidden" name="type" value="<?php echo $this->_request->type?>">
</form>

<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
	$('.sure').on('click', function(){
		$('.pay-form').submit();
	});
</script>
</body>
</html>




                       
