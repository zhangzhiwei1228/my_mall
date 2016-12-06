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
        //'credit','credit_happy','credit_coin','worth_gold','cash','vouchers
        // 帮帮币      快乐积分        积分币          抵佣金             抵用券
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        $type = $this->_request->type;
        $select = M('User_Credit')->select()
            ->where('user_id = ?', $this->user['id'])
            ->order('id DESC')
            ->paginator(20, $this->_request->page);

        if ($type ) {
            if( $this->_request->t != 'worth_gold' ) {
                $select->where('type = ?', $this->_request->t);
            } else {
                $select = M('Worthglod')->select()
                    ->where('uid = ?', $this->user['id'])
                    ->order('create_time DESC')
                    ->paginator(20, $this->_request->page);
            }
        }
        $datalist = $select->fetchRows();
    }
}

