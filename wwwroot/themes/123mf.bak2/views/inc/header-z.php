<div class="nn-header">
        <div class="nn-logo"><a href="<?=$this->url('default')?>"><img src="<?php echo static_file('mobile/img/img-02.png'); ?> " alt=""></a></div>
        <div class="nn-te"><a href="<?=$this->url('default')?>">
        <img src="<?php echo static_file('mobile/img/nimg-01.png'); ?> " alt=""> </a></div>
        <div class="nn-right">
        	<?php if ($this->user->exists()) { ?>
        	<span><a href="<?=$this->url('/usercp')?>"><?=$this->user['nickname']?></a></span>
            <!-- <span><a href="<?=$this->url('/usercp/passport/logout')?>">退出</a></span> -->
        	<?php } else { ?>
            <span><a href="<?=$this->url('/usercp/passport/login')?>">登陆</a></span>
            <span><a href="<?=$this->url('/usercp/passport/register')?>">注册</a></span>
            <?php } ?>
        </div>
    </div>
    <?php include_once VIEWS.'inc/sss.php'; ?>
<div class="h-107"></div>