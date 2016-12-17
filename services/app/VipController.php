<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-17
 * Time: 上午9:25
 */
class App_VipController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }
    public function doDesc() {
        $
        $page = M('Page')->getByCode('upgrade-vip'.$this->_request->t);
        echo $page['content'];
    }
}