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
        $this->user = $this->_auth();
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
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }
    protected function argSort($para) {
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
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
}