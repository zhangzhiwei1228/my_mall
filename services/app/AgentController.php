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
    }

    public function doDefault()
    {
        switch ($this->user['role']) {
            case 'staff':
                $parent = M('User')->getById((int)$this->user['parent_id']);
                $bonus = $this->user->getBonus($parent['role']);
                $data['last_1_num'] = $bonus['last1']['num'];//本月发展的一级会员总数
                $data['last_2_num'] = $bonus['last2']['num'];//本月发展的二级会员总数
                $data['history_1_num'] = $bonus['history1']['num'];//历史发展的一级会员总数
                $data['history_2_num'] = $bonus['history2']['num'];//历史发展的二级会员总数
                $data['last_1_vip'] = $bonus['last1']['vip'];//本月激活的一级会员总数
                $data['last_2_vip'] = $bonus['last2']['vip'];//本月激活的二级会员总数
                $data['history_1_vip'] = $bonus['history1']['vip'];//历史激活的一级会员总数
                $data['history_2_vip'] = $bonus['history2']['vip'];//历史激活的二级会员总数
                $data['coin1'] = $bonus['coin1']['credit_coin']['total'] ? $bonus['coin1']['credit_coin']['total'] : 0;//我的一级会员本月消费积分币
                $data['coin2'] = $bonus['coin2']['credit_coin']['total'] ? $bonus['coin2']['credit_coin']['total'] : 0;//我的二级会员本月消费积分币
                $data['vouchers1'] = $bonus['vouchers1']['vouchers']['total'] ? $bonus['vouchers1']['vouchers']['total'] : 0;//我的一级会员商城消费使用抵用券
                $data['vouchers2'] = $bonus['vouchers2']['vouchers']['total'] ? $bonus['vouchers2']['vouchers']['total'] : 0;//我的二级会员商城消费使用抵用券
                $data['buy1'] = $bonus['buy1']['worth_gold']['total'] ? $bonus['buy1']['worth_gold']['total'] : 0;//我的一级会员抵用券购买抵用金
                $data['buy2'] = $bonus['buy2']['worth_gold']['total'] ? $bonus['buy2']['worth_gold']['total'] : 0;//我的二级会员抵用券购买抵用金
                $data['conversion1'] = $bonus['conversion1']['credit']['total'] ? $bonus['conversion1']['credit']['total'] : 0;//我的一级会员抵用券转换成帮帮币
                $data['conversion2'] = $bonus['conversion2']['credit']['total'] ? $bonus['conversion2']['credit']['total'] : 0;//我的二级会员抵用券转换成帮帮币
                if ($parent['role'] == 'agent' || $parent['role'] == 'resale') {
                    $data['coin3'] = $bonus['coin3']['credit_coin']['total'] ? $bonus['coin3']['credit_coin']['total'] : 0;//发展的商家的一级会员本月消费积分币
                    $data['coin4'] = $bonus['coin4']['credit_coin']['total'] ? $bonus['coin4']['credit_coin']['total'] : 0;//发展的商家的二级会员本月消费积分币
                    $data['buy3'] = $bonus['buy3']['worth_gold']['total'] ? $bonus['buy3']['worth_gold']['total'] : 0;//我的商家的一级会员抵用券购买抵用金
                    $data['buy4'] = $bonus['buy4']['worth_gold']['total'] ? $bonus['buy4']['worth_gold']['total'] : 0;//我的商家的二级会员抵用券购买抵用金
                    $data['conversion3'] = $bonus['conversion3']['credit']['total'] ? $bonus['conversion3']['credit']['total'] : 0;//我的商家的一级会员抵用券转换成帮帮币
                    $data['conversion4'] = $bonus['conversion4']['credit']['total'] ? $bonus['conversion4']['credit']['total'] : 0;//我的商家的二级会员抵用券转换成帮帮币
                    $data['vouchers3'] = $bonus['vouchers3']['vouchers']['total'] ? $bonus['vouchers3']['vouchers']['total'] : 0;//我的商家的一级会员商城消费使用抵用券
                    $data['vouchers4'] = $bonus['vouchers4']['vouchers']['total'] ? $bonus['vouchers4']['vouchers']['total'] : 0;//我的商家的二级会员商城消费使用抵用券
                    $data['seller'] = $bonus['seller']['credit']['total'] ? $bonus['seller']['credit']['total'] : 0;//发展的商家本月使用帮帮币
                    $data['seller_v'] = $bonus['seller_v']['vouchers']['total'] ? $bonus['seller_v']['vouchers']['total'] : 0;//我的商家本月赠送抵用券
                    $data['seller_w'] = $bonus['seller_w']['worth_gold']['total'] ? $bonus['seller_w']['worth_gold']['total'] : 0;//我的商家本月核销抵用金
                }
                $data['amount'] = $bonus['amount'];//我的二级会员抵用券转换成帮帮币
                $bonus = $data;
                break;
            case 'resale':
                if ($this->user['resale_grade'] == 4) {
                    $bonus = $this->user->getStaffBonus();
                    $data['seller'] = $bonus['area']['seller']['t_credit'] ? $bonus['area']['seller']['t_credit'] : 0;//我代理地区我下线的商家本月使用帮帮币
                    $data['member'] = $bonus['area']['member']['t_coin'] ? $bonus['area']['member']['t_coin'] : 0;//我代理地区我下线的会员本月消费积分币
                    $data['seller_v'] = $bonus['area']['seller_v']['t_credit'] ? $bonus['area']['seller_v']['t_credit'] : 0;//我代理地区我下线的商家本月使用抵用券
                    $data['seller_w'] = $bonus['area']['seller_w']['t_credit'] ? $bonus['area']['seller_w']['t_credit'] : 0;//我代理地区我下线的商家本月核销抵用金
                    $data['member_v'] = $bonus['area']['member_v']['t_coin'] ? $bonus['area']['member_v']['t_coin'] : 0;//我代理地区我下线的会员本月商城消费使用抵用券
                    $data['amount'] = $bonus['amount'];//我的本月收益
                    $bonus = $data;
                } else {

                    try{
                        $resale_apply = M('Resale_Apply')->select('id')->where('phone ='.$this->user['mobile'].' and grade >'.$this->user['resale_grade'])->fetchRows()->toArray();
                        $data['is_apply2'] = 0;
                        $data['is_apply3'] = 0;
                        $data['is_apply4'] = 0;
                        foreach($resale_apply as $k=>$v) {
                            $key = $k+$this->user['resale_grade']+1;
                            $data['is_apply'.$key] =  1 ;
                        }
                    }catch (Exception $e) {
                        var_dump($e);
                        die();
                    }


                    $bonus = $this->user->getBonus('resale-1');
                    $data['last_1_num'] = $bonus['last1']['num'];//本月发展的一级会员总数
                    $data['last_1_vip'] = $bonus['last1']['vip'];//本月激活的一级会员总数
                    $data['history_1_num'] = $bonus['history1']['num'];//历史发展的一级会员总数
                    $data['history_1_vip'] = $bonus['history1']['vip'];//历史激活的一级会员总数
                    $data['last_2_num'] = $bonus['last2']['num'];//本月发展的二级会员总数
                    $data['last_2_vip'] = $bonus['last2']['vip'];//本月激活的二级会员总数
                    $data['history_2_num'] = $bonus['history2']['num'];//历史发展的二级会员总数
                    $data['history_2_vip'] = $bonus['history2']['vip'];//历史激活的二级会员总数
                    $data['vouchers1'] = $bonus['vouchers1']['vouchers']['total'] ? $bonus['vouchers1']['vouchers']['total'] : 0;//我的一级会员商城消费使用抵用券
                    $data['vouchers2'] = $bonus['vouchers2']['vouchers']['total'] ? $bonus['vouchers2']['vouchers']['total'] : 0;//我的二级会员商城消费使用抵用券
                    $data['buy1'] = $bonus['buy1']['worth_gold']['total'] ? $bonus['buy1']['worth_gold']['total'] : 0;//我的一级会员抵用券购买抵用金
                    $data['buy2'] = $bonus['buy2']['worth_gold']['total'] ? $bonus['buy2']['worth_gold']['total'] : 0;//我的二级会员抵用券购买抵用金
                    $data['conversion1'] = $bonus['conversion1']['credit']['total'] ? $bonus['conversion1']['credit']['total'] : 0;//我的一级会员抵用券转换成帮帮币
                    $data['conversion2'] = $bonus['conversion2']['credit']['total'] ? $bonus['conversion2']['credit']['total'] : 0;//我的二级会员抵用券转换成帮帮币
                    $data['coin1'] = $bonus['coin1']['credit_coin']['total'] ? $bonus['coin1']['credit_coin']['total'] : 0;//我的一级会员本月消费积分币
                    $data['coin2'] = $bonus['coin2']['credit_coin']['total'] ? $bonus['coin2']['credit_coin']['total'] : 0;//我的二级会员本月消费积分币
                    $data['coin3'] = $bonus['coin3']['credit_coin']['total'] ? $bonus['coin3']['credit_coin']['total'] : 0;//发展的商家的一级会员本月消费积分币
                    $data['coin4'] = $bonus['coin4']['credit_coin']['total'] ? $bonus['coin4']['credit_coin']['total'] : 0;//发展的商家的二级会员本月消费积分币
                    $data['vouchers3'] = $bonus['vouchers3']['vouchers']['total'] ? $bonus['vouchers3']['vouchers']['total'] : 0;//我的商家的一级会员商城消费使用抵用券
                    $data['vouchers4'] = $bonus['vouchers4']['vouchers']['total'] ? $bonus['vouchers4']['vouchers']['total'] : 0;//我的商家的二级会员商城消费使用抵用券
                    $data['buy3'] = $bonus['buy3']['worth_gold']['total'] ? $bonus['buy3']['worth_gold']['total'] : 0;//我的商家的一级会员抵用券购买抵用金
                    $data['buy4'] = $bonus['buy4']['worth_gold']['total'] ? $bonus['buy4']['worth_gold']['total'] : 0;//我的商家的二级会员抵用券购买抵用金
                    $data['conversion3'] = $bonus['conversion3']['credit']['total'] ? $bonus['conversion3']['credit']['total'] : 0;//我的商家的一级会员抵用券转换成帮帮币
                    $data['conversion4'] = $bonus['conversion4']['credit']['total'] ? $bonus['conversion4']['credit']['total'] : 0;//我的商家的二级会员抵用券转换成帮帮币
                    $data['seller'] = $bonus['seller']['credit']['total'] ? $bonus['seller']['credit']['total'] : 0;//发展的商家本月使用帮帮币
                    $data['seller_v'] = $bonus['seller_v']['vouchers']['total'] ? $bonus['seller_v']['vouchers']['total'] : 0;//我的商家本月赠送抵用券
                    $data['seller_w'] = $bonus['seller_w']['worth_gold']['total'] ? $bonus['seller_w']['worth_gold']['total'] : 0;//我的商家本月核销抵用金
                    $data['amount'] = $bonus['amount'];
                    $bonus = $data;
                }
                break;
            case 'seller':
                $bonus = $this->user->getStaffBonus();
                $uid = $this->user->id;
                $year = date("Y");
                $month = date("m");
                $day = date("d");
                $dayBegin = mktime(0,0,0,$month,$day,$year);//当天开始时间戳
                $dayEnd = mktime(23,59,59,$month,$day,$year);//当天结束时间戳

                $BeginDate=strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
                $start_month = date('Y-m-01', strtotime(date("Y-m-d")));
                $EndDate =  strtotime(date('Y-m-d', strtotime("$start_month +1 month -1 day")));

                //商家充值赠送的免费积分
                $employ = M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit<0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
                    ->fetchRow()->toArray();
                $Memploy = M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit<0 and create_time >'.$BeginDate.' and create_time <'.$EndDate)
                    ->fetchRow()->toArray();
                $recharge =  M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'credit'."'".' and credit>0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
                    ->fetchRow()->toArray();

                //商家充值赠送的抵用券
                $Demploy = M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit<0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
                    ->fetchRow()->toArray();
                $MemployV = M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit<0 and create_time >'.$BeginDate.' and create_time <'.$EndDate)
                    ->fetchRow()->toArray();
                $rechargeV =  M('User_Credit')->select('sum(credit) as total')
                    ->where('user_id = '.(int)$uid." and type='".'vouchers'."'".' and credit>0 and create_time >'.$dayBegin.' and create_time <'.$dayEnd)
                    ->fetchRow()->toArray();
                //amount 我的本月收益
                $bonus['coin1'] = $bonus['coin1']['credit_coin']['total'];//我员工发展的一级会员消费积分币
                $bonus['coin2'] = $bonus['coin2']['credit_coin']['total'];//我员工发展的二级会员消费积分币
                $bonus['vouchers1'] = $bonus['vouchers1']['vouchers']['total'];//我员工的一级会员商城消费使用抵用券
                $bonus['vouchers2'] = $bonus['vouchers2']['vouchers']['total'];//我员工的二级会员商城消费使用抵用券
                $bonus['buy1'] = $bonus['buy1']['worth_gold']['total'];//我的员工的一级会员抵用券购买抵用金
                $bonus['buy2'] = $bonus['buy2']['worth_gold']['total'];//我的员工的二级会员抵用券购买抵用金
                $bonus['conversion1'] = $bonus['conversion1']['credit']['total'];//我的员工的一级会员抵用券转换成帮帮币
                $bonus['conversion2'] = $bonus['conversion2']['credit']['total'];//我的员工的一级会员抵用券转换成帮帮币
                $bonus['rechargeV'] = $rechargeV['total'] ? $rechargeV['total'] : 0;//商家本日充值抵用券
                $bonus['MemployV']  = $MemployV['total'] ? $MemployV['total'] : 0;//商家本月赠送抵用券数
                $bonus['Demploy']   = $Demploy['total'] ? $Demploy['total'] : 0;//商家本日赠送抵用券
                $bonus['recharge']  = $recharge['total'] ? $recharge['total'] : 0;//商家本日充值帮帮币
                $bonus['Memploy']   = $Memploy['total'] ? $Memploy['total'] : 0;//商家本月赠送帮帮币数
                $bonus['employ']    = $employ['total'] ? $employ['total'] : 0;//商家本日赠送帮帮币
                break;
            case 'agent':
                $bonus = $this->user->agentearnings();
                $bonus['seller'] = $bonus['seller']['credit']['total'] ? $bonus['seller']['credit']['total'] : 0;//我代理地区商家本月使用帮帮币
                $bonus['userarea'] = $bonus['userarea']['credit_coin']['total'] ? $bonus['userarea']['credit_coin']['total'] : 0;//我代理地区会员本月消费积分币
                $bonus['seller_v'] = $bonus['seller_v']['vouchers']['total'] ? $bonus['seller_v']['vouchers']['total'] : 0;//我代理地区商家本月使用抵用券
                $bonus['seller_w'] = $bonus['seller_w']['worth_gold']['total'] ? $bonus['seller_w']['worth_gold']['total'] : 0;//我代理地区商家本月核销抵用金
                $bonus['userarea_v'] = $bonus['userarea_v']['vouchers']['total'] ? $bonus['userarea_v']['vouchers']['total'] : 0;//我代理地区会员本月商城消费使用抵用券
                break;
        }
        echo $this->_encrypt_data($bonus);
        //echo $this->show_data($this->_encrypt_data($bonus));
        die();
    }
    /**
     * 员工管理
     */
    public function doStaff() {
        $page = $this->_request->page ? $this->_request->page : 1;
        $limit = $this->_request->limit ? $this->_request->limit : 10;
        $data = M('User')->select('id,username')
            ->where('parent_id = ?', (int)$this->user->id)
            ->order('id DESC')
            ->paginator($limit, $page)
            ->fetchRows();
        foreach($data as $key =>$row) {
            $amount = $row->getBonus();
            $row->__set('amount',$amount['amount']);
            $data->set($key,$row->toArray());
        }
        $data = $data->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 我的员工详细信息
     */
    public function doStaffInfo() {
        $id = $this->_request->id;//员工id
        if(!$id) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $uinfo = M('User')->getById((int)$id);
        if(!$uinfo) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }

        $bonus = $uinfo->getBonus();
        $data['id'] = $uinfo->id;
        $data['username'] = $uinfo->username;
        $data['last_1_num'] = $bonus['last1']['num'];//本月发展的一级会员总数
        $data['last_2_num'] = $bonus['last2']['num'];//本月发展的二级会员总数
        $data['history_1_num'] = $bonus['history1']['num'];//历史发展的一级会员总数
        $data['history_2_num'] = $bonus['history2']['num'];//历史发展的二级会员总数
        $data['last_1_vip'] = $bonus['last1']['vip'];//本月激活的一级会员总数
        $data['last_2_vip'] = $bonus['last2']['vip'];//本月激活的二级会员总数
        $data['history_1_vip'] = $bonus['history1']['vip'];//历史激活的一级会员总数
        $data['history_2_vip'] = $bonus['history2']['vip'];//历史激活的二级会员总数
        $data['coin1'] = $bonus['coin1']['credit_coin']['total'] ? $bonus['coin1']['credit_coin']['total'] : 0;//我员工发展的一级会员消费积分币
        $data['coin2'] = $bonus['coin2']['credit_coin']['total'] ? $bonus['coin2']['credit_coin']['total'] : 0;//我员工发展的二级会员消费积分币
        $parent = M('User')->getById((int)$uinfo['parent_id']);
        if($parent['role'] != 'seller' ) {
            $data['coin3'] = $bonus['coin3']['credit_coin']['total'] ? $bonus['coin3']['credit_coin']['total'] : 0;//发展的商家的一级会员本月消费积分币
            $data['coin4'] = $bonus['coin4']['credit_coin']['total'] ? $bonus['coin4']['credit_coin']['total'] : 0;//发展的商家的二级会员本月消费积分币
            $data['seller'] = $bonus['seller']['credit']['total'] ? $bonus['seller']['credit']['total'] : 0;//发展的商家本月使用帮帮币
        }
        $data['amount'] = $bonus['amount'];
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商家帮帮币，抵用券充值和赠送列表
     */
    public function doCurrencyLogs() {
        $currency = $this->_request->currency;//credit 帮帮币 vouchers 抵用券
        $type = $this->_request->type; //employ 赠送 recharge 充值
        $limit = $this->_request->limit ? $this->_request->limit : 20;
        $page = $this->_request->page ? $this->_request->page : 1;
        $time_type = $this->_request->time_type ? $this->_request->time_type : 1;//1今天2一个星期3一个月
        if( !$currency || !$type ) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        switch($time_type) {
            case 1:
                $dayBegin = mktime(0,0,0,$month,$day,$year);//当天开始时间戳
                $dayEnd = mktime(23,59,59,$month,$day,$year);//当天结束时间戳
                break;
            case 2:
                //近七天的数据
                $dayEnd = mktime(0,0,0,$month,$day,$year);
                $dayBegin = strtotime("-1 week");
                break;
            case 3:
                //近一个月的数据
                $dayEnd = mktime(0,0,0,$month,$day,$year);
                $dayBegin = strtotime("-1 month");
                break;
        }

        $where = 'user_id = '.(int)$this->user->id." and type='".$currency."'".' and create_time >'.$dayBegin.' and create_time <'.$dayEnd;
        if($type == 'recharge') {
            $where .= ' and credit > 0 ';
        } else {
            $where .= ' and credit < 0 ';
        }
        $data = M('User_Credit')->select('id,create_time,credit,note,rid')
            ->where($where)
            ->order('id DESC')
            ->paginator($limit,$page)
            ->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 商家赠送（credit 帮帮币 vouchers 抵用券）
     */
    public function doEmploy() {
        $type = $this->_request->type;//credit 帮帮币 vouchers 抵用券
        $uid = $this->_request->uid;//赠送用户id
        $num = $this->_request->num;//赠送的数量
        if( !$type || !$uid  || !$num) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $account = M('User')->getById((int)$uid);
        if (!$account->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'此账户不存在');
            die();
        }
        if (!$account['is_enabled']) {
            echo  self::_error_data(API_USER_DISABLE,'此账户已被禁用');
            die();
        }
        if (!$this->_checkCredit($num, $this->user[$type])) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'您所拥有的不够赠送，请先充值');
            die();
        }
        $shop_id = $this->user->shop_id;
        $shop = M('Shop')->select('name')->where('id = '.$shop_id)->fetchRow()->toArray();

        $this->user->$type($num * -1, '赠送会员【'.$account['nickname'].'】', 3, $shop['name'], $account['id']);
        $account->$type($num, '商家赠送【'.$this->user['nickname'].'】', 3 , $shop['name'], $this->user->id);
        $data = M('User')->select('credit,credit_happy,credit_coin,worth_gold,vouchers')->where('id ='.(int)$this->user->id)->fetchRow()->toArray();
        //$data = array('status'=>'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 查询赠送的用户
     */
    public function doQueryUser()
    {
        $q = $this->_request->q;
        if( !$q) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $limit = $this->_request->limit ? $this->_request->limit : 20;
        $page = $this->_request->page ? $this->_request->page : 1;
        $data =  M('User')->select('id, username, nickname, credit, vouchers,credit_happy, credit_coin, balance, mobile')
            ->where('username = ? OR email = ? OR mobile = ?', $q)
            ->paginator($limit,$page)
            ->fetchRow()
            ->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    protected function _checkCredit($c1, $c2)
    {
        if ($c1 > $c2) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 查找要核销兑换码
     */
    public function doQueryGold()
    {
        $code = $this->_request->code;
        if( !$code) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $data =  M('Worthglod')->alias('wg')
            ->leftJoin(M('User')->getTableName().' AS u', 'wg.uid = u.id')
            ->columns('wg.id,wg.privilege,wg.code,wg.uid,wg.write,u.username,u.mobile')
            ->where('wg.code = ? and wg.status = 2', $code)
            ->fetchRow()
            ->toArray();
        if (!$data) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'查询数据不存在，或此兑换码未支付');
            die();
        }
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 核销
     */
    public function doCheckout() {
        $gid = $this->_request->gid;
        if( !$gid) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $glod = M('Worthglod')->getById((int)$gid);
        if (!$glod->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'此兑换码不存在');
            die();
        }
        $account = M('User')->getById((int)$glod['uid']);
        if (!$account->exists()) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'所属账户不存在');
            die();
        }
        $worthglod = M('User_Credit')->select()->where('user_id='.$account['id'].' and code='."'".$glod['code']."'")->fetchRow()->toArray();
        if($worthglod) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'此账户已经核销过，请不要重复核销');
            die();
        }

        if($account['worth_gold'] < $glod['privilege']) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'该帐户抵用金不足');
            die();
        }
        $glod->write = 2;
        $glod->write_uid = $this->user->id;
        $glod->write_time = time();
        $glod->save();
        $this->user->worthGold($glod['privilege'],'核销用户【'.$account['username'].'-'.$account['id'].'】【'.$glod['privilege'].'抵用金】', $glod['code']);
        $account->worthGold($glod['privilege'] * -1,'被用户【'.$this->user['username'].'-'.$this->user['id'].'】核销【'.$glod['privilege'].'抵用金】', $glod['code']);
        //$data = array('status'=>'ok');
        $data = M('User')->select('credit,credit_happy,credit_coin,worth_gold,vouchers')->where('id ='.(int)$this->user->id)->fetchRow()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 核销记录
     */
    public function doCheckLogs() {
        $limit = $this->_request->limit ? $this->_request->limit : 20;
        $page = $this->_request->page ? $this->_request->page : 1;
        $datas = M('User_Credit')->alias('uc')
            ->where('uc.user_id = '.(int)$this->user->id.' and uc.type='."'".'worth_gold'."'".' and uc.code !='."''")
            ->leftJoin(M('Worthglod')->getTableName().' AS wg', 'wg.code = uc.code')
            ->leftJoin(M('User')->getTableName().' AS u', 'u.id = wg.uid')
            ->columns('wg.privilege,u.username,uc.create_time')
            ->order('uc.create_time DESC')
            //->paginator($limit, $page)
            ->fetchRows();
        //$data = $datas->toArray();
        $earnings = $this->user->countGold();
        $data['data'] = $datas->toArray();
        $data['total'] = $earnings['total'] ? $earnings['total'] : 0;//本月核销
        $data['earnings'] = $earnings['earnings'];//可得收益
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();

    }
}