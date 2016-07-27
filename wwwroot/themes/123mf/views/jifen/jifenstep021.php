<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic21.jpg'); ?> " width="100%"></div>
    <div class="jifen-step02">
	    <table class="step02-main w90 bgwhite">
	    	<tr>
	    		<td align="left">会员账号</td>
	    		<td align="right">000101</td>
	    	</tr>
	    	<tr>
	    		<td align="left">会员名</td>
	    		<td align="right">韦小宝</td>
	    	</tr>
	    	<tr>
	    		<td align="left">赠送金额</td>
	    		<td class="goon" align="right">100<a href="#"></a></td>
	    	</tr>
	    </table>
	    <a href="<?php echo site_url('jifensteps1'); ?> " class=" btn prev">上一步</a>
	    <a href="<?php echo site_url('jifen/jifenstep031'); ?> " class=" btn next">下一步</a>
	</div>
    <div class="n-h56"></div>
   	 <!--<?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>-->
     <?php include_once VIEWS.'inc/footer01.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('table.step02-main tr:last').css('borderBottom','none');
</script>
</body>
</html>




                       
