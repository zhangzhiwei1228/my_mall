<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<h5 class="heading">活动规则</h5>
<div class="well">
	<div class="form-group">
		<label class="control-label col-sm-2">消费满：</label>
			<div class="col-sm-9"><div class="input-group" style="width:300px">
				<span class="input-group-addon">&yen;</span>
				<input type="text" name="setting[precond_amount]" value="<?=$this->setting['precond_amount']?>" class="form-control" /> 
				<span class="input-group-addon">元</span>
			</div></div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">减免：</label>
			<div class="col-sm-9"><div class="input-group" style="width:300px">
				<span class="input-group-addon">&yen;</span>
			<input type="text" name="setting[reduce_amount]" value="<?=$this->setting['reduce_amount']?>" class="form-control" />
			<span class="input-group-addon">元</span>
			</div></div>
	</div>
	<div class="form-group">
		<label class="control-label col-sm-2">允许累加：</label>
		<div class="col-sm-9">
			<div class="radio">
				<label><input type="radio" name="setting[accumulative]" value="1" <?=$this->setting['accumulative']==1?'checked':''?>> 是 </label> 
				<label><input type="radio" name="setting[accumulative]" value="0" <?=$this->setting['accumulative']==0?'checked':''?>> 否 </label>
			</div>
		</div>
	</div>
</div>