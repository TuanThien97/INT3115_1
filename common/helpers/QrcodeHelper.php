<?php
namespace common\helpers;

include dirname(dirname(__DIR__)).'/common/library/phpqrcode/qrlib.php';
use Yii;

class QrcodeHelper {
    public static function png($string=null,$filename=null){
        if (!$string) return null;
        if ($filename) {
            return \QRcode::png($string, $filename);
        }
        return \QRcode::png($string);
    }

    public static function text($string=null){
        if (!$string) return null;
        return \QRcode::text($string); 
    }

    public static function base64($string=null){
        ob_start();
        \QRCode::png($string, null);
        $imageString = base64_encode( ob_get_contents() );
        ob_end_clean();
        return $imageString;
    }

}