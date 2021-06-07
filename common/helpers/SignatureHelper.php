<?php
namespace common\helpers;

use Yii;

class SignatureHelper {
    /**
     * create signature using HMAC SHA256 algorithm
     * @param string $secret
     * @param array $params
     * @return string signature
     */
    public static function signature($secret,$params){
        $string = urldecode(http_build_query($params));
        //echo $string;die;
        $sig = hash_hmac('sha256', $string, $secret);
        return $sig;
    }

    /**
     * verify signature
     * @param string $secret
     * @param array $params
     * @param string $compared
     * @return bool true if verified
     */
    public static function verifySignature($secret, $params, $compared){
        return $compared == static::signature($secret,$params);
    }
}