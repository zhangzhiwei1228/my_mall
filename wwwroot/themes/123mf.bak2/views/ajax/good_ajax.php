<?php foreach($this->cates as $cate) { ?>

<div class="list">
    <p class="title">&nbsp;&nbsp;&nbsp;&nbsp;<?=$cate['name']?></p>
     <ul>
        <?php foreach($cate->getChilds() as $row) { ?>
        <li>
            <a href="<?=$this->url('./list?cid='.$row['id'])?>">
                <img src="<?=$this->baseUrl($row['thumb'])?> ">
                <p><?=$row['name']?></p>
            </a>
        </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
</div>
<?php } ?>