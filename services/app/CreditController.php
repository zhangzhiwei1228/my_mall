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
            $par = explode('_',$left_name['english']);
            $str = '';
            if(isset($par[1])) {
                $str = ucfirst($par[1]);
            }
            $par = $par[0].$str;
            $user->$par($number * -1,$desc,$status,$left_name['english'].'-'.$right_name['english']);
        }

        $data = M('User')->select('credit,credit_happy,credit_coin,worth_gold,vouchers')->where('id = '.(int)$this->user->id)->fetchRow()->toArray();
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
        $proportions = M('Proportion')->select('id,l_digital,r_digital,left_id,right_id')->where('type=16')->fetchRows()->toArray();
        foreach($proportions as &$row) {
            $left_name = M('Coltypes')->select('name')->where('id='.$row['left_id'])->fetchRow()->toArray();
            $right_name = M('Coltypes')->select('name')->where('id='.$row['right_id'])->fetchRow()->toArray();
            //$type_name = M('Coltypes')->select('name')->where('id='.$row['type'])->fetchRow()->toArray();
            $row['left_name'] = $left_name['name'];
            $row['right_name'] = $right_name['name'];
            //$row['type_name'] = $type_name['name'];
            unset($row['left_id']);
            unset($row['right_id']);
            unset($row['type']);
            if($row['exts'])  continue;
        }
        $data['service'] = $service_charge['price'];
        $data['proportions'] = $proportions;
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();

    }
    /**
     * 购买抵佣金
     */
    public function doPayWorthGold() {
        $flag = false;
        $service_charge = M('Coltypes')->getById(15)->toArray();
        $price_type = $this->_request->price_type;//选择的支付方式
        $consume = $this->_request->consume; //消费金额
        $discount = $this->_request->discount;//折扣
        $privilege = $this->_request->privilege;//优惠
        $service = $this->_request->service;//服务费

        $discount = $discount/100;//折扣换成百分比
        $privilege = $privilege ? $privilege : round(($consume - $consume*$discount),2);//优惠
        $service = $service ? $service : round(($privilege * $service_charge['price']),2);//服务费
        $pro18 = M('Proportion')->getById(18)->toArray();
        if($price_type == 100 || $price_type == 101 || $price_type == 102) {
            $money = ceil(($consume - $consume*$discount)*($pro18['l_digital']/$pro18['r_digital'])*0.5);//支付的货币金额
            $cash = $money + $service;
        }

        if($price_type == 100) {
            $price_type = 15;$flag=true;
        } elseif($price_type == 101) {
            $price_type = 16;$flag=true;
        } elseif($price_type == 102) {
            $price_type = 17;$flag=true;
        }
        $proportion = M('Proportion')->select()->where('id='.(int)$price_type)->fetchRow()->toArray();
        $payment = ceil(($consume - $consume*$discount)*($proportion['l_digital']/$proportion['r_digital']));//支付的货币金额
        $pay_name = M('Coltypes')->select('name,english')->where('id='.$proportion['left_id'])->fetchRow()->toArray();
        $right = M('Coltypes')->select('name')->where('id='.$proportion['right_id'])->fetchRow()->toArray();
        if(!$privilege || !$payment) {
            throw new App_Exception('计算错误，请重新计算提交');
        }
        if($flag){
            $payment = ceil($payment/2);
        }
        if(!$flag && $price_type !=18) {
            $cash = $service;
        }
        if($price_type == 18) {
            $cash = $service + $payment;
        }

        if ($pay_name['english'] == 'credit' && $this->user['credit'] < $payment) {
            echo  self::_error_data(API_USER_CREDIT_NO_ENOUGH,'支付失败，您的帮帮币不足');
            die();
        }
        if ($pay_name['english'] == 'credit_happy' && $this->user['credit_happy'] < $payment) {
            echo  self::_error_data(API_USER_CREDIT_HAPPY_NO_ENOUGH,'支付失败，您的快乐积分不足');
            die();
        }
        if ($pay_name['english'] == 'credit_coin' && $this->user['credit_coin'] < $payment) {
            echo  self::_error_data(API_USER_CREDIT_COIN_NO_ENOUGH,'支付失败，您的积分币不足');
            die();
        }
        if ($pay_name['english'] == 'vouchers' && $this->user['vouchers'] < $payment) {
            echo  self::_error_data(API_USER_VOUCHERS_NO_ENOUGH,'支付失败，您的抵用券不足');
            die();
        }
        $extra['consume'] = $consume;
        $extra['discount'] = $discount;
        $extra['price_type'] = $price_type;
        $extra['uid'] = $this->user->id;
        $extra['privilege'] = $privilege;
        $extra['service_charge'] = $service;
        $extra['discount'] = $discount;
        $extra['order_no'] = $this->doOrderNo();
        $extra['code'] = $this->doRandStr();
        $pay_json['payment'] = $payment;
        $pay_json['exts_type'] = $flag ? $pay_name['english'] : $pay_name['english'];
        $pay_json['exts_amount'] = $flag ? $money : '';
        $pay_json['pay_name'] = $flag ? $pay_name['name'].'+元' :$pay_name['name'];
        $pay_json['pay_desc'] = $flag ? $payment.$pay_name['name'].'+'.$money.'元'.'='.$privilege.$right['name'] :$proportion['l_digital'].$pay_name['name'].'='.$proportion['r_digital'].$right['name'];
        $extra['pay_json'] = json_encode($pay_json);

        $glod_id = M('Worthglod')->insert($extra);
        $pay_data['type'] = $flag ? 'hybrid' : $pay_name['english'];
        $pay_data['exts_type'] = $pay_name['english'];
        $pay_data['amount'] = $payment;
        $pay_data['pay_amount'] = $cash;
        $pay_data['flag'] = $flag;
        $pay_data['money'] = $money;
        $pay_data['return_url'] = '/usercp/money/success/?id='.$glod_id;
        $pay_data['glod_id'] = $glod_id;
        $pay_data['pay_name'] = $pay_name['name'];
        $pay_data['privilege'] = $privilege;
        $this->doPayPurchase($pay_data);
    }
    public function doPayPurchase($data) {

        $data['desc'] = $data['flag'] ? '使用【'.$data['amount'].$data['pay_name'].'+'.$data['money'].'元'.'】购买【'.$data['privilege'].'抵用金】' :'使用【'.$data['amount'].$data['pay_name'].'】购买【'.$data['privilege'].'抵用金】';
        $worthglod = M('Worthglod')->getById((int)$data['glod_id']);
        $status = 1;
        if(!$data['pay_amount']) {
            switch ($data['type']) {
                case 'credit':
                    $this->user->credit($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit-worth_gold');
                    break;
                case 'credit_happy':
                    $this->user->creditHappy($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit_happy-worth_gold');
                    break;
                case 'credit_coin':
                    $this->user->creditCoin($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit_coin-worth_gold');
                    break;
                case 'vouchers'://抵用券
                    $this->user->vouchers($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'vouchers-worth_gold');
                    break;
                case 'hybrid'://混合支付
                    switch($data['exts_type']) {
                        case 'credit':
                            $this->user->credit($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit-worth_gold');
                            break;
                        case 'credit_happy':
                            $this->user->creditHappy($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit_happy-worth_gold');
                            break;
                        case 'credit_coin':
                            $this->user->creditCoin($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'credit_coin-worth_gold');
                            break;
                        case 'vouchers'://抵用券
                            $this->user->vouchers($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】', $status,'vouchers-worth_gold');
                            break;
                    }
                    break;
            }
        } else {
            switch ($data['type']) {
                case 'credit':
                    /*$this->user->credit($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
                    break;*/
                case 'credit_happy':
                    /*$this->user->creditHappy($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
                    break;*/
                case 'credit_coin':
                    /*$this->user->creditCoin($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
                    break;*/
                case 'vouchers'://抵用券
                    /*$this->user->vouchers($data['amount']*-1, '购买抵用金【GL-'.$data['glod_id'].'】');
                    break;*/
                    $this->doPaySingle($data);
                    die();
                case 'cash'://现金
                    /*$this->user->cash($data['amount']*-1, '购买抵用金【TS-'.$data['glod_id'].'】');
                    break;*/
                case 'hybrid'://混合支付
                    $this->doHybrid($data);
                    die();
                    break;
            }
        }

        $worthglod->status = 2;
        $worthglod->pay_time = time();
        $worthglod->save();
        $this->user->worthGold($data['privilege'],$data['desc'],'',$status,$data['type'].'-worth_gold');

        $glod = M('Worthglod')->select('id,code,order_no')->where('id ='.$data['glod_id'])->fetchRow()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($glod));
        die();
    }
    // 生成订单号 $str 前缀
    function doOrderNo($str=''){
        $order_no=date('Ymdhis',time()).str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        return $str.$order_no;
    }
}

