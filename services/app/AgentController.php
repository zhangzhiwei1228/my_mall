<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-29
 * Time: 下午1:43
 */
class App_AgentController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
        $role = $this->user->role;
        $parent_id = $this->user->parent_id;
        $resale_grade = $this->user->resale_grade;
        var_dump($role);
        var_dump($parent_id);
        var_dump($resale_grade);
        die();

    }

    public function doDefault()
    {
        $user = M('User')->select('id,nickname,avatar,credit,credit_coin,vouchers,token')->where('id=' . (int)$this->user->id)->fetchRow()->toArray();
        $user['avatar'] = 'http://' . $_SERVER['HTTP_HOST'] . $user['avatar'];
        echo $this->_encrypt_data($user);
        //echo $this->show_data($this->_encrypt_data($user));
        die();
    }
}