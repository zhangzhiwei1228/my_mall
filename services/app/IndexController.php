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
        //$this->user = $this->_auth();
    }

    public function doDefault()
    {
        $user = M('Region')->select('*')->fetchRows()->toArray();
        $encrypt_data = ($this->_encrypt_data($user));
        echo $this->_decrypt_data($encrypt_data);
        die();
    }
}