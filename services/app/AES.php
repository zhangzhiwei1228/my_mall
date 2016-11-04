<?php 
/**
 * Class AES 
 * @author hanj
 */
class AES 
{
    public static function encrypt($input, $key,$bin2hex=false) {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = AES::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, md5($key), $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = $bin2hex ? bin2hex($data) : base64_encode($data);
        return $data;
    }


    private static function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private static function hex2bin($hexdata) {
        $bindata = '';
        $length = strlen($hexdata);
        for ($i=0; $i< $length; $i += 2)
        {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    public static function decrypt($sStr, $sKey, $bin2hex = false){
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');

        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        mcrypt_generic_init($td, md5($sKey), $iv);
        $newsStr = $bin2hex ? AES::hex2bin($sStr) : base64_decode($sStr);
        $decrypted_text = mdecrypt_generic($td,  $newsStr);
        $rt = rtrim($decrypted_text);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $rt = AES::pkcs5_pad($rt, $size);
        $dec_s = strlen($rt);
        $padding = ord($rt[$dec_s-1]);
        $rt = substr($rt, 0, -$padding);
        $rt = rtrim($rt);
        $rt = preg_replace('/(\}[^\]\}\{]*)$/', '}', $rt);
        return $rt;
        }
    /**
     * @param $str
     * @return string
     *
     */
    //加密
    public function AescbcEncode($str){
        $hex_iv = '0000000000000000';
        $key = 'U1MjU1M0FDOUZ.Qz';
        $block=mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,'cbc');
        $pad=$block-(strlen($str)%$block);
        if($pad<=$block)
        {
            $char=chr($pad);
            $str.=str_repeat($char,$pad);
        }

        $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $hex_iv);
        return base64_encode($encrypted);
    }

    //新增加密解密
    //解密
    function AescbcDecode($str,$isarray=0)
    {
        $hex_iv = '0000000000000000';
        $key = 'U1MjU1M0FDOUZ.Qz';
        $block=mcrypt_get_block_size(MCRYPT_RIJNDAEL_128,'cbc');
        $char=substr($str,-1,1);
        $num=ord($char);
        if($num<=8)
        {
            $len=strlen($str);
            for($i=$len-1;$i>=$len-$num;$i--) {
                if(ord(substr($str,$i,1))!=$num) {
                    return $str;
                }
            }
            $str=substr($str,0,-$num);
        }
        $str = base64_decode($str);
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $hex_iv);
        $number = $isarray==0 ? strripos($decrypted, '}',0) : strripos($decrypted, ']',0) ;
        $sub = substr($decrypted,0,$number+1);
        return json_decode($sub);
    }

    /*
     * rc4加密算法
     * $pwd 密钥
     * $data 要加密的数据
     */
    public static function rc4($data, $pwd)//$pwd密钥 $data需加密字符串
    {
        $key[] ="";
        $box[] ="";
        $cipher = '';

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;

            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;

            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }

        return $cipher;
    }
}