<?php if(!defined('APP_KEY')) { exit('Access Denied'); } ?>
<h4 class="heading">活动规则</h4>
<div class="form-group">
	<label class="control-label col-sm-2">消费满:</label>
		<div class="col-sm-9"><div class="input-group" style="width:300px">
		<span class="input-group-addon">&yen;</span>
		<input type="text" name="setting[precond_amount]" value="<?=$this->setting['precond_amount']?>" class="form-control" />
		<span class="input-group-addon">元</span></div>
		</div>
</div>
<div class="form-group">
	<label class="control-label col-sm-2">重量限制:</label>
		<div class="col-sm-9"><div class="input-group" style="width:300px">
		<input type="text" name="setting[precond_weight]" value="<?=$this->setting['precond_weight']?>" class="form-control" /> 
		<span class="input-group-addon">公斤</span></div>
		<p class="help-block">数值为0表示不限</p>
		</div>
</div>