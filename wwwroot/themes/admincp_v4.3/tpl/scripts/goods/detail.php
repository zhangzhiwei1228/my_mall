<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = $this->data['title'];
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-page-header">
	<h1><?=$this->head()->getTitle()?></h1>
		<div class="sui-page-options">
				<?php if ($this->data->getPrevItem()->exists()) { ?>
				<a href="<?=$this->url('&action=detail&id='.$this->data->getPrevItem()->id.'&ref='.$this->_request->ref)?>" class="btn btn-default">
					&lt;&lt; 查看上一条</a>
				<?php } ?>
				<?php if ($this->data->getNextItem()->exists()) { ?>
				<a href="<?=$this->url('&action=detail&id='.$this->data->getNextItem()->id.'&ref='.$this->_request->ref)?>" class="btn btn-default">
					查看下一条 &gt;&gt;</a>
				<?php } ?>
		</div>
</div>

<div class="sui-extend-bar">
	<div class="panel panel-default panel-shadow">
		<div class="sui-page-header"><h4>商品状态</h4></div>
		<div class="panel-body dl-horizontal">
			<div class="form-group">
				<label class="control-label col-sm-2">商品编号：</label>
				<div class="col-sm-9">#<?=$this->data['id']?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">创建时间：</label>
				<div class="col-sm-9"><?=date(DATETIME_FORMAT, $this->data['update_time'])?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">最后修改：</label>
				<div class="col-sm-9"><?=date(DATETIME_FORMAT, $this->data['update_time'])?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">当前状态：</label>
				<div class="col-sm-9"><?=$this->data['is_selling'] ? '<font class="label label-success">销售中</font>' : '<font class="label label-default">待售中</font>'?></div>
			</div>
		</div>
	</div>
	<div class="panel panel-default panel-shadow">
		<div class="sui-page-header"><h4>相关操作</h4></div>
		<ul class="list-group">
			<li class="list-group-item">
				<a href="<?=$this->url('&action=edit')?>">
					<i class="glyphicon glyphicon-edit"></i> 编辑商品</a></li>
			<li class="list-group-item">
				<a href="<?=$this->url('&action=delete&ref='.$this->_request->ref)?>" onclick="return confirm('确定要删除这条记录吗?')">
					<i class="fa fa-trash-o"></i> 删除商品</a></li>
			<li class="list-group-item">
				<a href="<?=$ref?>">
					<i class="glyphicon glyphicon-arrow-left"></i> 返回上一页</a></li>
		</ul>
	</div>
	<div class="panel panel-default panel-shadow">
		<div class="sui-page-header"><h4>数据操作日志</h4></div>
		<div class="panel-body">
			<div style="width:100%; background:#fff; height:120px; border:solid 1px #e0e0e0; overflow-y:auto">
				<div style="padding:8px"><?=$this->data['logs'] ? $this->data['logs'] : 'N/A'?></div>
			</div>
		</div>
	</div>
</div>

<div class="sui-primary">
	<ol class="breadcrumb">
		<li><a href="<?=$this->url('controller=goods')?>">商品管理</a></li>
		<?php foreach ($this->data->category->getPath() as $row) { ?>
		<li><a href="<?=$this->url('action=list&cid='.$row['id'])?>"><?=$row['name']?></a></li>
		<?php } ?>
	</ol>
	
	<div class="clearfix">
		<div class="sui-preview">
			<?php
			$images = $this->data->getImages();
			$preview = $images[0];
			?>
			<div class="img-box">
				<a href="<?=$this->baseUrl($preview['src'])?>" class="zoom" rel='gal1'>
					<img src="<?=$this->img($preview['src'], '400x400')?>" class="preview">
				</a>
			</div>
			<div class="img-list">
				<div class="move-left">‹</div>
				<ul class="clearfix">
					<?php foreach($images as $row) { ?>
					<li><a href="javascript:;" rel="{gallery: 'gal1', smallimage: '<?=$this->img($row['src'], '400x400')?>',largeimage: '<?=$this->baseUrl($row['src'])?>'}"><img src="<?=$this->img($row['src'], '60x60')?>" data-large="<?=$this->img($row['src'], '400x400')?>" /></a></li>
					<?php } ?>
				</ul>
				<div class="move-right">›</div>
			</div>
		</div>
		
		<div class="dl-horizontal" style="overflow:hidden; padding-right:15px">
			<h3 style="padding-left:60px"><?=$this->data['title']?></h3>
			<div class="form-group">
				<div class="col-sm-9"></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">商品编号：</label>
				<div class="col-sm-9"><?=$this->data['code']?></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">市场价格：</label>
				<div class="col-sm-9"><del class="currency"><?=$this->currency($this->data['market_price'])?></del></div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">销售价格：</label>
				<div class="col-sm-9"><span class="currency highlight"><?=$this->currency($this->data['selling_price'])?></span></div>
			</div>
			<?php if ($this->data['promotion_price']) { ?>
			<div class="form-group">
				<label class="control-label col-sm-2">促销信息：</label>
				<div class="col-sm-9"><span class="currency highlight"><?=$this->currency($this->data['promotion_price'])?></span></div>
			</div>
			<?php } ?>
			<div class="form-group">
				<label class="control-label col-sm-2">商品评分：</label>
				<div class="col-sm-9"><?=(int)$this->data['feedback_avg']?> (已有 <?=(int)$this->data['feedbacks_num']?> 人评价)</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">累计成交：</label>
				<div class="col-sm-9"><?=(int)$this->data['trans_num']?> 笔 (共售出 <?=(int)$this->data['sales_num']?> <?=$this->data['package_quantity'] ? $this->data['package_lot_unit'] : $this->data['package_unit']?>)</div>
			</div>
			<div class="form-group">
				<label class="control-label col-sm-2">收藏量：</label>
				<div class="col-sm-9"><?=(int)$this->data['sales_num']?> 次</div>
			</div>
			<div class="goods-skus">
				<?php
				$opts = $this->data->getSkuOpts();
				foreach((array)$opts as $row) { ?>
				<div class="form-group">
					<label class="control-label col-sm-2"><?=$row['name']?>：</label>
					<div class="col-sm-9"><?php
						foreach($row['values'] as $v) { 
							if ($row['type'] == 'color') { ?>
							<a href="javascript:;" style="background:<?=$v?>" class="color-card"></a>
							<?php } else { ?>
							<a href="javascript:;"><?=$v?></a>
							<?php } ?>
					<?php } ?></div>
				</div>
				<?php } ?>
				<div class="form-group">
					<label class="control-label col-sm-2">当前库存：</label>
					<div class="col-sm-9"><strong class="js-stock"><?=(int)$this->data['quantity']?></strong> <?=$this->data['package_unit']?></div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">简要描述：</label>
					<div class="col-sm-9"><?=nl2br($this->data['summary'])?></div>
				</div>
			</div>
		</div>
	</div>
	
	<ul class="nav nav-tabs" style="margin-top:40px;">
		<li class="active"><a href="#desc" data-toggle="tab">商品描述</a></li>
		<li><a href="#sku" data-toggle="tab">商品规格表</a></li>
		<li><a href="#feedback" data-toggle="tab">用户评论</a></li>
		<li><a href="#trans" data-toggle="tab">成交记录</a></li>
	</ul>
	<div class="tab-content">
		<div id="desc" class="tab-pane fade active in" style="padding-top:40px">
			<!--
			<ul class="goods-attr">
				<?php
				$attrs = $this->data->getAttrs();
				foreach((array)$attrs as $row) { ?>
				<li><?=$row['attr_name']?>：<?=implode(',', $row['attr_value'])?></li>
				<?php } ?>
			</ul>-->
			<?=$this->data['description']?>
		</div>
		<div id="sku" class="tab-pane fade">
			
		</div>
	</div>
</div>

<script type="text/javascript">
seajs.use('/assets/js/zoom/zoom.sea.js', function(zoom) {
	zoom.init('.zoom', {
		zoomType: 'reverse',
		lens:true,
		title:false,
		preloadImages: false,
		preloadText: '加载中...',
		showPreload: false,
		alwaysOn:false,
		zoomWidth:350,
		zoomHeight:350,
	});
});
</script>