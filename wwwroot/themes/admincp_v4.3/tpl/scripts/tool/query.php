<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }
$this->title = 'SQL 查询分析器';
$this->head()->setTitle($this->title);
?>


<div class="sui-box">
	<div class="sui-page-header">
		 <h1><?=$this->head()->getTitle()?></h1>
	</div>

	<p class="alert alert-warning" style="margin-top:15px"><strong>注意!</strong> 执行前请认真审查您的SQL语句。任何一条错误的SQL都有可能给数据带来灾难性后果！</p>
	<form method="post">
		<div class="form-group">
			<textarea class="form-control" name="sql" rows="6" placeholder="请输入SQL语句"><?=$this->_request->getPost('sql')?></textarea>
		</div>
		<button type="submit" class="btn btn-primary" onclick="return confirm('确定要执行这条SQL吗？')">执行</button><br /><br />
		
		<?php if (isset($this->affected)) { ?>
		<h3 class="heading">SQL执行成功！ 
			<small>影响条数 <strong><?=$this->affected?></strong> Row(s)</small></h3>
		<?php } ?>
		<?php if ($this->result) { ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<?php foreach ($this->fields as $field) { ?>
					<th><?=$field?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($this->result as $row) { ?>
				<tr>
					<?php foreach ($this->fields as $field) { ?>
					<td><?=htmlspecialchars($row[$field])?></td>
					<?php } ?>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } ?>
	</form>
</div>