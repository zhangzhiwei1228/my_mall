<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>

	<div class="payrecord">
		<div class="n-personal-center-tit">
			<a href="javascript:history.go(-1);"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png'); ?> " alt=""></a>
			<?php echo $this->left_name?>转化成<?php echo $this->right_name?>
		</div>
		<div class="n-recharge-pic clear">
			<div class="n-recharge-head">
				<div class="n-recharge-head-info"><img src="<?php echo static_file('mobile/img/img-23.png'); ?> " alt=""></div>
			</div>
			<span><?=$this->user['nickname']?></span>
			<p>手机号：<?=$this->user['mobile']?></p>
		</div>
		<div class="n-recharge-pic-te">
			<p style="color:#b40000;font-size:14px;font-weight:bold;">转换说明：</p>
			<p style="color:#555;">
				<?php echo $this->left_name?>可按<font style="color:#b40000;"><?php echo $this->data['l_digital']?> : <?php echo $this->data['r_digital']?></font>转换成<?php echo $this->right_name?>，
				<font style="color:#b40000;">10000</font><?php echo $this->left_name?>起转按<font style="color:#b40000;">1000</font>整数倍增加。例
				<font style="color:#b40000;">100</font><?php echo $this->left_name?>可转换成<font style="color:#b40000;"><?php echo floor(100*($this->data['r_digital']/$this->data['l_digital']))?></font><?php echo $this->right_name?>。
			</p>
		</div>
		<div class="n-h5"></div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">我的<?php echo $this->left_name?>余额</div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1" id="now-credit"><?php echo $this->user[$this->left_type]?></p>
				<input type="hidden" id="user-credit" value="<?php echo $this->user[$this->left_type]?>" />
				<p class="n-dealer-end-down-p3">
					<?php switch($this->left_type) {
						case 'credit':
							echo '分';
							break;
						case 'credit_happy':
							echo '分';
							break;
						case 'credit_coin':
							echo '币';
							break;
						case 'vouchers':
							echo '券';
							break;
						case 'worth_gold':
							echo '金';
							break;
					}?>

				</p>
			</div>
		</div>
		<div class="n-h5"></div>
		<div class="n-recharge-sp">
			<p>输入转换<?php echo $this->left_name?></p>
			<a href=""></a>
			<input type="text" name="credit" value="" id="credit">
		</div>
		<div class="n-dealer-end">
			<div class="n-dealer-end-top">获得<?php echo $this->right_name?></div>
			<div class="n-dealer-end-down">
				<p class="n-dealer-end-down-p1" id="credit-coin">0</p>
				<p class="n-dealer-end-down-p3">
					<?php switch($this->right_type) {
						case 'credit':
							echo '分';
							break;
						case 'credit_happy':
							echo '分';
							break;
						case 'credit_coin':
							echo '币';
							break;
						case 'vouchers':
							echo '券';
							break;
						case 'worth_gold':
							echo '金';
							break;
					}?>
				</p>
			</div>
		</div>
		<div style="margin-top:42px;" class="n-recharge-sub">
		<input type="hidden" name="cid" id="cid" value="<?php echo $this->_request->cid?>" />
		<input class="n-recharge-end-sub" value="我要转换" id="credit_conver" type="submit">
	</div>
	</div>

</body>
<script>
	$(document).ready(function() {
		var flag= false;
		$("#credit").blur(function(){
			var credit = $(this).val();
			var pattern = /^\d+(\.\d+)?$/;
			var exts = '<?php echo floor(($this->data['l_digital']));?>';
			var pro = '<?php echo $this->data['r_digital']/$this->data['l_digital'];?>';
			var user_credit = $('#user-credit').val();
			if(!credit.match(pattern) && credit != '' ) {
				alert('请输入正确的数字');
				return false;
			}

			if(parseInt(credit) > parseInt(user_credit)) {
				alert('输入的金额已大于您所拥有的');
				return false;
			}
			if(credit % exts !=0) {
				alert('转换必须是'+exts+'的倍数');
				return false;
			}
			var credit_coin = credit*pro;
			$('#credit-coin').text(credit_coin);
			$('#now-credit').text(user_credit-credit);
			flag = true;
		});
		$('#credit_conver').click(function() {
			var credit = $('#credit').val();
			var cid = $('#cid').val();
			if(!flag || credit == '') return false;
			$.post('<?php echo site_url('usercp/credit/conversion/');?>',{credit:credit,cid:cid},function(data) {
				$('#credit').val('');
				alert($.parseJSON(data).msg);

			});
		});
	});
</script>
</html>