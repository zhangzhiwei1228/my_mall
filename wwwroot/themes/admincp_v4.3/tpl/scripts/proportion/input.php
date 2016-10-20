<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '规则';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1><?=$this->head()->getTitle()?></h1>
	</div>
	<div class="sui-page-header">
		<ul class="nav nav-pills">
			<li class="<?php echo $this->data && $this->data['name'] ? '' : 'active'?>"><a href="#base" data-toggle="tab">基本信息</a></li>
			<li class="<?php echo $this->data && $this->data['name'] ? 'active' : ''?>"><a href="#price" data-toggle="tab">价格与库存</a></li>
		</ul>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="tab-content">
			<div id="base" class="tab-pane fade <?php echo $this->data && $this->data['name'] ? '' : 'active in'?> ">
				<div class="form-group">
					<label class="control-label col-sm-2"> 左侧数值 </label>
					<div class="col-sm-4">
						<textarea name="l_digital" class="form-control" rows="2"><?=$this->data['l_digital']?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 左侧名称</label>
					<div class="col-sm-4">
						<select name="left_id" class="form-control">
							<?php foreach($this->coltypes_cash as $row) {?>
								<option value="<?php echo $row['id']?>" <?php echo $this->data['left_id']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
							<?php }?>
						</select>
					</div>
					<input type="checkbox" class="hybrid_pay" <?php echo $this->data['exts'] ? 'checked' : '' ?>  value="<?php echo $this->data['exts'] ? '1' : '0' ?>"><label class="control-label col-sm-0">混合支付</label>
				</div>
				<div class="form-group hybrid" style="<?php echo $this->data['exts'] ? 'display: block' : 'display: none' ?>">
					<label class="control-label col-sm-2"> <span style="color: red">附加条件</span></label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php echo $this->data['exts']->value?>" name="exts[value]">
					</div>
					<label class="control-label col-sm-0"><input type="hidden"  value="<?php echo $this->data['exts']->name ? $this->data['exts']->name : '元' ?>" name="exts[name]">元</label>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 右侧数值 </label>
					<div class="col-sm-4">
						<textarea name="r_digital" class="form-control" rows="2"><?=$this->data['r_digital']?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 右侧名称</label>
					<div class="col-sm-4">
						<select name="right_id" class="form-control">
							<?php foreach($this->coltypes_cash as $row) {?>
								<option value="<?php echo $row['id']?>" <?php echo $this->data['right_id']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
							<?php }?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 所属类型</label>
					<div class="col-sm-4">
						<select name="type" class="form-control">
							<?php foreach($this->coltypes_desc as $row) {?>
								<option value="<?php echo $row['id']?>" <?php echo $this->data['type']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
			<div id="price" class="tab-pane fade <?php echo $this->data && $this->data['name'] ? 'active in' : ''?>">
				<div class="form-group">
					<label class="control-label col-sm-2"> 名称 </label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php echo $this->data['name']?>" name="name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 英文 </label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php echo $this->data['english']?>" name="english">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 价格 </label>
					<div class="col-sm-4">
						<input type="text" class="form-control" value="<?php echo $this->data['price']?>" name="price">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2"> 所属类型</label>
					<div class="col-sm-4">
						<select name="type" class="form-control">
							<?php foreach($this->earnings as $row) {?>
								<option value="<?php echo $row['id']?>" <?php echo $this->data['type']==$row['id'] ? 'selected': '' ?>><?php echo $row['name']?></option>
							<?php }?>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-10 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function() {
		$(".hybrid_pay").click(function() {
			var hybrid = $(this).val();
			if (hybrid == 1) {
				$(this).val(0);
				$(".hybrid").hide();
			}else{
				$(this).val(1);
				$(".hybrid").show();
			}
		});
	});
</script>