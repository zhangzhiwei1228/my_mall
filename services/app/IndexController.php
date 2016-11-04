<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-2
 * Time: 下午6:10
 */
class App_IndexController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }

    public function doDefault()
    {
        $user = M('User')->select('id,token,token_expire_time')->where('id='.(int)$this->user->id)->fetchRow()->toArray();
        $encrypt_data = ($this->_encrypt_data($user));
        echo $encrypt_data;
        die();
    }
}