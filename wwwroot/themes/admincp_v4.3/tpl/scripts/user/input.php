<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }

$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '帐户';
$this->head()->setTitle($this->title);
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');

$role = $this->data['role'] ? $this->data['role'] : $this->_request->role;
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1> <?=$this->head()->getTitle()?> </h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<input type="hidden" name="is_enabled" value="<?=isset($this->data['is_enabled']) ? $this->data['is_enabled'] : 1?>" />
		<div class="form-group">
			<label class="col-sm-2 control-label">帐户名:</label>
			<div class="col-sm-4">
				<input type="text" name="username" value="<?=$this->data['username']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">名称:</label>
			<div class="col-sm-4">
				<input type="text" name="nickname" value="<?=$this->data['nickname']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group <?=$this->_request->role?'hide':''?>">
			<label class="col-sm-2 control-label">账户类型:</label>
			<div class="col-sm-4">
				<select name="role" class="form-control">
					<option value="member" <?=$role=='member'?'selected':''?>>会员</option>
					<option value="agent" <?=$role=='agent'?'selected':''?>>代理商</option>
					<option value="seller" <?=$role=='seller'?'selected':''?>>商家</option>
					<option value="resale" <?=$role=='resale'?'selected':''?>>分销商</option>
				</select>
			</div>
		</div>
		<?php
		if ($role == 'resale') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">分销商等级:</label>
			<div class="col-sm-4">
				<select name="resale_grade" class="form-control">
					<option value="1" <?=$this->data['resale_grade']=='1'?'selected':''?>>一星</option>
					<option value="2" <?=$this->data['resale_grade']=='2'?'selected':''?>>二星</option>
					<option value="3" <?=$this->data['resale_grade']=='3'?'selected':''?>>三星</option>
					<option value="4" <?=$this->data['resale_grade']=='4'?'selected':''?>>四星</option>
				</select>
			</div>
		</div>
		<?php } ?>
		<?php if ($role == 'seller') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">商家区域:</label>
			<div class="col-sm-4">
				<div class="JS_Dmenu1 form-inline">
					<input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
					<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
				</div>
				<div class="help-block">商家所在区域</div>
			</div>
		</div>
		<?php } ?>
		<?php if ($role == 'agent' || $role == 'resale') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">区域代理:</label>
			<div class="col-sm-4">
				<div class="JS_Dmenu2 form-inline">
					<input type="hidden" name="agent_atext" value="<?=$this->data['agent_atext']?>" />
					<input type="hidden" name="agent_aid" value="<?=$this->data['agent_aid']?>" />
				</div>
				<div class="JS_Mtree" style="width:600px;display: none"></div>
				<div class="help-block">代理商或四星分销商所代理区域</div>
				<input type="hidden" name="destination" value="<?php echo $this->data['destination'] ? $this->data['destination'] : $this->data['agent_aid'];?>" />
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">电子邮箱:</label>
			<div class="col-sm-4">
				<input type="text" name="email" value="<?=$this->data['email']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">手机号码:</label>
			<div class="col-sm-4">
				<input type="text" name="mobile" value="<?=$this->data['mobile']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">登陆密码:</label>
			<div class="col-sm-4">
				<input type="password" name="password" value="" class="form-control" />
				<div class="help-block">不修改请留空</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">交易密码:</label>
			<div class="col-sm-4">
				<input type="password" name="pay_pass" value="" class="form-control" />
				<div class="help-block">不修改请留空</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">有效期至:</label>
			<div class="col-sm-4">
				<input type="text" name="expriy_time" value="<?=$this->data['expriy_time'] ? date(DATETIME_FORMAT, $this->data['expriy_time']) : ''?>" class="form-control" />
				<div class="help-block">为空表示永久有效</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">上级UID:</label>
			<div class="col-sm-4">
				<?=$this->data->parent['username']?>
				<input type="text" name="parent_id" value="<?=$this->data['parent_id']?>" value="" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-4">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
	dmenu.init('.JS_Dmenu1', {
		rootId: 1,
		limit: 3,
		script: '/misc.php?act=area',
		htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
		firstText: '请选择所在地',
		defaultText: '请选择',
		selected: $('input[name=area_id]').val(),
		callback: function(el, data) {
			var location = $('.JS_Dmenu>select>option:selected').text();
			$('input[name=area_id]').val(data.id > 0 ? data.id : 0); 
			$('input[name=area_text]').val(location);
		}
	});
	dmenu.init('.JS_Dmenu2', {
		rootId: 1,
		limit: 2,
		script: '/misc.php?act=area',
		htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
		firstText: '请选择所在地',
		defaultText: '请选择',
		selected: $('input[name=agent_aid]').val(),
		callback: function(el, data) {
			var agent_aid = '<?=$this->data['agent_aid']?>';
			if(agent_aid && agent_aid != data.id) {
				$('.JS_Mtree').hide();
			}
			if(data.level == 3) {
				seajs.use('/assets/js/mtree/mtree.sea.js', function(mtree) {
					mtree.init($('.JS_Mtree'), {
						script:'<?=$this->url('controller=region&action=getMtreeArea')?>'+'?area_id='+data.id,
						input:$('input[name=destination]'),
						disableIds:[<?=$this->ids?>]
					});
				});
				$('.JS_Mtree').show();
			}
			var location = $('.JS_Dmenu>select>option:selected').text();
			$('input[name=agent_aid]').val(data.id > 0 ? data.id : 0);
			$('input[name=agent_atext]').val(location);
		}
	});
});
</script>