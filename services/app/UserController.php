<?php

/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-11-9
 * Time: 下午6:55
 */
class App_UserController extends App_Controller_Action
{
    public function init()
    {
        parent::init();
        //$this->user = $this->_auth();
    }
    public function doDefault() {
        $user = M('User')->select('id,nickname,avatar,credit,credit_coin,vouchers,token')->where('id='.(int)$this->user->id)->fetchRow()->toArray();
        $user['avatar'] = 'http://'.$_SERVER['HTTP_HOST'].$user['avatar'];
        echo $this->_encrypt_data($user);
        //echo $this->show_data($this->_encrypt_data($user));
        die();
    }
    /**
     * 登录
     */
    public function doLogin() {
        $phone = $this->_request->phone;
        $pass = $this->_request->pwd;
        $app_id = $this->_request->app_id;//1wap2android3ios4pc
        if (!$phone || !is_mobile($phone)) {
            echo  self::_error_data(ERR_LOGIN_FAIL_PHONE,'无效手机号');
            die();

        }
        $user = M('User')->select()->where('username = ? OR email = ? OR mobile = ?', $phone)->fetchRow();
        if (!$user->exists() || ($user['password'] != $this->encrypt($pass, $user['salt']))) {
            echo  self::_error_data(ERR_LOGIN_FAIL_PWD_OR_ACCOUNT,'不存在此手机号或密码错误');
            die();
        }
        if (!$user['is_enabled']) {
            echo  self::_error_data(API_USER_DISABLE,'此账户已被禁用');
            die();
        }
        $ip = Suco_Controller_Request_Http::getClientIp();
        $user['last_login_time'] = time();
        $user['last_login_ip'] = ip2long($ip);
        $user['login_num'] += 1;

        $token_expire_time = time() + ONE_MONTH;
        $auth_token = md5(uniqid(mt_rand(), true));
        $user['token'] = $auth_token;
        $user['app_id'] = $app_id;
        $user['token_expire_time'] = $token_expire_time;
        $user['token_update_time'] = time();
        $user->save();
        $user_data = $user->toArray();
        $data['id'] = $user_data['id'];
        $data['role'] = $user_data['role'];
        $data['parent_id'] = $user_data['parent_id'];
        $data['username'] = $user_data['username'];
        $data['nickname'] = $user_data['nickname'];
        $data['mobile'] = $user_data['mobile'];
        $data['credit'] = $user_data['credit'];
        $data['credit_coin'] = $user_data['credit_coin'];
        $data['worth_gold'] = $user_data['worth_gold'];
        $data['vouchers'] = $user_data['vouchers'];
        $data['token'] = $user_data['token'];
        $data['avatar'] = $user_data['avatar'] ? 'http://'.$_SERVER['HTTP_HOST'].$user_data['avatar'] : '';
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 注册
     */
    public function doReg() {
        $phone = $this->_request->phone;
        $code = $this->_request->code;
        $pass = $this->_request->pwd;
        $repass = $this->_request->repwd;
        $invite = $this->_request->invite;
        $app_id = $this->_request->app_id;
        if(!$phone || !is_mobile($phone)){
            echo  self::_error_data(ERR_LOGIN_FAIL_PHONE,'手机号格式错误');
            die();
        }
        $user = M('User')->select()->where('username = ? OR email = ? OR mobile = ?', $phone)->fetchRow();
        if ($user->exists() ) {
            echo  self::_error_data(API_EXISTED_PHONE,'此手机号已被注册');
            die();
        }

        if($phone == $invite) {
            echo  self::_error_data(API_USER_INVITE_EQUAL,'注册手机号跟邀请人手机号相等');
            die();
        }
        if($invite && !is_mobile($invite)) {
            echo  self::_error_data(ERR_LOGIN_FAIL_PHONE,'邀请人手机号错误');
            die();
        }
        $inviter = M('User')->select()
            ->where('mobile = ? OR username = ?', $invite)
            ->fetchRow();
        if($invite && !$inviter->exists()) {
            echo  self::_error_data(API_NO_INVITE,'邀请帐号不存在');
            die();
        }
        if ($invite && !$inviter['is_enabled']) {
            echo  self::_error_data(API_USER_DISABLE,'邀请帐号已被禁用');
            die();
        }

        $phone_code = M('Limit')->select('tel,code')->where('tel='.$phone.' and code='.$code)->fetchRow()->toArray();
        if (isset($code) && !$phone_code && $code != '122866') {
            echo  self::_error_data(API_PHONE_CODE_ERROR,'手机验证码错误');
            die();
        }
        if(!$pass) {
            echo  self::_error_data(API_NO_PWD,'密码为空');
            die();
        }
        if(!$repass) {
            echo  self::_error_data(API_NO_REPWD,'确认密码为空');
            die();
        }
        if($repass != $pass) {
            echo  self::_error_data(API_NO_EQUAL_PWD_REPWD,'两次密码不一致');
            die();
        }

        $data = array(
            'mobile' =>$phone,
            //'sms_code' =>$code,
            'password' =>$pass,
            //'checkpass' =>$repass,
            //'invite_mobile' =>$invite,
        );
        $parent_id = $inviter ? $inviter->id : 0;
        $uid = M('User')->insert(array_merge($data, array(
            'username' => $phone,
            'nickname' => $phone,
            'is_enabled' => 1,
            //'referrals_id' => $_SESSION['recid'],
            'role' => 'member',
            'pay_pass' => $pass,
            //'admin_id' => $admin['id'],
            'ref' => $invite,
            'parent_id' => $parent_id,
            'exp' => 5 //初始经验值
        )));

        $user = M('User')->getById($uid);
        $this->_update_or_create_token($uid,$app_id,1);//创建token
        //自动通过手机验证
        $user->setAuth('mobile', 1);
        $user_data = $user->toArray();
        $data['id'] = $user_data['id'];
        $data['role'] = $user_data['role'];
        $data['parent_id'] = $user_data['parent_id'];
        $data['username'] = $user_data['username'];
        $data['nickname'] = $user_data['nickname'];
        $data['mobile'] = $user_data['mobile'];
        $data['credit'] = $user_data['credit'];
        $data['credit_coin'] = $user_data['credit_coin'];
        $data['worth_gold'] = $user_data['worth_gold'];
        $data['vouchers'] = $user_data['vouchers'];
        $data['token'] = $user_data['token'];
        $data['avatar'] = $user_data['avatar'] ? 'http://'.$_SERVER['HTTP_HOST'].$user_data['avatar'] : '';
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($user->toArray()));
        die();
    }
    /**
     * 获取验证码
     */
    public function doGetCode() {
        $phone = $this->_request->phone;
        $code_token = $this->_request->code_token;
        $token = md5('123bbw_'.date('YmdH'));
        if ($code_token != $token) {
            echo  self::_error_data(API_REG_VALIDATE_TOKEN_FAIL,'获取验证码token验证失败');
            die();
        }
        if(!$phone || !is_mobile($phone)){
            echo  self::_error_data(ERR_LOGIN_FAIL_PHONE,'手机号格式错误');
            die();
        }
        $user = M('User')->select()->where('username = ? OR email = ? OR mobile = ?', $phone)->fetchRow();
        if ($user->exists() ) {
            echo  self::_error_data(API_EXISTED_PHONE,'此手机号已被注册');
            die();
        }

        $check = M('Limit')->select()->where('tel='.$phone)->order('timeline desc')->fetchRow()->toArray();
        $count = M('Limit')->count('tel='.$phone);
        if ($check && $check['timeline'] >= time()) {
            echo  self::_error_data(API_SEND_CODE_QUICK,'发送频率过快，请稍后再试');
            die();
        }
        if ($count && $count >= 10) {
            echo  self::_error_data(API_SEND_PHONE_DAY_EXCEED_LIMIT,'此号码已超出单日发送限制');
            die();
        }

        $count = 6;
        $letter = FALSE;
        $ychar = "2,3,4,5,6,7,8,9";
        if($letter) {
            $ychar .= ',A,B,C,D,E,F,G,H,J,K,L,M,N,P,Q,R,S,T,U,V,W,X,Y,Z';
        } else {
            $ychar .= ',1,0';
        }
        $list = explode ( ",", $ychar );
        $code = '';
        for($i = 0; $i < $count; $i ++) {
            $randnum = rand ( 0, count($list) - 1 );
            $code .= $list [$randnum];
        }
        //发送短信
        //$fsurl="http://120.26.69.248/msg/HttpSendSM?account=shiyuan_yishenger&pswd=Yishenger2016&mobile=".$phone."&msg=".$msg."&needstatus=true";

        /*$msg="您的注册验证码为：".strtoupper ( $code )."，祝您购物愉快。";
        $fsurl="http://send.18sms.com/msg/HttpBatchSendSM?account=shiyuan_yishenger&pswd=Yishenger2016&mobile=".$phone."&msg=".$msg."&needstatus=true";
        $res=file_get_contents($fsurl);
        $msgarr=explode(',', $res);*/
        //if($msgarr[1]==0){
            $ip = Suco_Controller_Request_Http::getClientIp();
            $data['timeline'] = time() + PHONE_CODE_TIMEOUT;
            $data['ip'] = ip2long($ip);
            $data['tel'] = $phone;
            $data['code'] = $code;
            M('Limit')->insert($data);
            echo $this->_encrypt_data((int)$code);
            //echo $this->show_data($this->_encrypt_data($goods));
            die();
        //}
        echo  self::_error_data(API_GET_CODE_FAIL,'获取验证码失败');
        die();
    }
    /**
     * 用户添加购物车
     */
    public function doCart() {
        $this->user = $this->_auth();
        $good_id = $this->_request->good_id;
        $sku_id = $this->_request->sku_id;
        $shipping_id = $this->_request->shipping_id;
        $price_type = $this->_request->price_type;
        $qty = $this->_request->qty;
        $checkout = $this->_request->checkout;
        if(!$good_id || !$sku_id || !$shipping_id || !$price_type || !$qty ) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $good = M('Goods')->select()->where('id='.(int)$good_id)->fetchRow()->toArray();
        if(!$good) {
            echo  self::_error_data(API_GOOD_NOT_FOUND,'评价的商品不存在');
            die();
        }
        $sku = M('Goods_Sku')->select()->where('id='.(int)$sku_id)->fetchRow()->toArray();
        if(!$sku) {
            echo  self::_error_data(API_GOOD_SKU_NOT_FOUND,'此商品的规格不存在');
            die();
        }
        $shipping = M('Shipping')->select()->where('id='.(int)$shipping_id)->fetchRow()->toArray();
        if(!$shipping) {
            echo  self::_error_data(API_SHIPPING_NOT_FOUND,'发货地不存在');
            die();
        }
        $cart = M('User_Cart')->insert(array(
            'user_id' => $this->user->id,
            'goods_id' => $good_id,
            'sku_id' => $sku_id,
            'shipping_id' => $shipping_id,
            'price_type' => $price_type,
            'checkout' => $checkout,
            'qty' => $qty,
        ));
        echo $this->_encrypt_data($cart);
        //echo $this->show_data($this->_encrypt_data($cart));
        die();
    }
    /**
     * 购物车列表
     */
    public function doCartList() {
        $this->user = $this->_auth();
        $carts = M('User_Cart')->alias('c')
            ->leftJoin(M('Goods')->getTableName().' AS gs', 'c.goods_id = gs.id')
            ->leftJoin(M('Goods_Sku')->getTableName().' AS gsk', 'c.sku_id = gsk.id')
            ->columns('c.id,c.goods_id,c.price_type,c.qty, gs.title,gs.thumb, gsk.point1, gsk.point2,gsk.point3,gsk.point4,gsk.point5,gsk.exts,gsk.spec')
            ->where('c.user_id ='.(int)$this->user->id)
            ->fetchRows()->toArray();
        foreach($carts as $key1=> &$row) {
            $row = M('User_Cart')->price_type($row);
            $row['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$row['thumb'];
            $row['good_id'] = $row['goods_id'];
            unset($row['goods_id']);
            $spec = explode(',',$row['spec']);
            $arr = array();
            foreach($spec as $key=>$val) {
                $val = substr($val,0,strlen($val)-1);
                $val = substr($val,1);
                $val = explode(':',$val);
                $arr[$key]['name'] = $val[0];
                $arr[$key]['value'] = $val[1];
            }
            $row['spec'] = $arr;
        }
        //echo $this->_encrypt_data($carts);
        echo $this->show_data($this->_encrypt_data($carts));
        die();
    }
    /**
     * 用户删除购物车
     *
     */
    public function doDeleteCart() {
        $this->user = $this->_auth();
        $cart_id = $this->_request->id;
        if(!$cart_id) {
            echo  self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cart = M('User_Cart')->select()->where('id='.(int)$cart_id)->fetchRow()->toArray();
        if(!$cart) {
            echo  self::_error_data(API_CART_NOT_FOUND,'此购物车id不存在');
            die();
        }
        $data = M('User_Cart')->deleteById((int)$cart_id);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 用户管理收货地址
     */
    public function doAddrList() {
        $this->user = $this->_auth();
        $data =
            M('User_Address')
            ->select('id,area_id,area_text,consignee,address,zipcode,phone,is_def,create_time')
            ->where('user_id='.(int)$this->user->id.' and area_id <> 0 and phone <> '."''".' and address <>'."''" )->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 修改收货地址
     */
    public function doEditAddr() {
        $this->user = $this->_auth();
        $addr_id = $this->_request->id;
        $area_id = $this->_request->area_id;
        $area_text = $this->_request->area_text;
        $consignee = $this->_request->consignee;
        $address = $this->_request->address;
        $zipcode = $this->_request->zipcode;
        $phone = $this->_request->phone;
        $is_def = $this->_request->is_def;

        if(!$addr_id || !$area_id || !$area_text || !$consignee || !$address || !$zipcode || !$phone) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $addr = M('User_Address')->getById((int)$addr_id);
        if(!$addr) {
            echo self::_error_data(API_USER_ADDR_NOT_FOUND,'收获地址不存在');
            die();
        }
        $area = M('Region')->getById((int)$area_id);
        if(!$area) {
            echo self::_error_data(API_AREA_NOT_FOUND,'地区不存在');
            die();
        }
        if(!is_mobile($phone)) {
            echo self::_error_data(ERR_LOGIN_FAIL_PHONE,'手机格式错误');
            die();
        }
        $data = array(
            'area_id' => $area_id,
            'area_text' => $area_text,
            'consignee' => $consignee,
            'address' => $address,
            'zipcode' => $zipcode,
            'phone' => $phone,
            'is_def' => $is_def,
        );
        if($is_def) {
            M('User_Address')->update(array('is_def'=>0), 'user_id = '.(int)$this->user->id);
        }
        $update = M('User_Address')->updateById($data, (int)$addr_id);
        echo $this->_encrypt_data($update);
        //echo $this->show_data($this->_encrypt_data($update));
        die();
    }
    /**
     * 用户添加地址
     */
    public function doInsertAddr() {
        $this->user = $this->_auth();
        $area_id = $this->_request->area_id;
        $area_text = $this->_request->area_text;
        $consignee = $this->_request->consignee;
        $address = $this->_request->address;
        $zipcode = $this->_request->zipcode;
        $phone = $this->_request->phone;
        $is_def = $this->_request->is_def;
        if( !$area_id || !$area_text || !$consignee || !$address || !$zipcode || !$phone) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $data = array(
            'user_id' => $this->user->id,
            'area_id' => $area_id,
            'area_text' => $area_text,
            'consignee' => $consignee,
            'address' => $address,
            'zipcode' => $zipcode,
            'phone' => $phone,
            'is_def' => $is_def,
            'create_time' => time(),
        );
        $id = M('User_Address')->insert($data);
        echo $this->_encrypt_data($id);
        //echo $this->show_data($this->_encrypt_data($id));
        die();
    }
    /**
     * 用户删除地址
     */
    public function doDelAddr() {
        $this->user = $this->_auth();
        $addr_id = $this->_request->id;
        if(!$addr_id ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $addr = M('User_Address')->getById((int)$addr_id);
        if(!$addr) {
            echo self::_error_data(API_USER_ADDR_NOT_FOUND,'收获地址不存在');
            die();
        }
        $delete = M('User_Address')->deleteById((int)$addr_id);
        echo $this->_encrypt_data($delete);
        //echo $this->show_data($this->_encrypt_data($delete));
        die();
    }
    /**
     * 商品打包
     */
    public function doPackage() {

    }

}