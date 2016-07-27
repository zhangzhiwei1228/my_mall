<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '自定义页面';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
?>
<div class="primary">
		<h1 class="page-title"><?=$this->title?></h1>
	<form method="post" enctype="multipart/form-data" class="horizontal">
			<div class="form-group">
					<label class="control-label col-sm-2">相关商品:</label>
						<div class="col-sm-9"><div class="thumb left" style="margin-right:10px"><a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$this->data->goods['id'])?>" target="_blank">
							<img src="<?=$this->baseUrl($this->data->goods['image'])?>" /></a>
						</div>
						<p style="width:360px;"><?=$this->data->goods['title']?><br />
				[<a href="<?=$this->url('module=default&controller=goods&action=detail&id='.$this->data->goods['id'])?>" target="_blank">查看商品</a>]
					 	</p></div>
				</div>
		<div class="form-group">
			<label class="control-label col-sm-2">评论人:</label>
			<div class="col-sm-9"><?=$this->data['sender_id'] ? $this->data->sender['username'] : '游客'?>
							[<a href="<?=$this->url('controller=member&action=detail&id='.$this->data['id'])?>">查看</a>]</div>
			<div class="col-sm-9"><?=long2ip($this->data['client_ip'])?>
							(<?=new Ip_Location(long2ip($this->data['client_ip']))?>)</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">评论内容:</label>
			<div class="col-sm-9"><div style="width:420px; color:#093"><?=$this->data['comment']?></div></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">评分:</label>
			<div class="col-sm-9"><?=str_repeat('★', $this->data['score'])?></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">回复内容:</label>
			<div class="col-sm-9"><textarea name="reply" class="x-large" rows="6"><?=$this->data['reply']?></textarea></div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2">前台显示:</label>
			<div class="col-sm-9"><label><input type="checkbox" id="show-checkbox" value="1" />打勾表示允许显示</label>
							<input type="hidden" name="is_show" value="<?=isset($this->data['is_show']) ? $this->data['is_show'] : 1?>" />
							<script type="text/javascript">
					$('#show-checkbox').change(function(){ $('input[name=is_selling]').val(this.checked ? 1 : 0); });
					$('#show-checkbox').attr('checked', <?=$this->data['is_show'] ? 'true' : 'false'?>);
				</script>
						</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9">
			<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
			<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div> 
