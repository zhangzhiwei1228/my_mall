<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '申请退款';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">

		<div class="form-group">
			<label class="control-label col-sm-2">退单号:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->data['code']?></div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">订单号:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->data->order['code']?> 
				[<a href="<?=$this->url('controller=order&action=detail&id='.$this->data['order_id'])?>" target="_blank">查看</a>]</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商品图片:</label>
			<div class="col-sm-9">
				<div class="form-control-static" style="width:80px; height:80px;">
					<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$this->good['id'])?>" target="_blank">
						<img src="<?=$this->baseUrl($this->good['thumb'])?>" class="img-thumbnail" /></a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商品标题:</label>
			<div class="col-sm-9">
				<div class="form-control-static">
				<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$this->good['id'])?>" target="_blank"><?=$this->good['title']?></a></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">商品规格:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->sku['spec'] ? $this->sku['spec'] : 'N/A'?></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">购买数量:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->good['qty']?> 件</div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">买入价格:</label>
			<div class="col-sm-9">
				<!--<div class="form-control-static"><?/*=$this->currency($this->data->goods['subtotal_amount'] - $this->data->goods['subtotal_save'])*/?></div>-->
				<div class="form-control-static"><?=$this->good['price_text']?></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">订单支付运费:</label>
			<div class="col-sm-9">
				<!--<div class="form-control-static"><?/*=$this->currency($this->data->goods['freight'])*/?></div>-->
				<div class="form-control-static"><?=$this->order['total_freight']?></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">退款金额:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->currency($this->data['refund_amount'])?></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">退款原因:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->data['reason']?></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">问题描述:</label>
			<div class="col-sm-9">
				<div class="form-control-static"><?=$this->data['description'] ? $this->data['description'] : 'N/A'?></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<?php if ($this->data['status'] == 0) { ?>
				<button type="button" class="btn" onclick="window.location= '<?=$this->url('action=accept&id='.$this->data['id'])?>'" class="main">同意</button>
				<button type="button" class="btn" onclick="window.location= '<?=$this->url('action=refuse&id='.$this->data['id'])?>'">拒绝</button>	
				<?php } ?>
				<button type="button" class="btn" onclick="window.location= '<?=$ref?>'">返回</button>	
			</div>
		</div>
	</form>
</div>