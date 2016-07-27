<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->head()->setTitle(($this->_request->getActionName() == 'add' ? '添加' : '修改'). '权限组');
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="form-group">
			<label class="control-label col-sm-2"><span class="required">*</span>分组名称:</label>
			<div class="col-sm-9">
				<input type="text" name="name" value="<?=$this->data['name']?>" class="form-control" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">描述:</label>
			<div class="col-sm-9"> <textarea name="description" class="form-control" rows="3"><?=$this->data['description']?></textarea>
			</div>
		</div>
		<?php if (count($this->acl)) { ?>
		<div class="form-group">
			<label class="control-label col-sm-2">允许访问:</label>
			<div class="col-sm-9"> <?php 
					$allow = explode(',', $this->data['allow']);
					foreach($this->acl as $i => $row) { $acls[$row['package']][] = $row; } ?>
				<table class="table table-bordered">
					<?php foreach($acls as $pack => $resource) { $k = md5($pack); ?>
					<tbody data-plugin="chk-group">
						<tr>
							<th style="background:#f0f0f0"><label>
								<input id="<?=$k?>" type="checkbox" role="chk-all" />
								<b><?=$pack?></b> </label></th>
						</tr>
						<tr>
							<td><?php foreach ($resource as $row) { ?>
								<label style="margin-right:15px;">
									<input type="checkbox" name="allow[]" role="chk-item" value="<?=$row['id']?>" class="<?=$k?>" <?=in_array($row['id'], $allow) ? 'checked' : ''?>/>
									<?=$row['description']?> </label>
								<?php } ?></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>