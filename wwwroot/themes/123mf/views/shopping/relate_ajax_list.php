<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-2
 * Time: 上午8:29
 */
?>
<?php foreach($this->relateGoods as $row) { $row = $row->_raw;?>
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
                    <p class="text01">帮帮币：<span><?=$row['skus'][0]['point2']?></span>积分</p>
                <?php } ?>
                <?php if ($row['skus'][0]['point3'] > 0) { ?>
                    <p class="text01">积分币：<span><?=$row['skus'][0]['point3']?></span>币</p>
                <?php } ?>
                <?php if ($row['skus'][0]['point4'] > 0) { ?>
                    <p class="text01">抵用券：<span><?=$row['skus'][0]['point4']?></span>券</p>
                <?php } ?>
                <?php if ($row['skus'][0]['point5'] > 0) { ?>
                    <p class="text01">现金：<span><?=$row['skus'][0]['point5']?></span>￥</p>
                <?php } ?>
                <?php if ($row['skus'][0]['exts']['ext1']['cash']) { ?>
                    <p class="text02">现金+帮帮币：￥<span><?=$row['skus'][0]['exts']['ext1']['cash']?></span>+<span><?=$row['skus'][0]['exts']['ext1']['point']?></span>帮帮币</p>
                <?php } ?>
                <?php if ($row['skus'][0]['exts']['ext2']['cash']) { ?>
                    <p class="text03">现金+积分币：￥<span><span><?=$row['skus'][0]['exts']['ext2']['cash']?></span>+<span><span><?=$row['skus'][0]['exts']['ext2']['point']?></span>积分币</p>
                <?php } ?>
                <?php if ($row['skus'][0]['exts']['ext3']['cash']) { ?>
                    <p class="text03">现金+抵用券：￥<span><span><?=$row['skus'][0]['exts']['ext3']['cash']?></span>+<span><span><?=$row['skus'][0]['exts']['ext3']['point']?></span>抵用券</p>
                <?php } ?>
                <p class="text04">原价：￥<span class=""><?=$row['skus'][0]['market_price']?></span></p>
            </div>
        </a>
    </li>
<?php } ?>
