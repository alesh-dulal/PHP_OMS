<?php
namespace common\components;

use Yii;
use yii\base\Component;

class EncryptionHelper extends Component{

     var $kdey="0cb04b7e103a0cd8b44763051cef08bc55abe029f7ebae8e1d417e2ffb2800a3";

    public  function Encode($value){ 
        if(!$value)
        {
            return false;
        }
            $bitKey = pack('H*',$this->kdey);
            $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
            $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
            $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $bitKey,
            $value, MCRYPT_MODE_CBC, $iv);
            $ciphertext = $iv . $ciphertext;

        return base64_encode($ciphertext);
    }
    
    public function Decode($value){
        $ciphertext=  base64_decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv_dec = substr($ciphertext, 0, $iv_size);
        $bitKey = pack('H*',$this->kdey);
        $ciphertext = substr($ciphertext, $iv_size);

        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $bitKey,

        $ciphertext, MCRYPT_MODE_CBC, $iv_dec);
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