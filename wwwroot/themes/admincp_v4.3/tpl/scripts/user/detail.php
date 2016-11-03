<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '帐户信息';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<style type="text/css">
	/* 登录页面结束 */

.sui-profile {
	background: #ddd;
	border-radius: 3px;
	padding: 15px;
	position: relative;
}

.sui-profile .profile-avatar {
	margin: 12px auto;
	width: 128px;
	height: 128px;
	border-radius: 128px;
	border: solid 2px #333;
	padding: 3px;
	background: #fff;
	overflow: hidden;
}

.sui-profile .profile-avatar>img {
	max-width: 100%;
	max-height: 100%;
	border-radius: 100%;
	background: #fff;
	/*border: solid 2px rgb(38, 43, 54);*/
}

.sui-profile .profile-name {
	color: #333;
	text-align: center;
	font-size: 20px;
	margin-bottom: 15px;
}

.sui-profile .profile-name>small {
	display: block;
	font-size: 14px;
	color: #666;
}

.sui-profile .list-group {
	border: none;
}

.sui-profile .list-group-item {
	background: none;
	border: none;
	padding: 8px 2px;
	border-bottom: solid 1px #ccc;
	color: #000;
	text-align: right;
	border-radius: 0;
}

.sui-profile .list-group-item label {
	float: left;
	font-weight: bold;
	color: #666;
}

.sui-profile .profile-options .btn-block {
	margin-top: 10px;
	font-weight: bold;
}

.sui-profile .profile-edit {
	position: absolute;
	top: 10px;
	right: 10px;
	color: #333;
}

</style>

<div class="row">
	<div class="col-sm-9">
		<div class="sui-box">
			<div class="sui-page-header">
				<h1 class="pull-left">会员信息</h1>
				<ul class="nav nav-pills">
					<li class="active"><a href="#base" data-toggle="tab">个人资料</a></li>
					<li><a href="#auth" data-toggle="tab">会员认证</a></li>
					<li><a href="#credit" data-toggle="tab">积分明细</a></li>
					<li><a href="#staff" data-toggle="tab">员工帐号</a></li>
				</ul>
			</div>
			<div class="tab-content">
				<form id="base" class="form-horizontal tab-pane fade active in" style="padding-top:30px" method="post" action="<?=$this->url('action=profile&id='.$this->_request->id)?>">
					<?php
						foreach($this->extFields as $name => $item) {
							list($type, $label, $opts) = $item;
					?>
					<div class="form-group">
						<label class="control-label col-sm-2"><?=$label?>：
							<input type="hidden" name="ext[<?=$name?>][name]" value="<?=$label?>">
						</label>
						<?php if (trim($type) == 'text') { ?>
						<div class="col-sm-4">
							<input type="text" name="ext[<?=$name?>][value]" value="<?=$this->data->getExtField($name)?>" class="form-control">
						</div>
						<?php } elseif (trim($type) == 'select') { ?>
						<div class="col-sm-4">
							<select name="ext[<?=$name?>][value]" class="form-control">
								<?php
								foreach($opts as $v) { ?>
								<option <?=$this->data->getExtField($name) == trim($v)?'selected':''?>><?=trim($v)?></option>
								<?php } ?>
							</select>
						</div>
						<?php } elseif (trim($type) == 'textarea') { ?>
						<div class="col-sm-6">
							<textarea name="ext[<?=$name?>][value]" class="form-control" rows="4"><?=$this->data->getExtField($name)?></textarea>
						</div>
						<?php } elseif (trim($type) == 'birthday') { ?>
						<?php list($y,$m,$d) = explode(',', $this->data->getExtField($name)); ?>
						<div class="col-sm-9 form-inline">
							<select name="ext[<?=$name?>][value][year]" class="form-control">
								<?php $year = date('Y');
								for($i=$year; $i>=$year-100; $i--) { ?>
								<option value="<?=$i?>" <?=$i==$y ? 'selected' : ''?>><?=$i?>年</option>
								<?php } ?>
							</select>
							<select name="ext[<?=$name?>][value][month]" class="form-control">
								<?php for($i=1; $i<=12; $i++) { ?>
								<option value="<?=$i?>" <?=$i==$m ? 'selected' : ''?>><?=$i?>月</option>
								<?php } ?>
							</select>
							<select name="ext[<?=$name?>][value][day]" class="form-control">
								<?php for($i=1; $i<=31; $i++) { ?>
								<option value="<?=$i?>" <?=$i==$d ? 'selected' : ''?>><?=$i?>号</option>
								<?php } ?>
							</select>
						</div>
						<?php } elseif (trim($type) == 'gender') { ?>
						<div class="col-sm-9 radio">
							<?php
							foreach($opts as $v) { ?>
							<label><input type="radio" name="ext[<?=$name?>][value]" value="<?=trim($v)?>" <?=$this->data->getExtField($name)==trim($v)?'checked':''?>/> <?=trim($v)?></label>
							<?php } ?>
						</div>
						<?php } elseif (trim($type) == 'area') { ?>
						<div class="col-sm-9 form-inline JS_Dmenu">
							<input type="hidden" name="ext[<?=$name?>][value]" value="<?=$this->data->getExtField($name)?>" />
						</div>
						<script>
						seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
							dmenu.init('.JS_Dmenu', {
								rootId: 1,
								script: '/misc.php?act=area',
								htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
								firstText: '请选择所在地',
								defaultText: '请选择',
								selected: $('input[name="ext[<?=$name?>][value]"]').val(),
								callback: function(el, data) { 
									var location = $('.JS_Dmenu>select>option:selected').text();
									$('input[name="ext[<?=$name?>][value]"]').val(location);
								}
							});
						});
						</script>
						<?php } ?>
					</div>
					<?php } ?>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-5">
							<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
							<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
						</div>
					</div>
				</form>
				<div id="auth" class="tab-pane fade">
					<table class="table table-striped table-loan">
						<thead>
							<tr>
								<th>认证项目</th>
								<th>状态</th>
								<th>通过日期</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if ($this->auth->total()) {
							foreach($this->auth as $row) { if ($row['status'] != 1) { continue; }?>
							<tr>
								<td><?php switch($row['type']) {
									case 'name': echo '实名认证'; break;	
									case 'mobile': echo '手机认证'; break;	
									case 'email': echo '邮箱认证'; break;	
									case 'vip': echo 'VIP认证'; break;	
									case 'staff': echo '集团员工认证'; break;	
									case 'enterprise': echo '企业认证'; break;	
								}?></td>
								<td><?=$row['status'] == 1 ? '<i class="icon icon-tick"></i> 通过' : '未通过'?></td>
								<td><?=date('Y/m/d H:i', $row['create_time'])?></td>
							</tr>
							<?php } } else { echo '<tr><td colspan="3" class="notfound">此帐户未通过任何认证</td></tr>'; }?>
						</tbody>
					</table>
				</div>
				<div id="credit" class="tab-pane fade">
					<script type="text/javascript">
						$('#credit').load('<?=$this->url('&action=credit')?>');
					</script>
				</div>
				<div id="staff" class="tab-pane fade">
					<script type="text/javascript">
						$('#staff').load('<?=$this->url('&action=staff')?>');
					</script>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="sui-profile">
			<a href="<?=$this->url('&action=edit')?>" class="profile-edit">[编辑]</a>
			<div class="profile-avatar">
				<img src="<?=$this->data['avatar']?$this->baseUrl($this->data['avatar']):'./img/no-avatar.png'?>">
			</div>
			<div class="profile-name">
				<?=$this->data['username']?>
				<small><?=$this->data['email']?></small>
			</div>
			<ul class="list-group">
				<li class="list-group-item"><label>帐户余额：</label> &yen; <?=$this->data['balance']?></li>
				<li class="list-group-item"><label>帮帮币：</label> <?=$this->data['credit']?></li>
				<li class="list-group-item"><label>快乐积分：</label> <?=$this->data['credit_happy']?></li>
				<li class="list-group-item"><label>积分币：</label> <?=$this->data['credit_coin']?></li>
				<li class="list-group-item"><label>帐户等级：</label> <?=$this->data->grade['name']?></li>
				<li class="list-group-item"><label>注册来源：</label> <?php if ($this->data['ref']) { ?> <a href="<?=$this->data['ref']?>" target="_blank"><?=$this->cutstr($this->data['ref'], 50)?></a> <?php } else { ?>直接访问<?php } ?> </li>
				<?php if ($this->data['referrals_id']) { ?>
				<li class="list-group-item"><label>推荐人：</label> <a href="<?=$this->url('&id='.(int)$this->data->referrals['id'])?>" target="_blank"><?=$this->data->referrals['username']?></a> </li>
				<?php } ?>
				<li class="list-group-item"><label>注册时间：</label> <?=date(DATETIME_FORMAT, $this->data['create_time'])?></li>
				<li class="list-group-item"><label>最后登陆：</label> <?=date(DATETIME_FORMAT, $this->data['last_login_time'])?> <br>(<?=new Ip_Location(long2ip($this->data['last_login_ip']))?>)</li>
			</ul>
			<div class="profile-options">
				<a href="<?=$this->url('module='.($this->data['role']=='member'?'usercp':'agent').'&controller=passport&action=login&token='.$this->data->getToken())?>" target="_blank" class="btn btn-block btn-success">
					<i class="glyphicon glyphicon-log-in"></i> 用此帐户登陆</a>
				<?php if ($this->data->isBlacklist()) { ?>
				<a href="<?=$this->url('&action=remove_blacklist&ref='.$this->_request->url)?>" class="btn btn-block btn-info">
					<i class="fa fa-minus-square-o"></i> 移出黑名单</a>
				<?php } else { ?>
				<a href="<?=$this->url('&action=add_blacklist&ref='.$this->_request->url)?>" class="btn btn-block btn-info">
					<i class="fa fa-plus-square-o"></i> 加入黑名单</a>
				<?php } ?>
				<a href="<?=$this->url('&action=delete&ref='.$this->_request->ref)?>" onclick="return confirm('确定要删除这条记录吗?')" class="btn btn-block btn-danger">
					<i class="fa fa-trash-o"></i> 删除此帐户</a></li>
			</div>
		</div>
	</div>
</div>