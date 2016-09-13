<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
   	<!-- <div class="nn-header">
        <div class="nn-logo"><a href="<?php echo site_url(''); ?> "><img src="<?php echo static_file('mobile/img/img-02.png'); ?> " alt=""></a></div>
        <div class="nn-te"><a href="<?php echo site_url(''); ?> "><img src="<?php echo static_file('mobile/img/nimg-01.png'); ?> " alt=""></a></div>
        <div class="nn-right">
            <span><input value="登陆" type="submit"></span>
            <span><a href="">注册</a></span>
        </div>
    </div> -->

    <!-- <div class="nn-input">
        <a class="nn-new" href="">搜索导航表</a>
        <div class="nn-big-input">
            <select value="商品" name="" id="">
                <option value="商品">商品</option>
                <option value="商家">商家</option>
            </select>
            <input class="nn-kk" type="text">
            <input class="nn-dis" value="" type="submit">
        </div>
        <div class="nn-big-right"><img src="<?php echo static_file('mobile/img/nimg-03.png'); ?> " alt=""></div>
    </div> -->
    <div style="position:fixed;top:0px;left:0px;" class="n-personal-center-tit">
        <a href="javascript:history.back(-1)"><img width="11" height="21" src="<?php echo static_file('mobile/img/img-22.png')?> " alt=""></a>
        <?php echo $this->_request->cid == 14 ? '消息' : '通知';?>
    </div>
    <div style="height:57px;"></div>
	<div class="welcomew2" style="margin-bottom: 60px">
		<ul>
			<?php foreach($this->datalist as $row ) { ?>
			<li>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp1"><?=$row['title']?></span></a>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp2"><?=$this->cutstr($row['content'], 150)?></span></a>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp3"><?=date(DATE_FORMAT,$row['create_time'])?></span></a>
			</li>
			<?php } ?>
		</ul>
        <ul>
            <?php foreach($this->msglist as $row ) { ?>
                <li>
                    <a href="<?=$this->url('./detail?mid='.$row['id'])?>"><span class="welcomew2-sp1"><?= $row['title'] ?  $row['title'] : '私信' ?><?php echo $row['is_read'] ? '（已读）':'（未读）'?></span></a>
                    <a href="<?=$this->url('./detail?mid='.$row['id'])?>"><span class="welcomew2-sp2"><?=$this->cutstr($row['content'], 150)?></span></a>
                    <a href="<?=$this->url('./detail?mid='.$row['id'])?>"><span class="welcomew2-sp3"><?=date(DATE_FORMAT,$row['create_time'])?></span></a>
                </li>
            <?php } ?>
        </ul>
	</div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
</body>
</html>