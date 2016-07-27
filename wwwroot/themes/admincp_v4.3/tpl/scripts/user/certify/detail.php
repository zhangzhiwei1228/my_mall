<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
switch($this->data['type']) {
	case 'name': $title = '实名认证'; break;	
	case 'mobile': $title = '手机认证'; break;	
	case 'email': $title = '邮箱认证'; break;	
	case 'vip': $title = 'VIP认证'; break;	
	case 'staff': $title = '集团员工认证'; break;	
	case 'enterprise': $title = '企业认证'; break;	
}
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem" action="<?=$this->url('action=verify&id='.$this->data['id'])?>">
		<?php if ($this->data['type'] == 'name') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">真实姓名：</label>
			<div class="col-sm-9 form-control-static">
				<?=$this->data->user['realname']?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">身份证：</label>
			<div class="col-sm-9 form-control-static">
				<?=$this->data->user['idcard']?>
			</div>
		</div>
		<?php } elseif ($this->data['type'] == 'enterprise') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">公司名称：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_name']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">法人名称：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['realname']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">法人身份证：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['idcard']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">组织机构代码：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_code']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">公司性质：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_type']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">注册地址：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_addr']?></div>
		</div>
		<?php } elseif ($this->data['type'] == 'staff') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">姓名：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['realname']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">身份证：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['idcard']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">工作单位：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_name']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">工作地点：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['company_addr']?></div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">部门：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['position']?></div>
		</div>
		<?php } elseif ($this->data['type'] == 'email') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">E-mail：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['email']?></div>
		</div>
		<?php } elseif ($this->data['type'] == 'mobile') { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">手机号码：</label>
			<div class="col-sm-9 form-control-static"><?=$this->data->user['mobile']?></div>
		</div>
		<?php } ?>
		<?php if ($this->data['attachments']) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">提交材料：</label>
			<div class="col-sm-9"><?php
				$imgs = explode(',', $this->data['attachments']);
				foreach($imgs as $img) { ?>
				<a href="<?=$this->baseUrl($img)?>" target="_blank" style="border:solid 1px #ddd; background:#fff;"><img src="<?=$this->baseUrl($img)?>" height="100" /></a>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-sm-2 control-label">处理意见：</label>
			<div class="col-sm-9"><textarea name="feedback" class="form-control" rows="3"><?=$this->data['feedback']?></textarea>
				<div class="help-block">认证不通过时，处理意见将反馈给用户</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button type="submit" name="status" value="1" class="btn btn-success">通过</button>
				<button type="submit" name="status" value="-1" class="btn btn-danger">不通过</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">返回上一页</button>
			</div>
		</div>
	</form>
</div>