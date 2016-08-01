<div class="n-pro-l">
	<span class="n-pro-img">
        <?php if ($row['sup']['lt']) { ?><i class="sup-lt"><?=$row['sup']['lt']?></i><?php } ?>
        <?php if ($row['sup']['ld']) { ?><i class="sup-ld"><?=$row['sup']['ld']?></i><?php } ?>
        <?php if ($row['sup']['rt']) { ?><i class="sup-rt"><?=$row['sup']['rt']?></i><?php } ?>
        <?php if ($row['sup']['rd']) { ?><i class="sup-rd"><?=$row['sup']['rd']?></i><?php } ?>
		<a href="<?=$this->url('goods/detail?id='.$row['id'])?> "><img src="<?=$this->baseUrl($row['thumb'])?> " alt=""></a>
	</span>
	<p class="n-pro-l-tit"><?=$row['title']?></p>
	<div class="n-pro-l-te">
		<ul>
            <?php if ($row['skus'][0]['point1']) { ?>
            <li><strong>快乐积分：</strong><span><?=$row['skus'][0]['point1']?></span><p>积分</p></li> 
            <?php } ?>
            <?php if ($row['skus'][0]['point2']) { ?>
            <li><strong>免费积分：</strong><span><?=$row['skus'][0]['point2']?></span><p>积分</p></li> 
            <?php } ?>
            <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
            <li><strong>现金+免费积分:</strong><span><?=$row['skus'][0]['exts']['ext1']['cash']?><font color="#666">元</font></span><p>+</p><span><?=$row['skus'][0]['exts']['ext1']['point']?></span><p>积分</p></li>
            <?php } ?>
            <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
            <li><strong>现金+免费积分:</strong><span><?=$row['skus'][0]['exts']['ext2']['cash']?><font color="#666">元</font></span><p>+</p><span><?=$row['skus'][0]['exts']['ext2']['point']?></span><p>积分</p></li>
            <?php } ?>
            <li><del ><strong  style="text-decoration: line-through;">原价：￥</strong><span  style="text-decoration: line-through;"><?=$row['skus'][0]['market_price']?></span><p  style="text-decoration: line-through;">元</p></del><del class="fr"><strong class="cheaper"><?php echo $row['notes'];?></strong></del></li>
            
		</ul>
	</div>
</div>



