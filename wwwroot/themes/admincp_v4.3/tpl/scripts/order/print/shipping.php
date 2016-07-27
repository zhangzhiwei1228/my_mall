<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->setLayout(false);
?>
<html>
<head>
	<title>打印快递单</title>
	<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
	function printHtml(html) {
		var bodyHtml = document.body.innerHTML;
		document.body.innerHTML = html;
		window.print();
		document.body.innerHTML = bodyHtml;
	}
	function onprint() {
		var html = $("#print-area").html();
		printHtml(html);
	}
	</script>
	<style type="text/css">
	#print-area {
	}

	.box {
		/*
		width: 21.6cm;
		height: 14cm;
		margin: 40px;
		position: relative;
		background: url(http://www.lhjmall.com/themes/admincp_v4.1/img/sf_print.jpg);*/
		font-size: 14px;
		line-height: 25px;
		overflow: hidden;
		/*border:solid 1px #000;*/
	}

	@media print {
		
	}

	.shipper-box {
		width: 250px;
		position: absolute;
		top: 100px;
		left: 80px;

	}

	.shipper-zipcode {
		display: none;
	}

	.shipper-phone {
		text-align: right;
		padding-right: 50px;
	}

	.consignee-box {
		width: 250px;
		position: absolute;
		top: 242px;
		left: 80px;
	}

	.consignee-name {
		text-align: right;
	}

	.consignee-zipcode {
		display: none;
	}

	.consignee-phone {
		text-align: right;
		padding-right: 80px;
	}

	/*
	.shipper-name {
		position: absolute;
		top: 4cm;
		left: 3cm;
	}

	.shipper-addr {
		position: absolute;
		top: 4.6cm;
		left: 3cm;
		width: 250px;
	}


	.shipper-phone {
		position: absolute;
		top: 6.1cm;
		left: 4.6cm;
	}

	.consignee-name {
		position: absolute;
		top: 8cm;
		left: 8cm;
	}

	.consignee-addr {
		position: absolute;
		top: 8.6cm;
		left: 3cm;
		width: 250px;
	}

	.consignee-phone {
		position: absolute;
		top: 10.1cm;
		left: 4.6cm;
	}

	.consignee-zipcode {
		display: none;
	}*/

	.btn-print {
		background: #000;
		color: #fff;
		border: none;
		line-height: 30px;
		width: 100px;
		font-size: 15px;
	}
	</style>
</head>
<body>

<div id="print-area">
	<div class="box">
		<div class="shipper-box">
			<div class="shipper-name">领航家家居</div>
			<div class="shipper-addr">杭州市余杭区良渚镇勾庄路218号亿丰最家空间5楼</div>
			<div class="shipper-zipcode">311113</div>
			<div class="shipper-phone">0571-56079083</div>
		</div>

		<div class="consignee-box">
			<div class="consignee-name"><?=$this->data['consignee']?></div>
			<div class="consignee-addr"><?=$this->data['area_text']?> <?=$this->data['address']?></div>
			<div class="consignee-zipcode"><?=$this->data['zipcode']?></div>
			<div class="consignee-phone"><?=$this->data['phone']?></div>
		</div>
	</div>
</div>
<button class="btn-print" onclick="onprint()">打印</button>

</body>
</html>
