<?php

/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-9
 * Time: 下午6:55
 */
class App_UserController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }
    public function doDefault() {
        $user = M('User')->select('id,nickname,avatar,credit,credit_coin,vouchers,token')->where('id='.(int)$this->user->id)->fetchRow()->toArray();
        $user['avatar'] = 'http://'.$_SERVER['HTTP_HOST'].$user['avatar'];
        echo $this->_encrypt_data($user);
        //echo $this->show_data($this->_encrypt_data($user));
        die();
    }
}