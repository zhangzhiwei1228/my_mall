<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '用户认证';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"> <?=$this->head()->getTitle()?> <small>(共<?=$this->datalist->total()?>条记录)</small></h1>
		<ul class="nav nav-pills">
			<li <?=$this->_request->t=='pending' ? 'class="active"' : ''?>><a href="<?=$this->url('&t=pending')?>"><i class="fa fa-question-circle"></i> 待处理的认证</a></li>
			<li <?=$this->_request->t=='yes' ? 'class="active"' : ''?>><a href="<?=$this->url('&t=yes')?>"><i class="fa fa-check-circle-o"></i> 已通过的认证</a></li>
			<li <?=$this->_request->t=='no' ? 'class="active"' : ''?>><a href="<?=$this->url('&t=no')?>"><i class="fa fa-times-circle-o"></i> 未通过的认证</a></li>
		</ul>
	</div>
	<form method="post" action="<?=$this->url('action=batch')?>" class="sui-datalist">
		<div class="sui-toolbar">
			<ul class="pagination pagination-sm pull-right">
			<?=$this->paginator($this->datalist)?>
			</ul>
			<button type="submit" name="act" value="delete" class="btn btn-default btn-sm" onclick="return confirm('确定要删除所选记录吗?');"> <i class="fa fa-trash-o"></i> 删除</button>
		</div>
		<table width="100%" class="table table-striped" data-plugin="chk-group">
			<thead>
				<tr>
					<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
					<th width="150">认证类型</th>
					<th width="180">用户帐号</th>
					<th>反馈</th>
					<th width="80">状态</th>
					<th width="160">创建时间</th>
					<th width="80">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!count($this->datalist)) { ?>
				<tr align="center">
					<td colspan="7"><div class="notfound">找不到相关信息</div></td>
				</tr>
				<?php } else { foreach ($this->datalist as $row) { ?>
				<tr>
					<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>"><?php switch($row['type']) {
					case 'name': echo '实名认证'; break;	
					case 'mobile': echo '手机认证'; break;	
					case 'email': echo '邮箱认证'; break;	
					case 'vip': echo 'VIP认证'; break;	
					case 'staff': echo '集团员工认证'; break;	
					case 'enterprise': echo '企业认证'; break;	
					}?></a></td>
							<td><?=$row['username']?></td>
							<td style="color:red"><?=$row['feedback'] ? $row['feedback'] : ''?></td>
					<td><?php switch($row['status']) {
					case 0: echo '<font class="label label-default">待审核</font>'; break;	
					case 1: echo '<font class="label label-success">通过</font>'; break;	
					case -1: echo '<font class="label label-danger">不通过</font>'; break;	
					}?></td>
					<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
					<td><a href="<?=$this->url('action=detail&id=' . $row['id'].'&ref='.$this->_request->url)?>">查看</a> <a href="<?=$this->url('action=delete&id=' . $row['id'].'&ref='.$this->_request->url)?>" onclick="return confirm('确定要删除这条记录吗?')">删除</a></td>
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
	</form>
</div>
