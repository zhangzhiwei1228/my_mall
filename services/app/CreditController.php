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
        if(!$limit || !$page || !$type) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $select = M('User_Credit')->select()
            ->where('user_id = '. $this->user['id'].' and type ='."'".$type."'".' and credit > 0')
            ->order('id DESC')
            ->paginator($limit, $page);
        if( $type == 'worth_gold' ) {
            $select = M('Worthglod')->select()
                ->where('uid = ?', $this->user['id'])
                ->order('create_time DESC')
                ->paginator($limit, $page);
        }
        $data = array();
        $datalist = $select->fetchRows()->toArray();
        foreach($datalist as $key => $row) {
            switch($type) {
                case 'credit' :
                    $account = M('User')->getById((int)$row['rid']);
                    $data[$key]['create_time'] = $row['create_time'];
                    $data[$key]['type'] = $row['type'];
                    $data[$key]['shop_name'] = $row['conversion'];
                    $data[$key]['amount'] = $row['credit'];
                    $data[$key]['source'] = $row['status'] == 3 ? '商家赠送' : ($row['status'] == 1 ? '购买' : '');
                    $data[$key]['shop_id'] = $row['status'] == 3 ? $account->shop_id : '';
                    break;
                case 'credit_coin' :
                    $data[$key]['create_time'] = $row['create_time'];
                    $data[$key]['type'] = $row['type'];
                    $data[$key]['amount'] = $row['credit'];
                    $data[$key]['source'] = $row['status'] == 3 ? '商家赠送' : ($row['status'] == 1 ? '购买' : '转换');
                    break;
                case 'vouchers' :
                    $account = M('User')->getById((int)$row['rid']);
                    $data[$key]['create_time'] = $row['create_time'];
                    $data[$key]['type'] = $row['type'];
                    $data[$key]['shop_name'] = $row['conversion'];
                    $data[$key]['amount'] = $row['credit'];
                    $data[$key]['source'] = $row['status'] == 3 ? '商家赠送' : ($row['status'] == 2 ? '转换' : '购买');
                    $data[$key]['shop_id'] = $row['status'] == 3 ? $account->shop_id : '';
                    break;
                case 'worth_gold' :
                    $account = M('User')->getById((int)$row['write_uid']);
                    $shop = $account->shop_id ? M('Shop')->select('name')->where('id = '.$account->shop_id)->fetchRow()->toArray() : '';
                    $data[$key]['create_time'] = $row['create_time'];
                    $data[$key]['amount'] = $row['privilege'];
                    $data[$key]['source'] = $row['pay_type'] == 1 ? '购买' : '转换';
                    $data[$key]['code'] = $row['code'];
                    $data[$key]['status'] = $row['write'] == 1 ? '未核销' : '已核销';
                    $data[$key]['shop_name'] = $shop ? $shop['name'] : '';
                    $data[$key]['shop_id'] =  $account->shop_id ;
                    break;
            }
        }
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
}

