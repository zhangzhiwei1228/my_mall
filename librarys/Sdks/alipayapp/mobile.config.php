<?php
/**
 * Created by PhpStorm.
 * User: zzw
 * Date: 16-12-23
 * Time: 下午2:58
 */

$alipay_config['partner']        = '2088221359126641';

//收款支付宝账号，一般情况下收款账号就是签约账号
$alipay_config['seller_id']    = '13626566333@163.com';

//安全检验码，以数字和字母组成的32位字符
$alipay_config['key']            = 'bochy2h95oa54tpx2r3td5kyf4fwzamh';

//商户的私钥（后缀是.pen）文件相对路径
$alipay_config['private_key_path']	= LIB_DIR."Sdks/alipayapp/key/rsa_private_key.pem";

//支付宝公钥（后缀是.pen）文件相对路径
$alipay_config['ali_public_key_path']= LIB_DIR."Sdks/alipayapp/key/rsa_public_key.pem";


//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


//签名方式 不需修改
$alipay_config['sign_type']    = strtoupper('RSA');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//ca证书路径地址，用于curl中ssl校验
//请保证cacert.pem文件在当前文件夹目录中
$alipay_config['cacert']    = LIB_DIR."Sdks/alipayapp/cacert.pem";//getcwd().'\\cacert.pem';

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';