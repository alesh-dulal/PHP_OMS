<?php
namespace common\components;
use Yii;
use yii\base\Component;
class EncryptionHelper extends Component{
    var $kdey="0cb04b7e103a0cd8b44763051cef08bc55abe029f7ebae8e1d417e2ffb2800a3";
    var $cipher = "aes-128-cbc";
    var $tag = "";
    public  function Encode($value){ 
        if(!$value)
        {
            return false;
        }
        $bitKey = pack('H*',$this->kdey);
        $iv_size = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($iv_size);
        $ciphertext = openssl_encrypt($value, $this->cipher, $bitKey, 0, $iv/*, $this->tag*/);
        $ciphertext = $iv . $ciphertext;
        return base64_encode($ciphertext);
    }
    public function Decode($value){
        $ciphertext=  base64_decode($value);
        $iv_size = openssl_cipher_iv_length($this->cipher);
        $iv_dec = substr($ciphertext, 0, $iv_size);
        $bitKey = pack('H*',$this->kdey);
        $ciphertext = substr($ciphertext, $iv_size);
        return openssl_decrypt($ciphertext, $this->cipher, $bitKey, 0,  $iv_dec/*, $this->tag*/);
    }
    function RandomKey($length) {
        $ret='';
        $dist = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
        for($i=0; $i < $length; $i++) 
        {  
            $ret .= $dist[mt_rand(0, count($dist) - 1)];
        }
        return $ret;
    }
}
?>