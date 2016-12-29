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
}