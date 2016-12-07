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
    /**
     * 通用货币之间的转换
     */
    public function doConversionList() {
        $datas = M('Proportion')->select('id,left_id,right_id,l_digital,r_digital')->where('type=9')->fetchRows()->toArray();
        foreach($datas as &$data) {
            $left_name = M('Coltypes')->select('name')->where('id='.$data['left_id'])->fetchRow()->toArray();
            $right_name = M('Coltypes')->select('name')->where('id='.$data['right_id'])->fetchRow()->toArray();
            $data['left_name'] = $left_name['name'];
            $data['right_name'] = $right_name['name'];
            unset($data['left_id']);
            unset($data['right_id']);
        }
        echo $this->_encrypt_data($datas);
        //echo $this->show_data($this->_encrypt_data($datas));
        die();
    }
    /**
     * 转换
     */
    public function doConversion() {
        $id = $this->_request->id;
        $number = $this->_request->number;
        if( !$id || !$number ) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $data = M('Proportion')->getById((int)$id);
        if(!$data) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        $left_name = M('Coltypes')->select('name,english')->where('id='.$data['left_id'])->fetchRow()->toArray();
        $right_name = M('Coltypes')->select('name,english')->where('id='.$data['right_id'])->fetchRow()->toArray();

        if($number > $this->user[$left_name['english']]){
            echo  self::_error_data(API_INPUT_NUMBER_TOO_BIG,'输入的数字大于您所拥有的');
            die();
        }

        if($number % floor(($data['l_digital'])) != 0){
            echo  self::_error_data(API_MISSING_PARAMETER,'输入的数据不能整除');
            die();
        }
        $credit_coin = $number * ($data['r_digital']/$data['l_digital']);
        $user = $this->user;
        $desc = '以【'.$data['l_digital'].':'.$data['r_digital'].'】的比例进行【'.$left_name['name'].'转换成'.$right_name['name'].'】';
        $status = 2;

        if($right_name['english'] == 'worth_gold') {
            $extra = array(
                'uid' => $this->user->id,
                'privilege' => $credit_coin,
                'code' => $this->doRandStr(),
                'status' => 2,
            );
            M('Worthglod')->insert($extra);
            $user->worthGold($credit_coin,$desc,$extra['code'],$extra['status']);
        } else {
            $par = explode('_',$right_name['english']);
            $str = '';
            if(isset($par[1])) {
                $str = ucfirst($par[1]);
            }
            $par = $par[0].$str;
            $user->$par($credit_coin,$desc,$status,$left_name['english'].'-'.$right_name['english']);
        }
        if($left_name['english'] == 'worth_gold') {
            $extra = array(
                'uid' => $this->user->id,
                'privilege' => $credit_coin,
                'code' => $this->doRandStr(),
                'status' => 2,
            );
            M('Worthglod')->insert($extra);
            $user->worthGold($credit_coin,$desc,$extra['code'],$extra['status']);
        } else {
            $par = explode('_',$right_name['english']);
            $str = '';
            if(isset($par[1])) {
                $str = ucfirst($par[1]);
            }
            $par = $par[0].$str;
            $user->$par($number * -1,$desc,$status,$left_name['english'].'-'.$right_name['english']);
        }

        $data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }

    /**
     * @param int $length
     * @param string $chars
     * @return string
     * 兑换码
     */
    function doRandStr($length = 10, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890')
    {
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string))
        {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1}) $string .=  $r;
        }
        return $string;
    }
    /***
     * 抵佣金
     */
    public function doWorthGold() {
        $service_charge = M('Coltypes')->getById(15)->toArray();
        $proportions = M('Proportion')->select()->where('type=16')->fetchRows()->toArray();
        foreach($proportions as &$row) {
            $left_name = M('Coltypes')->select('name')->where('id='.$row['left_id'])->fetchRow()->toArray();
            $right_name = M('Coltypes')->select('name')->where('id='.$row['right_id'])->fetchRow()->toArray();
            $type_name = M('Coltypes')->select('name')->where('id='.$row['type'])->fetchRow()->toArray();
            $row['left_name'] = $left_name['name'];
            $row['right_name'] = $right_name['name'];
            $row['type_name'] = $type_name['name'];
            unset($row['left_id']);
            unset($row['right_id']);
            unset($row['type']);
            if($row['exts'])  continue;
        }
        $data['service'] = $service_charge['price'];
        $data['proportions'] = $proportions;
        //echo $this->_encrypt_data($data);
        echo $this->show_data($this->_encrypt_data($data));
        die();

    }
    /**
     * 购买抵佣金
     */
    public function doPayWorthGold() {

    }
}

