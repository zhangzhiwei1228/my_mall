<?php
if(!defined('APP_KEY')) { exit('Access Denied'); }

$this->title = t('ERR_NOT_FOUND_PAGE');
$this->head()->setTitle($this->title);
?>

<div class="alert alert-minimal">
		<h2 class="page-title"><?=$this->title?></h2>
		<div id="page404" class="notice-page">
								<?=t('ERR_NOT_FOUND_PAGE_MESSAGE')?>
				<h3 style="margin-top:20px;">
						<?=t('TRY_OPERATION')?>
				</h3>
				<ul class="operate">
						<li><a href="<?=base64_decode($this->_request->url)?>">
								<?=t('REFRESH_CUR_PAGE')?>
								</a></li>
						<li><a href="<?=$_SERVER['HTTP_REFERER']?>">
								<?=t('BACK_PREV_PAGE')?>
								</a></li>
						<li><a href="<?=$this->url('controller=index')?>">
								<?=sprintf(t('BACK_HOME_PAGE'), Setting::get('sitename'))?>
								</a></li>
				</ul>
		</div>
</div>
