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
                break;
            case 'resale':
                if ($this->user['resale_grade'] == 4) {
                    $bonus = $this->user->getStaffBonus();
                } else {
                    $bonus = $this->user->getBonus('resale-1');
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

        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $dayBegin = $this->_request->start_time ? strtotime($this->_request->start_time) : mktime(0,0,0,$month,$day,$year);//当天开始时间戳
        $dayEnd = $this->_request->end_time ? strtotime($this->_request->end_time) : mktime(23,59,59,$month,$day,$year);//当天结束时间戳
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
}