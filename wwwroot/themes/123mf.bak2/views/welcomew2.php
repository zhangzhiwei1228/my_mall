<!DOCTYPE html>
<html>
<head>
<?php include_once VIEWS.'inc/head.php'; ?>
</head>
<body>
   	<div class="nn-header">
        <div class="nn-logo"><a href="<?php echo site_url(''); ?> "><img src="<?php echo static_file('mobile/img/img-02.png'); ?> " alt=""></a></div>
        <div class="nn-te"><a href="<?php echo site_url(''); ?> "><img src="<?php echo static_file('mobile/img/nimg-01.png'); ?> " alt=""></a></div>
        <div class="nn-right">
            <span><input value="登陆" type="submit"></span>
            <span><a href="">注册</a></span>
        </div>
    </div>

    <div class="nn-input">
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
    </div>

	<div class="welcomew2">
		<ul>
			<?php foreach($this->datalist as $row ) { ?>
			<li>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp1"><?=$row['title']?></span></a>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp2"><?=$this->cutstr($row['summary'], 150)?></span></a>
				<a href="<?=$this->url('./detail?id='.$row['id'])?>"><span class="welcomew2-sp3"><?=date(DATE_FORMAT,$row['create_time'])?></span></a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php include_once VIEWS.'inc/footer01.php'; ?>
</body>
</html>