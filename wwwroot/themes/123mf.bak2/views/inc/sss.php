<div class="nn-input">
        <a class="nn-new" href="<?=$this->url('default/goods/nav')?>">搜索导航表</a>
        <form class="nn-big-input" action="<?=$this->url('default/goods/search')?>">
            <select value="商品" name="" id="">
                <option value="商品">商品</option>
                <option value="商家">商家</option>
            </select>
            <input class="nn-kk" type="text" name="q" value="<?=$this->_request->q?>">
            <input class="nn-dis" value="" type="submit">
        </form>
        <div class="nn-big-right"><a href="<?=$this->url('default/goods')?>"><img src="<?php echo static_file('mobile/img/nimg-03.png'); ?> " alt=""></a></div>
    </div>