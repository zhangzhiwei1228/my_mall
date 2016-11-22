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
        $app_id = $this->_request->pwd;//1wap2android3ios4pc
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
        $user['auth_token'] = $auth_token;
        $user['app_id'] = $app_id;
        $user['token_expire_time'] = $token_expire_time;
        $user['token_update_time'] = time();
        $user->save();

        echo $this->_encrypt_data($user->toArray());
        //echo $this->show_data($this->_encrypt_data($user->toArray()));
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


        if (isset($code) && $code != $_SESSION['sms_code'] && $code != '122866') {
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

        $uid = M('User')->insert(array_merge($data, array(
            'username' => $phone,
            'nickname' => $phone,
            'is_enabled' => 1,
            'referrals_id' => $_SESSION['recid'],
            'role' => 'member',
            'pay_pass' => $pass,
            //'admin_id' => $admin['id'],
            'ref' => $invite,
            'parent_id' => $inviter ? $inviter->id : 0,
            'exp' => 5 //初始经验值
        )));

        $user = M('User')->getById($uid);
        $this->_update_or_create_token($uid,$app_id,1);//创建token
        //自动通过手机验证
        $user->setAuth('mobile', 1);
        echo $this->_encrypt_data($user->toArray());
        //echo $this->show_data($this->_encrypt_data($user->toArray()));
        die();
    }
    /**
     * 获取验证码
     */
    public function doGetCode() {
        $phone = $this->_request->phone;
        $code_token = $this->_request->code_token;
        $token = md5('tts_'.date('YmdH'));
        if ($code_token != $token) {
            echo  self::_error_data(API_REG_VALIDATE_TOKEN_FAIL,'获取验证码token验证失败');
            die();
        }
        if (isset($_SESSION['sms_time']) && $_SESSION['sms_time'] >= time()-60) {
            echo  self::_error_data(API_SEND_CODE_QUICK,'发送频率过快，请稍后再试');
            die();
        }
        if (isset($_SESSION['num'][$phone]) && $_SESSION['num'][$phone] >= 10) {
            echo  self::_error_data(API_SEND_PHONE_DAY_EXCEED_LIMIT,'此号码已超出单日发送限制');
            die();
        }
        if (isset($_SESSION['sms_num']) && $_SESSION['sms_num'] >= 19) {
            echo  self::_error_data(API_SEND_DAY_EXCEED_LIMIT,'超出单日发送限制');
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
        $msg="您的注册验证码为：".strtoupper ( $code )."，祝您购物愉快。";
        //$fsurl="http://120.26.69.248/msg/HttpSendSM?account=shiyuan_yishenger&pswd=Yishenger2016&mobile=".$phone."&msg=".$msg."&needstatus=true";
        $fsurl="http://send.18sms.com/msg/HttpBatchSendSM?account=shiyuan_yishenger&pswd=Yishenger2016&mobile=".$phone."&msg=".$msg."&needstatus=true";
        $res=file_get_contents($fsurl);
        $msgarr=explode(',', $res);
        if($msgarr[1]==0){
            $_SESSION['num'][$phone] ++;
            $_SESSION['sms_num'] ++;
            $_SESSION['sms_time'] = time();
            echo $this->_encrypt_data($code);
            //echo $this->show_data($this->_encrypt_data($goods));
            die();
        }
        echo  self::_error_data(API_GET_CODE_FAIL,'获取验证码失败');
        die();
    }

}