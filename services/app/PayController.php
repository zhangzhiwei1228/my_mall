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

        $payment = M('Payment')->select()
            ->where('code = ?', 'alipay')
            ->fetchRow();

        parse_str($payment['setting']);
        $this->_pid = $payment['id'];
        $this->_sellerAccount = '13626566333@163.com';
        $this->notify_url =  (string)new Suco_Helper_Url('module=default&controller=callback&action=payment&t=alipay').'/';
        $this->_config = array(
            #合作身份者id，以2088开头的16位纯数字
            'app_id' => '2088221359126641',
            'method' => 'alipay.trade.app.pay',
            'format' => 'JSON',

            #安全检验码，以数字和字母组成的32位字符
            //'key' => 'bochy2h95oa54tpx2r3td5kyf4fwzamh',

            #签名方式 不需修改
            'sign_type' => strtoupper('RSA'),

            #字符编码格式 目前支持 gbk 或 utf-8
            'charset' => strtolower('utf-8'),
            'timestamp' => date('Y-m-d H:i:s',time()),
            'version' => '1.0',
            'notify_url' => $this->notify_url,
        );
    }
    public function doSign() {
        $this->user = $this->_auth();
        require_once("Sdks/alipay/lib/alipay_submit.class.php");
        $amount = $this->_request->amount;
        $trade_no = $this->_request->trade_no;
        $subject = $this->_request->subject;
        $biz_content = array(
            'body' => '支付宝支付订单',
            'subject' => $subject.'【'.M('Setting')->sitename.'】',
            'out_trade_no' => $trade_no,
            'timeout_express' => '60m',
            'total_amount' => $amount,
            'product_code' => 'QUICK_MSECURITY_PAY',
        );
        $this->_config['biz_content'] = json_encode($biz_content);
        $this->_config = $this->paraFilter($this->_config);
        $this->_config = $this->argSort($this->_config);
        //拼接
        $mystr = $this->createLinkstring($this->_config);

        //签名
        $sign = $this->rsaSign($mystr,SRV_DIR.'app/alipay_private_key.pem');

        //对签名进行urlencode转码
        $sign = urlencode($sign);
        //生成最终签名信息

        $orderInfor = $mystr."&sign=".$sign."&sign_type=RSA";
        echo $orderInfor;
        //echo $this->_encrypt_data($sign);
        //echo $this->show_data($this->_encrypt_data($orderInfor));
        die();
    }
    protected function createLinkstring($para) {
        $this->user = $this->_auth();
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }
    protected function rsaSign($data, $private_key_path) {
        $this->user = $this->_auth();
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    protected function argSort($para) {
        $this->user = $this->_auth();
        ksort($para);
        reset($para);
        return $para;
    }
    /**RSA验签
     * $data待签名数据
     * $sign需要验签的签名
     * 验签用支付宝公钥
     * return 验签是否通过 bool值
     */
    protected function verify($data, $sign)  {
        $this->user = $this->_auth();
        //读取支付宝公钥文件
        $pubKey = file_get_contents(SRV_DIR.'app/alipay_public_key.pem');

        //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);

        //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res);

        //释放资源
        openssl_free_key($res);

        //返回资源是否成功
        return $result;
    }
    protected function paraFilter($para) {
        $this->user = $this->_auth();
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
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
        Log::DEBUG($notify);
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
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();

        echo $returnXml;


        if($notify->checkSign() == TRUE)
        {
            if ($notify->data["return_code"] == "FAIL") {
                //此处应该更新一下订单状态，商户自行增删操作
                Log::DEBUG("微信【通信出错】:". $xml);

            }
            elseif($notify->data["result_code"] == "FAIL"){
                //此处应该更新一下订单状态，商户自行增删操作
                Log::DEBUG("微信【业务出错】:". $xml);

            }
            else{
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