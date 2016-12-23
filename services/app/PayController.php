<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-6
 * Time: 下午3:02
 */
class App_PayController extends App_Controller_Action
{
    protected $_pid;
    protected $_config;
    protected $_sellerAccount;
    protected $notify_url;
    public function init()
    {
        parent::init();
    }

    /**
     * 支付宝签名 alipay.trade.app.pay
     */
    public function doAliSign() {
        $this->user = $this->_auth();
        $notifyUrl = (string)new Suco_Helper_Url('module=app&controller=pay&action=AliPayNotify');
        $trade_no = $this->_request->trade_no;
        $amount = $this->_request->amount;
        $subject = $this->_request->subject;
        if( !$trade_no || !$amount  ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        require_once LIB_DIR."Sdks/alipayapp/alipay.config.php";
        require_once LIB_DIR."Sdks/alipayapp/lib/alipay_notify.class.php";
        $paydata=array(
            'app_id'=>$alipay_config['APPID'],
            'method'=>"alipay.trade.app.pay",
            'charset'=>'utf-8',
            'sign_type'=>'RSA',
            //'format'=>'json',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url'=>$notifyUrl,
            'biz_content'=>json_encode(array('subject'=>$subject,'seller_id'=>$alipay_config['partner'],'body'=>"商品购买",'out_trade_no'=>$trade_no,'total_amount'=>$amount,'product_code'=>'QUICK_MSECURITY_PAY','timeout_express'=>'150m'))
        );

        $paydata=argSort($paydata);
        $str=createLinkstring($paydata);
        $paydata['sign']=rsaSign($str,trim($alipay_config['private_key_path']));
        //$data['sign']=rsaSign($str,trim($alipay_config['private_key_path']));

        /*$data['paycode']=createLinkstringUrlencode($paydata);
        echo json_encode($data);
        die();*/
        $data['paycode']=createLinkstringUrlencode($paydata);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
        $re_data['paytype']=1;

    }
    public function doTestAli() {
        $this->user = $this->_auth();
        $notifyUrl = (string)new Suco_Helper_Url('module=app&controller=pay&action=AliPayNotify');
        $trade_no = $this->_request->trade_no;
        $amount = $this->_request->amount;
        $subject = $this->_request->subject;
        if( !$trade_no || !$amount  ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        require_once LIB_DIR."Sdks/alipayapp/alipay.config.php";
        require_once LIB_DIR."Sdks/alipayapp/lib/alipay_notify.class.php";
        $paydata=array(
            'app_id'=>$alipay_config['APPID'],
            'method'=>"alipay.trade.app.pay",
            'charset'=>'utf-8',
            'sign_type'=>'RSA',
            //'format'=>'json',
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'notify_url'=>$notifyUrl,
            'biz_content'=>json_encode(array('subject'=>$subject,'seller_id'=>$alipay_config['partner'],'body'=>"商品购买",'out_trade_no'=>$trade_no,'total_amount'=>$amount,'product_code'=>'QUICK_MSECURITY_PAY','timeout_express'=>'150m'))
        );

        $paydata=argSort($paydata);
        $str=createLinkstring($paydata);
        $paydata['sign']=rsaSign($str,trim($alipay_config['private_key_path']));
        //$data['sign']=rsaSign($str,trim($alipay_config['private_key_path']));


        $data['paycode']=createLinkstringUrlencode($paydata);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
        $re_data['paytype']=1;

    }
    /**
     * 支付宝签名，mobile.securitypay.pay
     */
    public function doAliSignMobile() {
        $this->user = $this->_auth();
        $notifyUrl = (string)new Suco_Helper_Url('module=app&controller=pay&action=AliPayNotify');
        $trade_no = $this->_request->trade_no;
        $amount = $this->_request->amount;
        $subject = $this->_request->subject;
        if( !$trade_no || !$amount  ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        require_once LIB_DIR."Sdks/alipayapp/alipay.config.php";
        require_once LIB_DIR."Sdks/alipayapp/lib/alipay_notify.class.php";
        // 签约合作者身份ID
        $orderInfo['partner'] = '2088221359126641';
        // 签约卖家支付宝账号
        $orderInfo['seller_id'] = '13626566333@163.com';
        // 商户网站唯一订单号
        $orderInfo['out_trade_no'] = $trade_no;
        // 商品名称
        $orderInfo['subject'] = $subject;
        // 商品详情
        $orderInfo['body'] = '购买商品';
        // 商品金额
        $orderInfo['total_fee'] = $amount;
        // 服务器异步通知页面路径
        $orderInfo['notify_url'] = $notifyUrl;
        // 服务接口名称， 固定值
        $orderInfo['service'] = 'mobile.securitypay.pay';
        // 支付类型， 固定值
        $orderInfo['payment_type'] = '1';
        // 参数编码， 固定值
        $orderInfo['_input_charset'] = 'utf-8';

        // 设置未付款交易的超时时间
        // 默认30分钟，一旦超时，该笔交易就会自动被关闭。
        // 取值范围：1m～15d。
        // m-分钟，h-小时，d-天，1c-当天（无论交易何时创建，都在0点关闭）。
        // 该参数数值不接受小数点，如1.5h，可转换为90m。
        $orderInfo['it_b_pay'] = '30m';

        // extern_token为经过快登授权获取到的alipay_open_id,带上此参数用户将使用授权的账户进行支付
        // orderInfo += "&extern_token=" + "\"" + extern_token + "\"";

        // 支付宝处理完请求后，当前页面跳转到商户指定页面的路径，可空
        $orderInfo['return_url'] = 'm.alipay.com';
        // 调用银行卡支付，需配置此参数，参与签名， 固定值 （需要签约《无线银行卡快捷支付》才能使用）
        // orderInfo += "&paymethod=\"expressGateway\"";
        $orderInfo=argSort($orderInfo);
        $str=createLinkstring($orderInfo);
        $orderInfo['sign']=rsaSign($str,trim($alipay_config['private_key_path']));
        $data['paycode']=createLinkstringUrlencode($orderInfo);
        echo $this->_encrypt_data($data);
        //echo $this->show_data($this->_encrypt_data($data));
        die();
    }
    /**
     * 支付宝回调
     */
    public function doAliPayNotify() {
        require_once LIB_DIR . "Sdks/alipayapp/mobile.config.php";
        require_once LIB_DIR . "Sdks/alipayapp/lib/alipay_notify.class.php";
        //计算得出通知验证结果
        Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', 'start:', 'a');
        //Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', 'alipay_config: '.$alipay_config, 'a');
        $alipayNotify = new AlipayNotify($alipay_config);
        //Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', 'alipayNotify: '.$alipayNotify, 'a');
        $verify_result = $alipayNotify->verifyNotify();
        Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', 'verify_result: '.$verify_result, 'a');

        if ($verify_result) {
            //验证成功
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            Suco_File::write(LOG_DIR.'error_'.date('Ymd').'.log', $trade_status, 'a');
            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {

            }
        }
    }
    /**
     * 微信生成签名
     */
    public function doWxSign() {
        $this->user = $this->_auth();
        $trade_no = $this->_request->trade_no;
        $amount = $this->_request->amount;
        $subject = $this->_request->subject;
        if( !$trade_no || !$amount  ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        require_once LIB_DIR."Sdks/weixin/WxPayPubHelper/WxPayPubHelper.php";
        require_once LIB_DIR."Sdks/wxpay/lib/log.php";
        //初始化日志
        $logHandler= new CLogFileHandler(LOG_DIR.date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);
        $notify = new Notify_pub();

        $notifyUrl = (string)new Suco_Helper_Url('module=app&controller=pay&action=WxNotify');
        $paydata=array(
            'appid'=>WxPayConf_pub::APPID,
            'mch_id'=>WxPayConf_pub::MCHID,
            'nonce_str'=>$notify->createNoncestr(),
            'body'=>$subject,
            'out_trade_no'=>$trade_no,
            'total_fee'=>$amount * 100,
            'spbill_create_ip'=>get_ip(),
            'notify_url'=>$notifyUrl,
            'trade_type'=>'APP'
        );

        $paydata['sign']=$notify->getSign($paydata);
        //$data['sign']=$notify->getSign($paydata);

        $xml= $notify->postXmlCurl($notify->arrayToXml($paydata),'https://api.mch.weixin.qq.com/pay/unifiedorder');
        $paydatanew=$notify->xmlToArray($xml);
        if($paydatanew['return_code']=="SUCCESS"){
            $arr['appid']=$paydatanew['appid'];
            $arr['partnerid']=$paydatanew['mch_id'];
            $arr['prepayid']=$paydatanew['prepay_id'];
            $arr['package']="Sign=WXPay";
            $arr['noncestr']=$notify->createNoncestr();
            $arr['timestamp']=time();
            $arr['sign']=$notify->getSign($arr);
            $data['xml'] = $notify->arrayToXml($paydata);
            $data['json'] = $arr;
            echo $this->_encrypt_data($data);
            //echo $this->show_data($this->_encrypt_data($data));
            die();
        }else{
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'生成签名失败');
            die();
        }
    }
    public function doTest() {
        $this->user = $this->_auth();
        $trade_no = $this->_request->trade_no;
        $amount = $this->_request->amount;
        $subject = $this->_request->subject;
        if( !$trade_no || !$amount  ) {
            echo self::_error_data(API_MISSING_PARAMETER,'缺少必要参数');
            die();
        }
        require_once LIB_DIR."Sdks/weixin/WxPayPubHelper/WxPayPubHelper.php";
        require_once LIB_DIR."Sdks/wxpay/lib/log.php";
        //初始化日志
        $logHandler= new CLogFileHandler(LOG_DIR.date('Y-m-d').'.log');
        $log = Log::Init($logHandler, 15);
        $notify = new Notify_pub();

        $notifyUrl = (string)new Suco_Helper_Url('module=app&controller=pay&action=WxNotify');
        $paydata=array(
            'appid'=>WxPayConf_pub::APPID,
            'mch_id'=>WxPayConf_pub::MCHID,
            'nonce_str'=>$notify->createNoncestr(),
            'body'=>$subject,
            'out_trade_no'=>$trade_no,
            'total_fee'=>$amount,
            'spbill_create_ip'=>get_ip(),
            'notify_url'=>$notifyUrl,
            'trade_type'=>'APP'
        );

        $paydata['sign']=$notify->getSign($paydata);

        $xml= $notify->postXmlCurl($notify->arrayToXml($paydata),'https://api.mch.weixin.qq.com/pay/unifiedorder');
        $paydatanew=$notify->xmlToArray($xml);
        if($paydatanew['return_code']=="SUCCESS"){
            $arr['appid']=$paydatanew['appid'];
            $arr['partnerid']=$paydatanew['mch_id'];
            $arr['prepayid']=$paydatanew['prepay_id'];
            $arr['package']="Sign=WXPay";
            $arr['noncestr']=$notify->createNoncestr();
            $arr['timestamp']=time();
            $arr['sign']=$notify->getSign($arr);
            echo $this->_encrypt_data($arr);
            //echo $this->show_data($this->_encrypt_data($arr));
            die();
        }else{
            echo self::_error_data(API_RESOURCES_NOT_FOUND,'生成签名失败');
            die();
        }
    }
    /**
     * 微信回调
     */
    public function doWxNotify() {
        require_once LIB_DIR."Sdks/weixin/WxPayPubHelper/WxPayPubHelper.php";
        require_once LIB_DIR."Sdks/wxpay/lib/log.php";
        //初始化日志
        $logHandler= new CLogFileHandler(LOG_DIR.date('Y-m-d-H-i-s').'.log');
        $log = Log::Init($logHandler, 15);
        //使用通用通知接口
        $notify = new Notify_pub();
        //Log::DEBUG($notify);
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        Log::DEBUG("微信xml:");
        Log::DEBUG($xml);
        $notify->saveData($xml);

        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
            Log::DEBUG('shibai');
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
            Log::DEBUG('SUCCESS');
        }
        $returnXml = $notify->returnXml();
        Log::DEBUG('returnXml:'.$returnXml);
        echo $returnXml;

        Log::DEBUG('checkSign:'.$notify->checkSign());
        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                Log::DEBUG("微信【通信出错】:". $xml);

            } elseif ($notify->data["result_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                Log::DEBUG("微信【业务出错】:". $xml);

            } else {
                //此处应该更新一下订单状态，商户自行增删操作
                Log::DEBUG("微信【支付】:". print_r($notify,1));
                $out_trade_no=$notify->data["out_trade_no"];
                $out_trade_no_V = substr($out_trade_no, 0, 3);
                Log::DEBUG($out_trade_no);
                Log::DEBUG($out_trade_no_V);
            }
        }
    }
}