<?php

/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-10-11
 * Time: 下午2:11
 */
class Worthglod extends Abstract_Model {
    protected $_name = 'worthglod';
    protected $_primary = 'id';
    protected $_referenceMap = array(
        'buyer' => array(
            'class' => 'User',
            'type' => 'hasone',
            'source' => 'uid',
            'target' => 'id'
        )
    );
    public function getByOrderNo($code)
    {
        return $this->select()
            ->where('order_no = ?', $code)
            ->fetchRow();
    }
    //会员混合支付抵佣金
    public function payHybrid($glod) {
        $this->getAdapter()->beginTrans();
        try {
            $glod->status = 2;
            $glod->pay_time = time();
            $glod->save();
            $exts = json_decode($glod['pay_json']);
            //扣免费积分
            if ($exts['exts_type'] == 'credit') {
                $glod->buyer->credit($exts['payment']*-1, '消耗'.$exts['payment'].'点免费积分，支付【'.$exts['pay_desc'].'】');
            }
            //扣快乐积分
            if ($exts['exts_type'] == 'credit_happy') {
                $glod->buyer->creditHappy($exts['payment']*-1, '消耗'.$exts['payment'].'点快乐积分，支付【'.$exts['pay_desc'].'】');
            }
            //扣积分币
            if ($exts['exts_type'] == 'credit_coin') {
                $glod->buyer->creditCoin($exts['payment']*-1, '消耗'.$exts['payment'].'点积分币，支付【'.$exts['pay_desc'].'】');
            }
            //扣抵用券
            if ($exts['exts_type'] == 'vouchers') {
                $glod->buyer->vouchers($exts['payment']*-1, '消耗'.$exts['payment'].'点抵用券，支付【'.$exts['pay_desc'].'】');
            }
            $desc = explode('=',$exts['pay_desc']);
            $glod->buyer->worthGold($glod['privilege'],'使用【'.$desc[0].'】购买【'.$desc[1].'】');
            $this->getAdapter()->commit();
        } catch (App_Exception $e) {
            $logFile = 'order'.'_'.date('Ymd').'.log';
            Suco_File::write(LOG_DIR.$logFile, $e, 'a+');
            echo $e->dump();
            $this->getAdapter()->rollback();
        }
    }
    //会员用现金支付抵佣金
    public function payCash($glod) {
        $this->getAdapter()->beginTrans();
        try {
            $glod->status = 2;
            $glod->pay_time = time();
            $glod->save();
            $exts = json_decode($glod['pay_json']);
            $desc = explode('=',$exts['pay_desc']);
            $glod->buyer->worthGold($glod['privilege'],'使用【'.$desc[0].'】购买【'.$desc[1].'】');
            $pay_amount = $glod['service_charge']+$exts['payment'];
            $glod->buyer->cash($pay_amount,'使用【'.$pay_amount.'】现金购买【'.$glod['privilege'].'抵用金');
            $this->getAdapter()->commit();
        } catch (App_Exception $e) {
            $logFile = 'order'.'_'.date('Ymd').'.log';
            Suco_File::write(LOG_DIR.$logFile, $e, 'a+');
            echo $e->dump();
            $this->getAdapter()->rollback();
        }
    }
    //会员用非混合支付抵佣金
    public function paySingle($glod) {
        $this->getAdapter()->beginTrans();
        try {
            $glod->status = 2;
            $glod->pay_time = time();
            $glod->save();
            $exts = json_decode($glod['pay_json']);
            //扣免费积分
            if ($exts['exts_type'] == 'credit') {
                $glod->buyer->credit($exts['payment']*-1, '消耗'.$exts['payment'].'点免费积分，支付【'.$glod['privilege'].'抵用金】');
            }
            //扣快乐积分
            if ($exts['exts_type'] == 'credit_happy') {
                $glod->buyer->creditHappy($exts['payment']*-1, '消耗'.$exts['payment'].'点快乐积分，支付【'.$glod['privilege'].'抵用金】');
            }
            //扣积分币
            if ($exts['exts_type'] == 'credit_coin') {
                $glod->buyer->creditCoin($exts['payment']*-1, '消耗'.$exts['payment'].'点积分币，支付【'.$glod['privilege'].'抵用金】');
            }
            //扣抵用券
            if ($exts['exts_type'] == 'vouchers') {
                $glod->buyer->vouchers($exts['payment']*-1, '消耗'.$exts['payment'].'点抵用券，支付【'.$glod['privilege'].'抵用金】');
            }
            $desc = explode('=',$exts['pay_desc']);
            $glod->buyer->worthGold($glod['privilege'],'使用【'.$exts['payment'].$exts['pay_name'].'】购买【'.$glod['privilege'].'抵用金】');
            $this->getAdapter()->commit();
        } catch (App_Exception $e) {
            $logFile = 'order'.'_'.date('Ymd').'.log';
            Suco_File::write(LOG_DIR.$logFile, $e, 'a+');
            echo $e->dump();
            $this->getAdapter()->rollback();
        }
    }
}