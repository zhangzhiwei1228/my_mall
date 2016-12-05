<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-5
 * Time: 下午8:13
 */
class App_CreditController extends App_Controller_Action
{
    public function init(){
        parent::init();
        $this->user = $this->_auth();
    }
    public function doList(){

    }
}

