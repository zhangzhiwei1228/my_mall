<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle('帐户明细');
?>

<div class="panel">
	<div class="row">
		<div class="col-sm-9">
			<div class="sui-page-header">
				<ul class="nav nav-pills">
					<li class="active"><a href="#logs" data-toggle="tab">帐户流水明细</a></li>
					<li><a href="#banks" data-toggle="tab">已绑定银行卡</a></li>
					<li><a href="#profile" data-toggle="tab">帐户持有人资料</a></li>
				</ul>
			</div>
			<div class="panel-body tab-content dl-horizontal">
				<div id="logs" class="tab-pane fade in active">
					<table width="100%" class="table table-striped" data-plugin="chk-group">
						<thead>
							<tr>
								<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
								<!-- <th width="80">流水号</th> -->
								<th width="120">发生时间</th>
								<th width="70">类型</th>
								<th>备注</th>
								<th width="100">收入</th>
								<th width="100">支出</th>
								<th width="100">结余</th>
								<th width="60">状态</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!count($this->logs)) { ?>
							<tr>
								<td colspan="8"><div class="notfound">找不到相关信息</div></td>
							</tr>
							<?php } else { foreach ($this->logs as $row) { ?>
							<tr>
								<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
								<td><?=$row['create_time'] ? date(DATETIME_FORMAT, $row['create_time']) : 'N/A'?></td>
								<!-- <td><?=$row['id']?></td> -->
								<td><?=M('User_Money')->getTypeText($row['type'])?></td>
								<td><?=$row['remark']?></td>
								<td style="color:green"><?=$row['amount']>0?$this->currency($row['amount']):'-'?></td>
								<td style="color:red"><?=$row['amount']<0?$this->currency($row['amount']):'-'?></td>
								<td><?=$this->currency($row['balance'])?></td>
								<td><?php switch ($row['status']) {
									case 0: echo '<font class="label label-danger">撤销</font>'; break;	
									case 1: echo '<font class="label label-default">未入账</font>'; break;	
									case 2: echo '<font class="label label-success">已入账</font>'; break;	
								}?></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
					<div align="center">
						<ul class="pagination pagination-sm">
							<?=$this->paginator($this->logs)?>
						</ul>
					</div>
				</div>
				<div id="banks" class="tab-pane fade">
					<table width="100%" class="table table-striped" data-plugin="chk-group">
						<thead>
							<tr>
								<th width="20" align="center"><input type="checkbox" role="chk-all" /></th>
								<th width="150">银行名称</th>
								<th width="100">开户行</th>
								<th>卡号</th>
								<th width="120">持卡人</th>
								<th width="120">绑定时间</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!count($this->banks)) { ?>
							<tr>
								<td colspan="6"><div class="notfound">找不到相关信息</div></td>
							</tr>
							<?php } else { foreach ($this->banks as $row) { ?>
							<tr>
								<td align="center"><input type="checkbox" name="ids[]" role="chk-item" value="<?=$row['id']?>" /></td>
								<td><?=$row['bank_name']?></td>
								<td><?=$row['bank_sub_branch']?></td>
								<td><?=$row['bank_account']?></td>
								<td><?=$row['account_name']?></td>
								<td><?=date(DATETIME_FORMAT, $row['create_time'])?></td>
							</tr>
							<?php } } ?>
						</tbody>
					</table>
				</div>
				<div id="profile" class="tab-pane fade">
					<div class="form-group">
						<label class="control-label col-sm-2">帐户名：</label>
						<div class="col-sm-9"><?=$this->data['username']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">真实姓名：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['realname']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">性别：</label>
						<div class="col-sm-9"><?php switch($this->data->user->profile['gender']) {
						case 'male': echo '男'; break;
						case 'female': echo '女'; break;
						default: echo '保密'; break;
					}?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">出生日期：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['birthday']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">最高学历：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['education']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">婚姻状况：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['marital'] ? '已婚' : '未婚'?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">职业：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['profession']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">月收入：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['salary']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">所地址：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['location']?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">详细地址：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['address'] ? $this->data->user->profile['address'] : 'N/A'?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">邮政编码：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['zipcode'] ? $this->data->user->profile['zipcode'] : 'N/A'?></div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">手机：</label>
						<div class="col-sm-9"><?=$this->data->user->profile['mobile'] ? $this->data->user->profile['mobile'] : 'N/A'?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="sui-profile">
				<div class="profile-avatar">
					<img src="<?=$this->data['avatar']?$this->baseUrl($this->data['avatar']):'./img/no-avatar.png'?>">
				</div>
				<div class="profile-name">
					<?=$this->data['nickname']?>
					<small><?=$this->data['email']?></small>
				</div>
				<ul class="list-group">
					<li class="list-group-item"><label>支出金额：</label> <?=$this->currency($this->data['expend'])?></li>
					<li class="list-group-item"><label>收入金额：</label> <?=$this->currency($this->data['income'])?></li>
					<li class="list-group-item"><label>冻结资金：</label> <?=$this->currency($this->data['unusable'])?></li>
					<li class="list-group-item"><label>可用余额：</label> <?=$this->currency($this->data['balance'])?></li>
				</ul>
				<div class="profile-options">
					<a href="<?=$this->url('controller=recharge&action=add&uid='.$this->data['id'].'&ref='.$this->_request->url)?>" class="btn btn-block btn-info">充值</a>
					<a href="<?=$this->url('controller=withdraw&action=add&uid='.$this->data['id'].'&ref='.$this->_request->url)?>" class="btn btn-block btn-danger">提现</a>
				</div>
			</div>
		</div>
	</div>
</div>