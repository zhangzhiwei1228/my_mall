<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-9
 * Time: 上午10:34
 */
?>
<div class="list">
    <ul>
        <?php foreach($this->brand as $row) { ?>
            <li>
                <img src="<?=$this->baseUrl($row['thumb'])?> ">
                <p><?=$row['name']?></p>
            </li>
        <?php } ?>
    </ul>
    <div class="clear"></div>
</div>
