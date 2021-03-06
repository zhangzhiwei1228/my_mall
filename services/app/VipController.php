<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-17
 * Time: 上午9:25
 */
class App_VipController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->user = $this->_auth();
    }
    public function doDesc() {
        /**
         * 申请一星分销商 ---  四星分销商  ：1～4
         * 申请代理商  6
         * 申请商家入驻是  5
         */
        $type = $this->_request->type;
        if( !$type ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $page = M('Page')->getByCode('upgrade-vip'.$type);
        if(!$page) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        echo $this->_encrypt_data($page['content']);
        //echo $this->show_data($this->_encrypt_data($page['content']));
        die();
    }
    public function doApply() {
        $type = $this->_request->type;
        $location = $this->_request->location;//河北省唐山市古冶区
        $area_id = $this->_request->area_id;//区id
        $address = $this->_request->address;//详细地址
        $contact = $this->_request->contact;//联系人
        $phone = $this->user->mobile;//联系电话
        $remark = $this->_request->remark;//留言
        if( !$type || !$phone || !$address) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        if($type < 5) {
            $where = 'mobile = '.$phone.' and role = '."'resale'".' and resale_grade = '.$type;
        } elseif($type == 5) {
            $where = 'mobile = '.$phone.' and role = '."'seller'";
        } else {
            $where = 'mobile = '.$phone.' and role = '."'agent'";
        }
        $result = M('User')->select('id')->where($where)->fetchRow()->toArray();
        $msg = '';
        switch($type) {
            case 1:
                $msg = '此手机号已经申请一星分销商，请不要重复申请';
                break;
            case 2:
                $msg = '此手机号已经申请二星分销商，请不要重复申请';
                break;
            case 3:
                $msg = '此手机号已经申请三星分销商，请不要重复申请';
                break;
            case 4:
                $msg = '此手机号已经申请四星分销商，请不要重复申请';
                break;
            case 5:
                $msg = '此手机号已经申请商家，请不要重复申请';
                break;
            case 6:
                $msg = '此手机号已经申请代理商，请不要重复申请';
                break;
        }
        if($result) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,$msg);
            die();
        }
        $data = array(
            'grade' => $type,
            'location' => $location,
            'area_id' => $area_id,
            'address' => $address,
            'contact' => $contact,
            'phone' => $phone,
            'remark' => $remark,
        );
        if($type == 5) {
            $company = $this->_request->company;//公司或商家名称
            $business = $this->_request->business;//经营类别
            $day_selas = $this->_request->day_selas;//日营业额
            $day_volume = $this->_request->day_volume;//日客流量
            $day_volume_stat = $this->_request->day_volume_stat;//日客流量状态
            $area = $this->_request->area;//占地面积
            $data['area'] = $area;
            $data['company'] = $company;
            $data['type'] = $business;
            $data['day_selas'] = $day_selas;
            $data['day_volume'] = $day_volume;
            $data['day_volume_stat'] = $day_volume_stat;
        }
        $insert_id = M('Resale_Apply')->insert($data);
        echo $this->_encrypt_data($insert_id);
        //echo $this->show_data($this->_encrypt_data($insert_id));
        die();
    }
    /**
     * 充值说明
     * credit 帮帮币
     * credit_coin 积分币
     * vouchers 抵用券
     */
    public function doPayDesc() {
        $type = $this->_request->type;
        if( !$type ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $coltype = M('Coltypes')->select('id,english,name')->where("english='".$type."'")->fetchRow()->toArray();

        $data = M('Proportion')->select()->where('type=7 and right_id='.(int)$coltype['id'])->fetchRow()->toArray();
        $view = $this->_initView();
        $view->data = $data;
        $view->name = $coltype['name'];
        $view->type = $type;
        $view->render('views/app/recharge.php');
    }
    /**
     * 充值比例
     */
    public function doProDesc() {
        $type = $this->_request->type;
        if( !$type ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $coltype = M('Coltypes')->select('id,english,name')->where("english='".$type."'")->fetchRow()->toArray();

        $pro = M('Proportion')->select()->where('type=7 and right_id='.(int)$coltype['id'])->fetchRow()->toArray();
        $data = array(
            'name'  =>  '1元='.$pro['r_digital'].$coltype['name'],
            'value' =>  $pro['r_digital']
        );
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 激活说明
     */
    public function doActiveDesc() {
        $view = $this->_initView();
        $view->render('views/app/member_two.php');
    }
    /**
     * 充值
     * type ：
     * credit_coin 积分币 credit 帮帮币 vouchers抵用券
     * hybrid购买抵佣金混合支付   cash购买抵佣金只用现金 single购买抵佣金除了现金的通用货币
     * vouchers 购买抵用券
     * vip0_active 用户激活
     * vip1_active 升级一星分销商
     * vip2_active 升级二星分销商
     * vip3_active 升级三星分销商
     * vip4_active 升级四星分销商
     */
    public function doRecharge() {
        $type = $this->_request->type;
        switch ($type) {
            case 'credit':
                $prefix = 'RCA-';
                break;
            case 'credit_happy':
                $prefix = 'RCB-';
                break;
            case 'credit_coin':
                $prefix = 'RCC-';
                break;
            case 'vip0_active':
                $prefix = 'VIP-';
                break;
            case 'vip1_active':
                $prefix = 'VIP1-';
                break;
            case 'vip2_active':
                $prefix = 'VIP2-';
                break;
            case 'vip3_active':
                $prefix = 'VIP3-';
                break;
            case 'vip4_active':
                $prefix = 'VIP4-';
                break;
            case 'hybrid':
                $prefix = 'hybrid-';
                break;
            case 'cash':
                $prefix = 'cash-';
                break;
            case 'single':
                $prefix = 'single-';
                break;
            case 'vouchers'://抵佣券
                $prefix = 'RCD-';
                break;
        }
        $data = array(
            'trade_no' => $prefix.$this->user->id.'-'.time(),
            'subject' => '帐户充值',
        );
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
}