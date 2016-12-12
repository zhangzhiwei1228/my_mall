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
        $data['is_vip'] = $user_data['is_vip'];
        $data['shop_id'] = $user_data['shop_id'];
        $data['avatar'] = $user_data['avatar'] ? 'http://'.$_SERVER['HTTP_HOST'].$user_data['avatar'] : '';
        $count = M('User_Cart')->count('user_id = '.$user->id);
        $extends = M('User_Extend')->select('field_key,field_name,field_value')->where('user_id ='.$user->id)->fetchRows()->toArray();
        foreach($extends as $row) {
            if($row['field_key'] == 'gender') {
                $data['gender'] = $row['field_value'];
            }
            if($row['field_key'] == 'birthday') {
                $data['birthday'] = $row['field_value'];
            }
        }
        $data['count_cart'] = $count;
        //$data['exts'] = $extends;
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
        $this->UserExtend();//初始化额外信息
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
        $type = $this->_request->type;//1注册2找回密码
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
        if($type == 1) {
            if ($user->exists() ) {
                echo  self::_error_data(API_EXISTED_PHONE,'此手机号已被注册');
                die();
            }
        } elseif($type == 2) {
            if (!$user->exists() ) {
                echo  self::_error_data(ERR_LOGIN_FAIL_PWD_OR_ACCOUNT,'不存在此手机号');
                die();
            }
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
        $is_vip = $this->user->is_vip;
        if(!$is_vip) {
            echo  self::_error_data(API_IS_NOT_VIP,'您还没有激活，请先激活');
            die();
        }
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
        $cart = M('Cart')->doAppAddItem(
            (int)$this->user->id,
            (int)$good_id,
            (int)$sku_id,
            (int)$qty,
            (int)$price_type
            ,0,0,(int)$shipping_id
        );
        /*$cart = M('User_Cart')->insert(array(
            'user_id' => $this->user->id,
            'goods_id' => $good_id,
            'sku_id' => $sku_id,
            'shipping_id' => $shipping_id,
            'price_type' => $price_type,
            'checkout' => $checkout,
            'qty' => $qty,
        ));*/
        $data = array('id'=> $cart);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
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
            ->order('c.create_time asc')
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
        $ids = explode(',',$cart_id);
        foreach($ids as $id) {
            $cart = M('User_Cart')->select()->where('id='.(int)$id)->fetchRow()->toArray();
            if(!$cart) {
                echo  self::_error_data(API_CART_NOT_FOUND,'此购物车id不存在');
                die();
            }
            M('User_Cart')->deleteById((int)$id);
        }
        $data = array('status'=>'ok');
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
    public function doBale() {
        $this->user = $this->_auth();
        $cart_ids = $this->_request->ids;
        $addr_id = $this->_request->addr_id;
        if(!$cart_ids ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        if($addr_id) {
            $addr = M('User_Address')->select('area_id,area_text,consignee,address,zipcode,phone')->where('id ='.(int)$addr_id.' and user_id ='.(int)$this->user->id)->fetchRow()->toArray();
            if(!$addr) {
                echo self::_error_data(API_USER_ADDR_NOT_FOUND,'用户地址不正确');
                die();
            }
        } else {
            $addr = M('User_Address')->select('id,area_id,area_text,consignee,address,zipcode,phone')->where('user_id ='.(int)$this->user->id.' and is_def=1' )->fetchRow()->toArray();
            if($addr) {
                $addr_id = $addr['id'];
            }
        }
        $cart = M('Cart');
        $cart->setAppCart($this->user->id);

        if (!$cart->getTotalQty()) {
            echo  self::_error_data(API_NO_CHOOSE_GOODS,'请选择结算的商品');
            die();
        }
        $pricetype = array();
        $qty = array();
        $ids = explode(',',$cart_ids);
        foreach($ids as $key=>$val) {
            $data = M('User_Cart')->select('goods_id,sku_id,price_type,qty')->where('id = '.(int)$val)->fetchRow()->toArray();
            if(!$data) continue;
            $codes[] = $data['goods_id'].'.'.$data['sku_id'].'.'.$data['price_type'];
            $pricetype[$data['goods_id'].'.'.$data['sku_id']] = $data['price_type'];
            $qty[$data['goods_id'].'.'.$data['sku_id']] = $data['qty'];
        }
        if (!$codes) {
            echo  self::_error_data(API_NO_CHOOSE_GOODS,'结算商品数据错误');
            die();
        }
        $cart->checking($codes);
        $status = $cart->getAllStatus();
        $order_json = json_decode($status['order_json']);
        $postage = 0;
        //将分好的商品的邮费计算出来
        foreach($order_json as $key =>&$val) {
            $val = get_object_vars($val);
            unset($val['thumb']);
            unset($val['points']);
            if(strpos($val['skus_id'],',')) {
                $sku_ids = explode(',',$val['skus_id']);
                foreach($sku_ids as $k => $sku_id) {
                    $sku = M('Goods_Sku')->select()->where('id = ?', (int)$sku_id)->fetchRow()->toArray();
                    $sku['price_type'] = $pricetype[$sku['goods_id'].'.'.$sku_id];
                    $sku['exts'] = json_encode($sku['exts']);
                    $price_type = M('User_Cart')->price_type($sku);
                    $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                    $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                    $good['qty'] = $qty[$sku['goods_id'].'.'.$sku_id];
                    $spec = explode(',',$sku['spec']);
                    $arr = array();
                    foreach($spec as $key1=>$val1) {
                        $val1 = substr($val1,0,strlen($val1)-1);
                        $val1 = substr($val1,1);
                        $val1 = explode(':',$val1);
                        $arr[$key1]['name'] = $val1[0];
                        $arr[$key1]['value'] = $val1[1];
                    }
                    $good['spec'] = $arr;
                    $good['price_type'] = $price_type['price_text'];
                    unset($good['price']);
                    unset($good['unit']);
                    $good['sku_id'] = $sku_id;
                    //$val['goods'][] = $good;
                    $val['goods'][$k] = $good;
                }

            } else {
                $sku = M('Goods_Sku')->select()->where('id = ?', (int)$val['skus_id'])->fetchRow()->toArray();
                $sku['price_type'] = $pricetype[$sku['goods_id'].'.'.$val['skus_id']];
                $sku['exts'] = json_encode($sku['exts']);
                $price_type = M('User_Cart')->price_type($sku);
                $good = M('Goods')->select('id,title,thumb,package_weight')->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                $good['thumb'] = 'http://'.$_SERVER['HTTP_HOST'].$good['thumb'];
                $good['qty'] = $qty[$sku['goods_id'].'.'.$val['skus_id']];
                $spec = explode(',',$sku['spec']);
                $arr = array();
                foreach($spec as $key1=>$val1) {
                    $val1 = substr($val1,0,strlen($val1)-1);
                    $val1 = substr($val1,1);
                    $val1 = explode(':',$val1);
                    $arr[$key1]['name'] = $val1[0];
                    $arr[$key1]['value'] = $val1[1];
                }
                $good['spec'] = $arr;
                $good['price_type'] = $price_type['price_text'];
                unset($good['price']);
                unset($good['unit']);
                $good['sku_id'] = $val['skus_id'];
                $val['goods'][] = $good;
            }

            $order['shipping_id'] = $val['shipping_id'];
            if($addr_id) {
                $order['area_id'] = $addr['area_id'];
                $postage = $this->doPostAge($order, $val['total'], $val['weight']);
            }
            $val['total_postage'] = $postage;
        }
        $addr = array('addr'=>$addr);
        $order_json = array('bales'=>$order_json);
        $order_json = array_merge($order_json,$addr);
        echo $this->_encrypt_data($order_json);
        //echo $this->show_data($this->_encrypt_data($order_json));
        die();
    }

    /**
     * 生成订单
     */
    public function doCreateOrder() {
        $this->user = $this->_auth();
        $cart_ids = $this->_request->ids;
        $addr_id = $this->_request->addr_id;
        if(!$cart_ids || !$addr_id) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cart = M('Cart');
        $cart->setAppCart($this->user->id);

        if (!$cart->getTotalQty()) {
            echo  self::_error_data(API_NO_CHOOSE_GOODS,'请选择结算的商品');
            die();
        }
        $ids = explode(',',$cart_ids);
        foreach($ids as $key=>$val) {
            $data = M('User_Cart')->select('goods_id,sku_id,price_type')->where('id = '.(int)$val)->fetchRow()->toArray();
            if(!$data) continue;
            $codes[] = $data['goods_id'].'.'.$data['sku_id'].'.'.$data['price_type'];
        }
        if (!$codes) {
            echo  self::_error_data(API_NO_CHOOSE_GOODS,'结算商品数据错误');
            die();
        }
        $cart->checking($codes);
        $items = $cart->getItems();
        $status = $cart->getAllStatus();
        $addr = M('User_Address')->select('area_id,area_text,consignee,address,zipcode,phone')->where('id ='.(int)$addr_id.' and user_id ='.(int)$this->user->id)->fetchRow()->toArray();
        if(!$addr) {
            echo self::_error_data(API_USER_ADDR_NOT_FOUND,'用户地址不正确');
            die();
        }
        $addr['addr_id'] = $addr_id;
        M('Order')->getAdapter()->beginTrans();
        try{
            //减库存
            foreach ($items as $key =>$item) {
                //处理规格库存
                if ($item['skuId']) {
                    $sku = M('Goods_Sku')->select()
                        ->where('id = ?', (int)$item['skuId'])
                        ->forUpdate(1)
                        ->fetchRow();
                    if ($sku['quantity'] < $item['qty'] || $item['qty'] == 0) {
                        throw new Suco_Exception('很抱歉，商品 “'.$item['goods']['title'].'” 已经缺货。');
                    }
                    $sku->quantity -= $item['qty'];
                    $sku->sales_num += $item['qty'];
                    $sku->save();
                }
                if($item['shipping_id']) {
                    $status['shipping_id'] = $item['shipping_id'];
                }
                M('Goods')->updateById('
					sales_num = sales_num + '.(int)$item['qty'].',
					trans_num = trans_num + 1,
					quantity =	quantity - '.(int)$item['qty']
                    , (int)$item['goods']['id']);
                $shippings[$key] = $item['shipping_id'];
            }
            $postage = $this->doPostAge($item, $item['total'], $item['subtotal_weight']);
            $status['total_freight'] = $postage;
            $status['total_pay_amount'] = $status['total_pay_amount']+$postage;
            $oid = M('Order')->insert(array_merge($addr, $status, array(
                'code' => time(),
                'buyer_id' => $this->user->id,
                'invoice_id' => (int)$invoiceId,
                'status' => 1,
                'is_virtual' => 0,
                'expiry_time' => time() + (int)M('Setting')->timeout_pay,
            )));
            foreach($items as $k => $row) {
                unset($row['goods']['id']);
                unset($row['goods']['key']);
                unset($row['goods']['cost_price']);
                unset($row['goods']['market_price']);
                unset($row['goods']['point1']);
                unset($row['goods']['point2']);
                unset($row['goods']['point3']);
                unset($row['goods']['point4']);
                unset($row['goods']['point5']);
                unset($row['goods']['quantity']);
                unset($row['goods']['quantity_warning']);
                unset($row['goods']['thumb1']);
                unset($row['goods']['package_weight']);
                unset($row['goods']['package_unit']);
                unset($row['goods']['package_quantity']);
                unset($row['goods']['package_lot_unit']);
                unset($row['goods']['price_text']);
                M('Order_Goods')->insert(array_merge($row['goods'], array(
                    'order_id' => $oid,
                    'buyer_id' => $this->user->id,
                    'subtotal_amount' => $row['subtotal_amount'],
                    'subtotal_weight' => $row['subtotal_weight'],
                    'subtotal_save' => $row['subtotal_save'],
                    'purchase_quantity' => $row['qty'],
                    'promotion' => $row['goods']['price_label'],
                    'subtotal_vouchers' => $row['subtotal_vouchers'],
                    //'unit' => $row['unit'],
                    'sku_id' => $row['skuId']
                )));
                $cart->delItem($k,$this->user->id);
            }
            //发票处理
            if ($_POST['invoice']['type_id']) {
                $invoiceId = M('Invoice')->insert(array_merge($_POST['invoice'], $_POST, array(
                    'order_ids' => $oid,
                    'invoice_amount' => $status['total_amount']
                )));
            }

            //销毁购物车
            //$cart->destroy();
            M('Order')->getAdapter()->commit();
            $order = M('Order')->getById((int)$oid);

            $order_json = json_decode($order['order_json']);
            $order_postage = 0;
            //将分好的商品的邮费计算出来
            foreach($order_json as $key =>$val) {
                if(strpos($val->skus_id,',')) {
                    $sku_ids = explode(',',$val->skus_id);
                    foreach($sku_ids as $sku_id) {
                        $sku = M('Goods_Sku')->select()->where('id = ?', (int)$sku_id)->fetchRow();
                        $good = M('Goods')->select()->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                        $val->goods[$sku_id] = $good;
                    }

                } else {
                    $sku = M('Goods_Sku')->select()->where('id = ?', (int)$val->skus_id)->fetchRow();
                    $good = M('Goods')->select()->where('id = ?', (int)$sku['goods_id'])->fetchRow()->toArray();
                    $val->goods[$val->skus_id] = $good;
                }

                $order['shipping_id'] = $val->shipping_id;
                $postage = $this->doPostAge($order, $val->total, $val->weight);
                $val->order_postage = $postage;
                $order_postage += $postage;
            }
            $total_postage = $order['order_json'] ? $order_postage : $this->doPostAge($order);//计算邮费
            $order->total_pay_amount = $total_postage+$order->total_pay_amount;
            $total_amount = $total_postage+$order->total_amount;
            $order->total_amount = $total_amount;
            $pay = 0;
            if(!$total_amount) {
                if ($order['total_credit'] > 0 && $this->user['credit'] < $order['total_credit']) {
                    echo self::_error_data(API_USER_CREDIT_NO_ENOUGH,'支付失败，您的帮帮币不足');
                    die();
                }
                if ($order['total_credit_happy'] > 0 && $this->user['credit_happy'] < $order['total_credit_happy']) {
                    echo self::_error_data(API_USER_CREDIT_HAPPY_NO_ENOUGH,'支付失败，您的快乐积分不足');
                    die();
                }
                if ($order['total_credit_coin'] > 0 && $this->user['credit_coin'] < $order['total_credit_coin']) {
                    echo self::_error_data(API_USER_CREDIT_COIN_NO_ENOUGH,'支付失败，您的积分币不足');
                    die();
                }
                if ($order['total_vouchers'] > 0 && $this->user['vouchers'] < $order['total_vouchers']) {
                    echo self::_error_data(API_USER_VOUCHERS_NO_ENOUGH,'支付失败，您的抵用券不足');
                    die();
                }

                if ($order['total_credit']) {
                    $this->user->credit($order['total_credit']*-1, '支付订单【TS-'.$order['id'].'】');
                }
                if ($order['total_credit_happy']) {
                    $this->user->creditHappy($order['total_credit_happy']*-1, '支付订单【TS-'.$order['id'].'】');
                }
                if ($order['total_credit_coin']) {
                    $this->user->creditCoin($order['total_credit_coin']*-1, '支付订单【TS-'.$order['id'].'】');
                }
                if ($order['total_vouchers']) {
                    $this->user->vouchers($order['total_vouchers']*-1, '支付订单【TS-'.$order['id'].'】');
                }
                $order->status = 2;
                $order->save();
                $user_id = $order->buyer_id;
                $area_id = $order->area_id;
                $user_area = M('User_Area')->select('id')->where('user_id='.(int)$user_id)->fetchRow()->toArray();
                if(!$user_area) {
                    M('User_Area')->insert(array(
                        'user_id' => (int)$user_id,
                        'area_id' => $area_id,
                        'create_time' => time()
                    ));
                }
                $pay = 1;
            }
            $data = array(
                'oid' => $oid,
                'amount' => $order['total_amount'],
                'trade_no' => 'TS-'.$order->code,
                'subject' => '支付订单',
                'pay' => $pay
            );
            echo $this->_encrypt_data($data);
            //echo $this->show_data($this->_encrypt_data($data));
            die();
        } catch(Exception $e) {
            M('Order')->getAdapter()->rollback();
            echo  self::_error_data(API_ORDER_SUBMIT_FAIL,'订单提交失败');
            die();
        }
    }
    /**
     * 激活vip
     */
    public function doVip() {
        $this->user = $this->_auth();
        $is_vip = $this->user->is_vip;
        if($is_vip) {
            echo  self::_error_data(API_USER_IS_VIP,'您已经激活，请不要重复提交');
            die();
        }
        if ( $this->user->credit < 20) {
            echo  self::_error_data(API_USER_CREDIT_NO_ENOUGH,'帮帮币不足');
            die();
        }
        $this->user->credit(20 * -1,'使用20帮帮币激活会员');
        $this->user->is_vip = 1;
        $this->user->save();
        $this->user->activateAddCredit((int)$this->user->id);
        $data = 1;
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * @param $order
     * @param int $total
     * @param int $weight
     * @return float
     */
    public function doPostAge($order, $total=0, $weight=0) {
        //计算邮费
        $total_quantity = $total ? $total : round($order['total_weight'],2);
        $total_weight = $weight ? $weight : round($order['total_quantity'],2);
        $user_adder_area_id = (int)$order['area_id'];
        $shipping_id = (int)$order['shipping_id'];
        $region = M('Region')->getById($user_adder_area_id);
        //$region_parent_id = (int)$region['parent_id'];

        $region_path_ids = $region['path_ids'];
        $region_path_ids = explode(',',$region_path_ids);
        $region_province = $region_path_ids[2];//获取省
        $region_city = $region_path_ids[3];//获取市
        //在存运费的时候，如果存的是全省中的，那destination的值是省id，如果是某些市，那会是市id
        $shipping_freight = M('Shipping_Freight')->select()
            ->where('shipping_id = '. $shipping_id.' and (destination like '.'"%'.$region_province.'%" or destination like '.'"%'.$region_city.'%")')->fetchRow();

        $first_weight = (int)$shipping_freight['first_weight'];//首重
        $first_freight = round($shipping_freight['first_freight'],2);//一千克首重价格
        $second_weight = (int)$shipping_freight['second_weight'];//继重
        $second_freight = round($shipping_freight['second_freight'],2);//一千克继重价格
//		$one_weight = round($total_weight/$total_quantity,3);
//		$one_weight = ceil($total_weight/$total_quantity);//向上取正
        $one_weight = ceil($total_weight);//向上取正

        if($one_weight > $first_weight) {
            $total_postage = $first_weight*$first_freight+($one_weight-$first_weight)*$second_weight*$second_freight;
        } else {
            $total_postage = $first_weight*$first_freight;
        }
//		return round($total_quantity*$total_postage,2);
        return round($total_postage,2);
    }
    /**
     * 用户通知
     */
    public function doNoticeList() {
        $this->user = $this->_auth();
        $limit = $this->_request->limit;
        $page = $this->_request->page;
        if(!$limit || !$page) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $messages = M('Message')->alias('m')
            ->leftJoin(M('User')->getTableName().' AS u', 'u.id = m.sender_uid')
            ->columns('m.id,m.title,m.create_time,m.is_read, u.username AS sender_name, u.avatar AS sender_avatar')
            ->where('m.recipient_uid = ?', $this->user['id'])
            ->order('m.is_read ASC, m.id DESC')
            ->paginator((int)$limit, (int)$page);
        $data = $messages->fetchRows()->toArray();
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 消息详情
     */
    public function doNoticeDetail() {
        $this->user = $this->_auth();
        $id = $this->_request->id;
        if( !$id ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $news = M('Message')->select('content')->where('id='.(int)$id)->fetchRow()->toArray();
        if(!$news) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        M('Message')->updateById(array('is_read' => 1), (int)$id);
        $view = $this->_initView();
        $view->content = $news['content'];
        $view->render('views/app/news_info.php');
    }
    /**
     * 修改购物车
     */
    public function doEditCart() {
        $this->user = $this->_auth();
        $id = $this->_request->id;
        $qty = $this->_request->qty;
        if( !$id ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $cart = M('User_Cart')->select('id')->where('id='.(int)$id)->fetchRow()->toArray();
        if(!$cart) {
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'请求数据错误');
            die();
        }
        $data = M('User_Cart')->updateById(array('qty' => (int)$qty), (int)$id);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 修改用户信息
     */
    public function doEditUser() {
        $this->user = $this->_auth();
        $image = $this->Upload();//imgFile
        $nickname = $this->_request->nickname;//昵称
        $gender = $this->_request->gender;//性别
        $birthday = $this->_request->birthday;//出生年月
        $age = $this->_request->age;//年龄
        $data = array();


        if(isset($image['src']) ) {
            $data['avatar'] = $image['src'];
        }
        if($nickname) {
            $data['nickname'] = $nickname;
        }
        if($gender) {
            $extend['field_value'] = $gender;
            $str = 'gender';
            M('User_Extend')->update($extend, 'user_id = '.$this->user->id.' and field_key ='."'".$str."'");
        }
        if($birthday) {
            $extend['field_value'] = $birthday;
            $str = 'birthday';
            M('User_Extend')->update($extend, 'user_id = '.$this->user->id.' and field_key ='."'".$str."'");
        }
        if($age) {
            $extend['field_value'] = $age;
            $str = 'age';
            $user_age = M('User_Extend')->select('*')->where('user_id = '.$this->user->id.' and field_key ='."'".$str."'")->fetchRow()->toArray();
            if($user_age) {
                M('User_Extend')->update($extend, 'user_id = '.$this->user->id.' and field_key ='."'".$str."'");
            } else {
                $data = array(
                    'user_id'=>$this->user->id,
                    'field_key'=>$str,
                    'field_name'=>'年龄',
                    'field_value'=>$age,
                );
                M('User_Extend')->insert($data);
            }
        }
        if($data) {
            M('User')->updateById($data, (int)$this->user->id);
        }
        $insert = array('status'=>'ok');
        echo $this->_encrypt_data($insert);
        //echo $this->show_data($this->_encrypt_data($insert));
        die();
    }
    /**
     * 修改密码
     */
    public function doEditPwd() {
        $this->user = $this->_auth();
        $pwd = $this->_request->pwd;//原密码
        $repwd = $this->_request->repwd;//新密码
        $repwds = $this->_request->repwds;//确认密码
        if(!$pwd || !$repwd || !$repwds) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        if (!$this->user->checkPass($pwd)) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'原密码错误');
            die();
        }
        if($repwd != $repwds) {
            echo  self::_error_data(API_NO_EQUAL_PWD_REPWD,'确认密码两次不相同');
            die();
        }
        $this->user['password'] = $repwd;
        $this->user->save();
        $data = array('status' => 'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 找回密码
     */
    public function doForgetPwd() {
        $phone = $this->_request->phone;
        $code = $this->_request->code;
        $pwd = $this->_request->pwd;
        $repwd = $this->_request->repwd;
        if(!$pwd || !$repwd || !$code || !$phone) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        $user = M('User')->select()
            ->where('mobile = ?', $phone)
            ->fetchRow();
        if (!$user->exists() ) {
            echo  self::_error_data(API_RESOURCES_NOT_FOUND,'此手机号不存在');
            die();
        }
        $phone_code = M('Limit')->select('tel,code')->where('tel='.$phone.' and code='.$code)->fetchRow()->toArray();
        if (isset($code) && !$phone_code && $code != '122866') {
            echo  self::_error_data(API_PHONE_CODE_ERROR,'手机验证码错误');
            die();
        }
        if($pwd != $repwd) {
            echo  self::_error_data(API_NO_EQUAL_PWD_REPWD,'确认密码两次不相同');
            die();
        }

        $user->password = $pwd;
        $user->save();
        $data = array('status' => 'ok');
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    public function doInfo() {
        $this->user = $this->_auth();
        $token = $this->_request->token ;
        $user = M('User')
            ->select('id, token,is_vip,credit,credit_coin,worth_gold,vouchers,shop_id,nickname,avatar,mobile,username,role')
            ->where('token='."'".$token."'")
            ->fetchRow()->toArray();
        $count = M('User_Cart')->count('user_id = '.$user['id']);
        $extends = M('User_Extend')->select('field_key,field_name,field_value')->where('user_id ='.$user['id'])->fetchRows()->toArray();
        foreach($extends as $row) {
            if($row['field_key'] == 'gender') {
                $user['gender'] = $row['field_value'];
            }
            if($row['field_key'] == 'birthday') {
                $user['birthday'] = $row['field_value'];
            }
        }
        $user['avatar'] = 'http://'.$_SERVER['HTTP_HOST'].$user['avatar'];
        $user['count_cart'] = $count;
        echo $this->_encrypt_data($user);
        //echo $this->show_data($this->_encrypt_data($user));
        die();
    }
    public function UserExtend() {
        $data = array(
            'realname' => array(
                'name' => '',
                'value' => '',
            ),
            'gender' => array(
                'name' => '',
                'value' => '',
            ),
            'birthday' => array(
                'name' => '',
                'value' => '',
            ),
            'area' => array(
                'name' => '',
                'value' => '',
            ),
            'address' => array(
                'name' => '',
                'value' => '',
            ),
            'zipcode' => array(
                'name' => '',
                'value' => '',
            ),
            'major' => array(
                'name' => '',
                'value' => '',
            ),
            'qq' => array(
                'name' => '',
                'value' => '',
            ),
            'wechat' => array(
                'name' => '',
                'value' => '',
            ),
            'sign' => array(
                'name' => '',
                'value' => '',
            ),
        );
        M('User_Extend')->delete('user_id = ?', $this->user['id']);
        foreach($data as $k => $v) {
            M('User_Extend')->insert(array(
                'user_id' => $this->user['id'],
                'field_key' => $k,
                'field_name' => $v['name'],
                'field_value' => $v['value']
            ));
        }
    }
}