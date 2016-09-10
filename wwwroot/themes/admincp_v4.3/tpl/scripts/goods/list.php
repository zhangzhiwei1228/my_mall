<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = $this->_request->search ? '商品搜索' : ($this->category->exists() ? $this->category['name'] : '商品管理');
switch ($this->_request->view) {
	case 'approval_pending': $this->title = '待审商品'; break;
	case 'not_approved': $this->title = '审核不通过'; break;
	case 'offsale': $this->title = '待售商品'; break;
	case 'onsale': $this->title = '正在销售'; break;
	case 'quantity_warning': $this->title = '库存警告'; break;
}

$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?><small>(共<?=$this->datalist->total()?>条记录)</small></h1>

		<form method="get" action="<?=$this->url('action=list')?>" class="sui-searchbox form-inline">
			<input type="hidden" name="search" value="1" />
			<input type="hidden" name="page" value="1" />
			
			<a class="fa fa-external-link" href="?search=1&page=1&cid=-1" target="_blank">分类异常</a>
			<a class="fa fa-external-link" href="?search=1&page=1&cid=-2" target="_blank">物流异常</a>
			<div class="form-group">
				<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" placeholder="请输入关键词查询" />
				<?php if ($this->_request->q) { ?>
				<a href="<?=$this->url('&q=')?>" class="fa fa-remove"></a>
				<?php } ?>
			</div>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
			<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#adv-search-box" title="高级搜索"><i class="fa fa-external-link"></i></button>
		</form>
	</div>
	<form method="post" action="<?=$this->url('action=batch')?>" data-plugin="chk-group" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
				<?=$this->paginator($this->datalist)?>
			</ul>
			<div class="btn-group btn-group-sm">
				<button type="submit" name="act" value="onsale" class="btn btn-default"> <i class="fa fa-check-circle-o"></i> 上架</button>
				<button type="submit" name="act" value="offsale" class="btn btn-default"> <i class="fa fa-times-circle-o"></i> 下架</button>
			</div>
			<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#move-box"> <i class="fa fa-exchange"></i> 移动</button>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
			<a class="btn btn-default btn-sm" href="<?=$this->url('action=add&ref='.$this->_request->url)?>"> <i class="fa fa-plus-circle"></i> 添加商品</a>

		</div>

		<table width="100%" class="table table-striped">
			<thead>
				<tr>
					<th width="20" class="text-right"><input type="checkbox" role="chk-all" /></th>
					<th width="75" class="text-center">商品图片</th>
					<th>标题</th>
					<th width="260">价格</th>
					<th width="120">统计</th>
					<th width="60" class="text-center">推荐</th>
					<th width="80" class="text-center">审核</th>
					<th width="80" class="text-center">状态</th>
					<th width="140">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="9"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td class="text-center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td class="text-center">
						<a href="<?=$this->url('action=edit&id='.$row['id'].'&ref='.$this->_request->url)?>">
							<img src="<?=$this->img($row['thumb'], '160x160')?>" class="img-thumbnail"></a>
					</td>
					<td style="<?php if($row['diff_quantify'] <= 0) {?> color:red<?php }?>">
						<a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>" style="<?php if($row['diff_quantify'] <= 0) {?> color:red<?php }?>">
							<?=$this->highlight($row['title'], $this->_request->q)?></a><br />

						货号：<?=$row['code'] ? $this->highlight($row['code'], $this->_request->q) : 'N/A'?> [#ID: <?=$row['id']?>]<br />
						类目：<?=$row['category_name'] ? $row['category_name'] : 'N/A'?>
					</td>
					<!-- <td>
						<?php if ($row['is_promotion']) { ?>
						<strong class="amount">&yen;<?=$row['promotion_price']?></strong> / <?=$row['unit']?><br />
						<del class="amount">&yen;<?=$row['price']?></del> / <?=$row['unit']?><br />
						<?php } else { ?>
						<strong class="amount">&yen;<?=$row['price']?></strong> / <?=$row['unit']?><br />
						<?php } ?>
						<?php if ($row['package_quantity']) { ?>
						每<?=$row['package_lot_unit']?> <?=$row['package_quantity']?> <?=$row['package_unit']?>
						<?php } ?>
					</td> -->
					<td>
						快乐积分：<span><?=$row['skus'][0]['point1']?></span>点<br>
	                    免费积分：<span><?=$row['skus'][0]['point2']?></span>点<br>
	                    <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
	                    现金+免费积分：￥<span><?=$row['skus'][0]['exts']['ext1']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext1']['point']?></span>免费积分<br>
	                    <?php } ?>
	                    <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
	                    现金+积分币：￥<span><?=$row['skus'][0]['exts']['ext2']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext2']['point']?></span>积分币<br>
	                    <?php } ?>
	                    原价：￥<span class=""><?=$row['skus'][0]['market_price']?></span>
					</td>
					<td>
						库存：<?=$row['quantity'] == 0 || $row['quantity'] <= $row['quantity_warning'] ? '<font class="highlight">'.(int)$row['quantity'].'</font>' : (int)$row['quantity']?> <?=$row['unit']?><br />
						销量：<?=(int)$row['sales_num']?> <?=$row['unit']?><br />
						成交：<?=(int)$row['trans_num']?> 笔<br/>
						警告量：<?=(int)$row['quantity_warning']?> 件
					</td>
					<td class="text-center">
						<a href="<?=$this->url('action=toggle_status&t=is_rec&id='.$row['id'].'&v='.$row['is_rec'].'&ref='.$this->_request->url)?>">
						<?=$row['is_rec'] ? '<span class="label label-success">是</span>' : '<span class="label label-default">否</span>'?>
						</a></td>
					<td class="text-center">
						<div class="dropdown">
							<?php
								switch($row['is_checked']) {
									case 0: $class = 'btn-default'; $text = '待审中'; break;
									case 1: $class = 'btn-danger'; $text = '不通过'; break;
									case 2: $class = 'btn-success'; $text = '已通过'; break;
								}
							?>
							<button type="button" class="btn btn-xs <?=$class?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?=$text?>
							 <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel" style="min-width:80px;">
								<li><a href="<?=$this->url('action=toggle_status&t=is_checked&id='.$row['id'].'&v=2&&ref='.$this->_request->url)?>">通过</a></li>
								<li><a href="<?=$this->url('action=toggle_status&t=is_checked&id='.$row['id'].'&v=1&&ref='.$this->_request->url)?>">不过</a></li>
								<li><a href="<?=$this->url('action=toggle_status&t=is_checked&id='.$row['id'].'&v=0&&ref='.$this->_request->url)?>">待审</a></li>
							</ul>
						</div>
					</td>
					<td class="text-center">
						<a href="<?=$this->url('action=toggle_status&t=is_selling&id='.$row['id'].'&v='.$row['is_selling'].'&ref='.$this->_request->url)?>">
						<?=$row['is_selling'] ? '<span class="label label-success">销售中</span>' : '<span class="label label-default">待售中</span>'?>
						</a></td>
					<td valign="top"><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?>
						<?php if ($row['expiry_time']) { echo '<p>剩余:'.$this->countdown($row['expiry_time']).'</p>'; } ?></td>
					<td valign="top"><a href="<?=$this->url('action=edit&id=' . $row['id'].'&ref='.$this->_request->url)?>">编辑</a> <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a><br />
						<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['id'])?>" target="_blank">预览</a>
						<a href="<?=$this->url('action=copy&id='.$row['id'])?>">复制</a>
						<a href="<?=$this->url('action=sales&id='.$row['id'])?>">销售明细</a>
					</td>
				</tr>
				<?php } } ?>
			</tbody>
		</table>
		<div class="sui-toolbar"> 
			<script type="text/javascript">
				var toolbar = $('.sui-toolbar').clone();
				document.write(toolbar.html());
			</script> 
		</div>
		
		<!-- Modal -->
		<div class="modal fade" id="move-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog" style="width:600px">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">移动至...</h4>
					</div>
					<div class="modal-body">
						<div class="JS_Dmenu form-inline">
							<input type="hidden" name="cid" value="<?=$this->_request-cid?>" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
						<button type="submit" class="btn btn-primary" name="act" value="move">保存设置</button>
					</div>
				</div>
				<!-- /.modal-content --> 
			</div>
			<!-- /.modal-dialog --> 
		</div>
		<!-- /.modal -->
	</form>

	<!-- Modal -->
	<div class="modal fade" id="adv-search-box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:500px">
			<form method="get" class="modal-content">
				<input type="hidden" name="search" value="1" />
				<input type="hidden" name="page" value="1" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">高级搜索</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						按分类查找：
						<div class="JS_Dmenu2 form-inline">
							<input type="hidden" name="cid" value="<?=$this->_request-cid?>" />
						</div>
					</div>
					<div class="form-group">
						关键词查找：
						<input type="text" name="q" value="<?=$this->_request->q?>" class="form-control input-sm" />
					</div>
					<div class="form-group">
						发布时间：
						<div class="input-group">
							<input type="text" name="start_time" value="<?=$this->_request->start_time?>" class="form-control input-sm" data-plugin="date-picker">
							<span class="input-group-addon">~</span>
							<input type="text" name="end_time" value="<?=$this->_request->end_time?>" class="form-control input-sm" data-plugin="date-picker">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
					<button type="submit" class="btn btn-primary">搜索</button>
				</div>
			</form>
			<!-- /.modal-content --> 
		</div>
		<!-- /.modal-dialog --> 
	</div>
	<!-- /.modal -->
</div>
<script>
seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
	dmenu.init('.JS_Dmenu', {
		script: '<?=$this->url('controller=goods_category&action=getJson')?>',
		htmlTpl: '<select size="8" class="form-control" style="margin-right:6px"></select>',
		selected: $('input[name=cid]').val(),
		callback: function(el, data) {
			$('input[name=cid]').val(data.id);
		}
	});
	dmenu.init('.JS_Dmenu2', {
		script: '<?=$this->url('controller=goods_category&action=getJson')?>',
		htmlTpl: '<select size="6" class="form-control" style="margin-right:6px;"></select>',
		selected: <?=(int)$this->_request->cid?>,
		defaultText: '不限',
		callback: function(el, data) {
			$('input[name=cid]').val(data.id);
		}
	});
});
</script>
