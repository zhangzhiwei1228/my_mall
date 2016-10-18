<!DOCTYPE html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>

<body class="bgcolor">
    <?php include_once VIEWS.'inc/header_jifen.php'; ?>
    <div class="steps-show"><img src="<?php echo static_file('m/img/pic20.jpg'); ?> " width="100%"></div>
    <form class="jifen-searchbox" action="#" method="get">
    	<input class="inputext fl" name="q" type="text" value="" placeholder="输入会员账号" />
    	<input class="submit fl" type="submit" value="" />
    	<div class="clear"></div>
    </form>
    <form class="jifen-search-result" method="get" action="<?=$this->url('./confirm')?>">
    	<button type="submit" style="display: none"></button>
	    <table class="jifen-member w90 bgwhite">
	    	<thead>
		    	<tr>
		    		<td width="30"></td>
		    		<td align="center">会员账号</td>
		    		<td align="center">会员名</td>
					<?php if($this->_request->t == 'credit') {?>
						<td align="center">积分余额</td>
					<?php } else {?>
						<td align="center">抵用券余额</td>
					<?php }?>

		    	</tr>
	    	</thead>
	    	<tbody class="query_result">
		    	
		    </tbody>
	    </table>
	    <a href="javascript:;" onclick="$('.jifen-search-result').submit();" class="next">下一步</a>
	</form>
    <div class="jifen-default">
	    <div class="jifen-free bgwhite">
	    	<div class="w90">

	    		<p class="free-text fl"><em><?php echo $this->_request->t == 'credit' ? '积' : '券'; ?></em><?php echo $this->_request->t == 'credit' ? '免费积分余额':'抵用券余额' ?></p>
	    		<p class="free-point fr"><span><?=$this->user[$this->_request->t]?></span>分</p>
	    		<div class="clear"></div>
	    	</div>
	    </div>
	    <div class="jifen-recharge bgwhite">
			<?php if($this->_request->t == 'credit') {?>
				<a href="<?php echo $this->url('agent/credit/recharge/?t=credit')?>">立即充值免费积分</a>
			<?php } else {?>
				<a href="<?php echo $this->url('agent/credit/vouchers/')?>">立即充值抵用券</a>
			<?php }?>

	    </div>
	</div>
	<div class="n-h60"></div>
    <?php include_once VIEWS.'inc/footer_merchantsw.php'; ?>
<?php
	echo static_file('web/js/main.js');
?>
<script type="text/javascript">
	$('.jifen-searchbox').on('submit', function(){
		var el = $(this);
		var q = $('[name=q]', this).val();
		var t = '<?php echo $this->_request->t?>';
		if (!q) {
			alert('请输入会员账号');
			return false;
		}
		if (!t) {
			alert('请不要随意修改url');
			return false;
		}

		$.getJSON('<?=$this->url('./query_user')?>', {q:q,t:t}, function(json){
			console.log(json);
			$('.query_result').empty();
			if (json.length > 0) {
				$(json).each(function(){
					if(t == 'credit') {
						$('.query_result').append('<tr>'
							+'<td align="center"><input type="hidden" name="type" value="'+t+'"><input type="radio" name="uid" value="'+this.id+'" checked></td>'
							+'<td align="center">'+this.username+'</td>'
							+'<td align="center">'+this.nickname+'</td>'
							+'<td align="center">'+this.credit+'</td>'
							+'</tr>');
					} else {
						$('.query_result').append('<tr>'
							+'<td align="center"><input type="hidden" name="type" value="'+t+'"><input type="radio" name="uid" value="'+this.id+'" checked></td>'
							+'<td align="center">'+this.username+'</td>'
							+'<td align="center">'+this.nickname+'</td>'
							+'<td align="center">'+this.vouchers+'</td>'
							+'</tr>');
					}

				});
				$('.next').css('background', '#ff6600');
			} else {
				$('.query_result').html('<tr><td align="center" colspan="3">未找到相关帐号</td></tr>');
				$('.next').css('background', '#ddd');
			}

			$(el).siblings('.jifen-default').css('display','none');
			$(el).siblings('.jifen-search-result').css('display','block');
		});
		
		return false;
	});
</script>
</body>
</html>

                       
