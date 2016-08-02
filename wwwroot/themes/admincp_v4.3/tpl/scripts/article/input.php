<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = ($this->_request->getActionName() == 'add' ? '添加' : '修改'). '文章';
$ref = isset($this->_request->ref) ? base64_decode($this->_request->ref) : $this->url('action=list');
$this->head()->setTitle($this->title);
if (!isset($this->data['author'])) {
	$this->data['author'] = $this->admin['nickname'];
}
?>

<div class="sui-box">
	<div class="sui-page-header">
		<h1 class="pull-left"><?=$this->head()->getTitle()?></h1>
		<ul class="nav nav-pills">
			<li class="active"><a href="#base" data-toggle="tab">基本信息</a></li>
			<li><a href="#other" data-toggle="tab">更多设置</a></li>
		</ul>
	</div>
	<form method="post" enctype="multipart/form-data" class="form-horizontal sui-dataitem">
		<div class="tab-content">
				<div id="base" class="tab-pane fade active in">
				<div class="form-group">
					<label class="control-label col-sm-2">标题:</label>
					<div class="col-sm-9"> <input type="text" name="title" value="<?=$this->data['title']?>" class="form-control" /> </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">分类:</label>
					<div class="col-sm-9">
						<select name="category_id" class="form-control">
							<option value="">请选择</option>
							<?php 
							$cid = isset($this->data['category_id']) ? $this->data['category_id'] : $this->_request->cid;
							foreach ($this->categories as $row) { ?>
							<option value="<?=$row['id']?>" <?php if ($cid == $row['id']) echo 'selected';?>> <?=str_repeat('&nbsp;&nbsp;&nbsp;', $row['level'])?> &gt; <?=$row['name']?> </option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">推送地区:</label>
					<div class="col-sm-7">
						<div class="JS_Dmenu form-inline">
							<input type="hidden" name="area_text" value="<?=$this->data['area_text']?>" />
							<input type="hidden" name="area_id" value="<?=$this->data['area_id']?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">正文:</label>
					<div class="col-sm-9"><div class="input-group">
						<textarea name="content" class="form-control" rows="20" data-plugin="editor" data-token="<?=$this->admin->getToken()?>"><?=stripcslashes($this->data['content'])?></textarea>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">发生时间:</label>
					<div class="col-sm-9"><div class="input-group">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<input type="text" class="form-control" data-plugin="datetime-picker" name="post_time" value="<?=date(DATETIME_FORMAT, $this->data['post_time']?$this->data['post_time']:time())?>" />
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">封面:</label>
					<div class="col-sm-9">
						<div class="sui-img-selector form-control" data-plugin="img-selector" data-limit="1" 
							data-ipt="thumb" data-ref="article">
							<div class="sui-img-value"><?=$this->data['thumb']?$this->baseUrl($this->data['thumb']):''?></div>
							<div class="sui-img-selector-box"></div>
							<div class="sui-img-selector-btns">
								<button type="button" class="btn" role="btn">选择图片</button>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">审核:</label>
					<div class="col-sm-9"> <?php $checked = isset($this->data['is_checked']) ? $this->data['is_checked'] : 2 ?>
						<label> <input type="radio" name="is_checked" value="0" <?=$checked==0 ? 'checked' : ''?>/> 不通过</label>
						<label> <input type="radio" name="is_checked" value="1" <?=$checked==1 ? 'checked' : ''?>/> 待审</label>
						<label> <input type="radio" name="is_checked" value="2" <?=$checked==2 ? 'checked' : ''?>/> 通过</label>
						<div class="help-block">
							您可以通过禁用选项临时关闭此分类
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">置顶:</label>
					<div class="col-sm-9"> <?php $checked = isset($this->data['is_best']) ? $this->data['is_best'] : 0 ?>
						<label> <input type="radio" name="is_best" value="1" <?=$checked==1 ? 'checked' : ''?> /> 是</label>
						<label> <input type="radio" name="is_best" value="0" <?=$checked==0 ? 'checked' : ''?>/> 否</label>
						<div class="help-block">
							置顶内容将优先显示。若同时置顶多个，则最后设置的显示最前。
						</div>
					</div>
				</div>
			</div>
			<div id="other" class="tab-pane fade">
				<div class="form-group">
					<label class="control-label col-sm-2">摘要:</label>
					<div class="col-sm-9">
						<textarea name="summary" rows="5" class="form-control"><?=$this->data['summary']?></textarea>
						<div class="help-block">
							摘要是您可以手动添加的内容概要，一些主题会用到这些文字。<br />
							若不填摘要，系统会自动根据正文截取255个字符以内的内容。
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">标签:</label>
					<div class="col-sm-9">
						<textarea name="tags" class="form-control" rows="3" data-plugin="tagsinput"><?=$this->data['tags']?></textarea>
						<div class="help-block">
							用户在搜索时,除了对标题匹配外还会对标签进行匹配<br />
							多个标签请用半角逗号(,)分隔
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">作者:</label>
					<div class="col-sm-9"> <input type="text" name="author" value="<?=$this->data['author']?>" class="form-control" /> </div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">出处:</label>
					<div class="col-sm-9">
						<input type="text" name="source" value="<?=$this->data['source']?>" class="form-control" />
						<div class="help-block">
							为避免版权纠纷，请写明出处。
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-2">引用地址:</label>
					<div class="col-sm-9"> <input type="text" name="ref_url" value="<?=$this->data['ref_url']?>" class="form-control" /> </div>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-9 col-sm-offset-2">
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> 保存</button>
				<button type="button" class="btn" onclick="window.location='<?=$ref?>'">取消</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	seajs.use('/assets/js/dmenu/dmenu.sea.js', function(dmenu) {
		dmenu.init('.JS_Dmenu', {
			rootId: 1,
			script: '/misc.php?act=area',
			htmlTpl: '<select class="form-control" style="width:auto; margin-right:6px"></select>',
			firstText: '请选择所在地',
			defaultText: '请选择',
			selected: $('input[name=area_id]').val(),
			callback: function(el, data) {
				var location = $('.JS_Dmenu>select>option:selected').text();
				$('input[name=area_id]').val(data.id > 0 ? data.id : 0);
				$('input[name=area_text]').val(location);
				$('input[name=zipcode]').val(data.zipcode > 0 ? data.zipcode : '');
			}
		});

	});
</script>