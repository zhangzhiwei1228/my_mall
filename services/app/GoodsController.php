<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-2
 * Time: 下午7:04
 */
class App_GoodsController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
    }

    public function doDefault()
    {
        var_dump("1111");
        die();
    }

    /**
     * 商品列表
     */
    public function doList() {

    }

    /**
     * 商品评价
     */
    public function doComment() {

    }

    /**
     * 添加商品评论
     */
    public function doAddComment() {
        $data = $this->_request->post();
        M('Goods_Comment')->insert(array_merge($data, $this->_request->getFiles()));
    }

}