<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-21
 * Time: 下午2:59
 */
class App_ShopController extends App_Controller_Action
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
     *商家列表
     */
    public function doList() {
        var_dump("list");
        die();
    }

    /**
     * 商家评价
     */
    public function doComment() {

    }

    /**
     * 添加商家评论
     */
    public function doAddComment() {
        $data = $this->_request->post();
        M('Shop_Comment')->insert(array_merge($data, $this->_request->getFiles()));
    }
}