<?php foreach($this->datalist as $row) { ?>
<li class="shop-main w90 bgwhite">
    <a href="<?=$this->url('./detail?id='.$row['id'])?>">
        <?php if($row['ref_img_bg']) {?>
            <img class="fl" src="<?=$this->baseUrl($row['ref_img_bg'])?> "  />
        <?php } else {?>
            <img class="fl" src="<?php echo static_file('m/img/pic14.jpg'); ?> " />
        <?php }?>
        <div class="intro fr">
            <p class="name"><?=$row['name']?></p>
            <p class="phone"><em></em><span><?=$row['tel']?></span></p>
            <p class="address"><em></em><span class=""><?=$row['addr']?></span></p>
        </div>
        <div class="clear"></div>
    </a>
</li>
<?php } ?>
