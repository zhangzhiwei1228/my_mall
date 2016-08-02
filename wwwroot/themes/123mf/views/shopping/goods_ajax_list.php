<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-7-28
 * Time: 上午11:47
 */?>
<?php foreach($this->datalist as $row) { ?>
    <?php $row = $row->_raw; ?>
    <?php if(!$row['is_selling'] && $row['is_checked'] != 2) continue; ?>
    <li>
        <a class="bgwhite" href="<?=$this->url('./detail?id='.$row['id'])?>">
            <p class="pic">
                <?php if ($row['sup']['lt']) { ?><i class="sup-lt"><?=$row['sup']['lt']?></i><?php } ?>
                <?php if ($row['sup']['ld']) { ?><i class="sup-ld"><?=$row['sup']['ld']?></i><?php } ?>
                <?php if ($row['sup']['rt']) { ?><i class="sup-rt"><?=$row['sup']['rt']?></i><?php } ?>
                <?php if ($row['sup']['rd']) { ?><i class="sup-rd"><?=$row['sup']['rd']?></i><?php } ?>
                <img src="<?=$row['thumb']?> " width="100%" />
            </p>
            <p class="name"><?=$this->highlight($row['title'], $this->_request->q)?></p>
            <div class="good-price">
                <?php if ($row['skus'][0]['point1'] > 0) { ?>
                    <p class="text01">快乐积分：<span><?=$row['skus'][0]['point1']?></span>积分</p>
                <?php } ?>
                <?php if ($row['skus'][0]['point2'] > 0) { ?>
                    <p class="text01">免费积分：<span><?=$row['skus'][0]['point2']?></span>积分</p>
                <?php } ?>
                <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
                    <p class="text02">现金+免费积分：￥<span><?=$row['skus'][0]['exts']['ext1']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext1']['point']?></span>免费积分</p>
                <?php } ?>
                <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
                    <p class="text03">现金+积分币：￥<span><span><?=$row['skus'][0]['exts']['ext2']['cash']?></span>+<span><span><?=$row['skus'][0]['exts']['ext2']['point']?></span>积分币</p>
                <?php } ?>
                <p class="text04">原价：￥<span class=""><?=$row['skus'][0]['market_price']?></span></p>
            </div>
        </a>
    </li>
<?php } ?>