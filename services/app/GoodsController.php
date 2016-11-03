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
    public function doList() {
        var_dump("list");
        die();
    }
}