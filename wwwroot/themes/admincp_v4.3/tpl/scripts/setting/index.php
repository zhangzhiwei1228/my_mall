<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = '站点设置';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"> <?=$this->head()->getTitle()?> </h1>
		<ul class="nav nav-pills">
			<li class="active"><a href="#base" data-toggle="tab">基本设置</a></li>
			<li><a href="#credit" data-toggle="tab">积分设置</a></li>
			<li><a href="#rule" data-toggle="tab">交易设置</a></li>
			<li><a href="#smtp" data-toggle="tab">邮箱服务器</a></li>
		</ul>
	</div>

	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="tab-content">
			<div id="base" class="tab-pane fade active in">
				<div class="form-group">
					<label class="control-label col-sm-2">站点名称</label>
					<div class="col-sm-4">
						<input type="text" name="config[sitename]" class="form-control" value="<?=$this->data['sitename']?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">主题</label>
					<div class="col-sm-4">
						<select name="config[theme]" class="form-control">
							<?php
							$curTheme = $this->data['theme'];
							foreach($this->themes as $row) { 
								if (substr($row['name'], 0, 5) == 'admin') continue;
								$path = '/'.str_replace(array(WWW_DIR, '\\'), array('', '/'), $row['path'].$row['name']).'/';
							?>
							<option value="<?=$path?>" <?=$path==$this->data['theme']?'selected':''?>> <?=$row['name']?> </option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">页脚文本</label>
					<div class="col-sm-6">
						<textarea name="config[footer_text]" rows="3" class="form-control"><?=$this->data['footer_text']?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">客服QQ</label>
					<div class="col-sm-6">
						<textarea name="config[service_qqs]" rows="3" class="form-control"><?=$this->data['service_qqs']?></textarea>
					</div>
				</div>
			</div>
			<div id="credit" class="tab-pane fade">
				<div class="form-group">
					<label class="control-label col-sm-2">注册送积分</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[credit_reg]" class="form-control" value="<?=$this->data['credit_reg']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">每消费1元得</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[credit_expend]" class="form-control" value="<?=$this->data['credit_expend']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">每推荐一个用户</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[credit_invite]" class="form-control" value="<?=$this->data['credit_invite']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">帮帮币比率</label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">1元人民币=</span>
							<input type="text" name="config[credit_rate]" class="form-control" value="<?=$this->data['credit_rate']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">快乐积分比率</label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">1元人民币=</span>
							<input type="text" name="config[credit_happy_rate]" class="form-control" value="<?=$this->data['credit_happy_rate']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">积分币比率</label>
					<div class="col-sm-4">
						<div class="input-group">
							<span class="input-group-addon">1元人民币=</span>
							<input type="text" name="config[credit_coin_rate]" class="form-control" value="<?=$this->data['credit_coin_rate']?>" />
							<span class="input-group-addon">点</span>
						</div>
					</div>
				</div>
			</div>
			<div id="smtp" class="tab-pane fade">
				<div class="form-group">
					<label class="control-label col-sm-2">SMTP 地址</label>
					<div class="col-sm-4">
						<input type="text" name="config[smtp_host]" class="form-control" value="<?=$this->data['smtp_host']?>" />
						<p class="help-block">如：smtp.suconet.com</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">SMTP 端口</label>
					<div class="col-sm-4">
						<input type="text" name="config[smtp_port]" class="form-control" value="<?=$this->data['smtp_port']?>" />
						<p class="help-block">一般默认端口为25</p>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">邮箱帐户</label>
					<div class="col-sm-4">
						<input type="text" name="config[smtp_user]" class="form-control" value="<?=$this->data['smtp_user']?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">邮箱密码</label>
					<div class="col-sm-4">
						<input type="password" name="config[smtp_pass]" class="form-control" value="<?=$this->data['smtp_pass']?>" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-4 col-sm-offset-2">
						<div class="checkbox">
						<label><input type="checkbox" name="config[smtp_enabled]" value="1" <?php if ($this->data['smtp_enabled'] == 1) echo 'checked';?>/> 启用邮件服务</label>
					</div>
					</div>
				</div>
			</div>
			<div id="rule" class="tab-pane fade">
				<div class="form-group">
					<label class="control-label col-sm-2">提现限额</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[withdraw_limit_min]" value="<?=$this->data['withdraw_limit_min']?>" class="form-control" />
							<span class="input-group-addon">-</span>
							<input type="text" name="config[withdraw_limit_max]" value="<?=$this->data['withdraw_limit_max']?>" class="form-control" />
							<span class="input-group-addon">元</span>
						</div>
						<div class="help-block">限制提现额度区间，设为0表示不限</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">退换货地址</label>
					<div class="col-sm-4">
						<textarea name="config[return_addr]" class="form-control" rows="4"><?=$this->data['return_addr']?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">提现手续费</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input name="config[withdraw_rate]" value="<?=$this->data['withdraw_rate']?>" class="form-control" />
							<span class="input-group-addon">%</span>
						</div>
					</div>
				</div>
				<h4 class="heading">超时处理</h4>
				<div class="form-group">
					<label class="control-label col-sm-2">付款超时</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[timeout_pay]" value="<?=$this->data['timeout_pay']?>" class="form-control" />
							<span class="input-group-addon">秒</span>
						</div>
						<div class="help-block">系统会自己关闭超时订单 </div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">发货超时</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[timeout_delivery]" value="<?=$this->data['timeout_delivery']?>" class="form-control" />
							<span class="input-group-addon">秒</span>
						</div>
						<div class="help-block">系统只发出提醒，不会改变订单状态 </div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">签收超时</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[timeout_confirm]" value="<?=$this->data['timeout_confirm']?>" class="form-control" />
							<span class="input-group-addon">秒</span>
						</div>
						<div class="help-block">超时后系统将自动改变订单状态为已签收，并关闭退换货功能。 </div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">评价超时</label>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" name="config[timeout_comment]" value="<?=$this->data['timeout_comment']?>" class="form-control" />
							<span class="input-group-addon">秒</span>
						</div>
						<div class="help-block">超时后系统将给好评 </div>
					</div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-4 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存</button>
			</div>
		</div>
	</form>
</div>