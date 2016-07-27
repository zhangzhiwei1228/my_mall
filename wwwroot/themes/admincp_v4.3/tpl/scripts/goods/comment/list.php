<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '商品评价';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>
<div class="primary">
		<h1 class="page-title"><?=$this->title?></h1>
	<form method="get" class="search-bar">
		<input type="hidden" name="page" value="1" />
		<input type="hidden" name="search" value="1" />
		商品标题：<input type="text" name="goods_title" value="<?=$this->_request->goods_title?>" style="width:160px;" />
		用户：<input type="text" name="username" value="<?=$this->_request->username?>" style="width:100px;" />
		评论时间：<input type="text" name="begin_time" class="ipt-date JS_DatePicker" value="<?=$this->_request->begin_time?>" /> - 
		<input type="text" name="end_time" class="ipt-date JS_DatePicker" value="<?=$this->_request->end_time?>" />
		<button type="submit" class="btn btn-primary">搜索</button>
		<?php if ($this->_request->search) { ?>
		<button type="button" class="btn" onclick="window.location= '<?=$this->url('action=list')?>'">取消搜索</button>
		<?php } ?>
	</form>
	<form class="JS_Batch" method="post" action="<?=$this->url('action=batch')?>">
		<div class="action-bar">
					<span class="paginator" style="float:right;"><?=$this->paginator($this->datalist)->getMiniBar()?></span>
						<span class="control-btn">
			<button type="submit" class="btn JS_Precond" disabled="disabled" name="act" value="delete" onclick="return confirm('确定要删除所选记录吗?')">删除</button>
			</span> </div>
		<table width="100%" class="datalist">
			<thead>
				<tr>
									<td width="20" align="center"><input type="checkbox" class="JS_AllChecked JS_Checkbox" /></td>
										<td width="80" align="center">相关商品</td>
					<td>评论内容</td>
					<td width="120">用户</td>
										<td width="50">评分</td>
					<td width="50" align="center">显示</td>
					<td width="70">操作</td>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="7"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" class="JS_OneChecked JS_Checkbox" value="<?=$row['id']?>" /></td>
										<td align="center" valign="top" style="height:80px; overflow:hidden;">
										<div class="thumb" style="width:65px; height:65px; margin:4px 0px; padding:2px;">
												<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$row['goods_id'])?>" title="<?=$row['goods_title']?>" target="_blank"><img src="<?=$this->img($row['thumb'], '65x65')?>" width="65" /></a>
										</div>
										</td>
					<td valign="top" style="padding-right:20px; padding-bottom:20px;">
					<blockquote>
						<?=nl2br($row['comment'])?>
						<p style="color:#888;">
							<?php if ($row['client_ip']) { ?>
													IP:<?=long2ip($row['client_ip']) ?>
							<?php } ?>
													<?=date(DATETIME_FORMAT, $row['create_time']) ?>
												</p>
						</blockquote>
					<?php if ($row['reply']) { ?>
					<blockquote><span class="label error">答</span>
						<?=nl2br($row['reply'])?></blockquote>
					<?php } ?>
					</td>
					<td valign="top">
											<?php if ($row['sender_id']) { ?>
						<a href="<?=$this->url('controller=member&action=detail&id='.$row['sender_id'])?>" target="_blank"><?=$row['username']?></a> 
												<?php } elseif ($row['sender_name']) { ?>
						<?=$row['sender_name']?><br />
						<span class="label">临时用户</span>
						<?php } else { echo '游客'; }?>
					</td>
										<td valign="top"><?=$row['score']?></td>
					<td valign="top" align="center"><a href="<?=$this->url('action=enabled&id=' . $row['id'].'&ref='.$this->_request->url)?>">
						<?=$row['is_show'] ? '<font class="label success">是</font>' : '<font class="label">否</font>'?></a></td>
					<td valign="top">
						<a href="<?=$this->url('action=reply&id=' . $row['id'].'&ref='.$this->_request->url)?>">回复</a>
						<a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a>
					</td>
				</tr>
				<?php } } ?>
			</tbody>
		</table>
		<div class="action-bar">
			<script type="text/javascript">document.write($('.control-btn').clone().html());</script>
		</div>
		<div class="action-bar">
			<div class="paginator"><?=$this->paginator($this->datalist)?></div>
		</div>
	</form>
</div>