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
        var_dump($this->createLinkstring($this->_config));
    }
    function createLinkstring($para) {
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
}