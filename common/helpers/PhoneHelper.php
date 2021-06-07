<?php

namespace common\helpers;

use Yii;

/**
 * Phone helper
 */
class PhoneHelper
{
    /**
     * strip 0 prefix from phone number
     * @param string $phoneNumber
     */
    public static function sanitizePrefix($phoneNumber)
    {
        // strip 0xx from phone number
        preg_match('/^0(\d+)$/', $phoneNumber, $matches);
        if ($matches) {
            return $matches[1];
        }
        return $phoneNumber;
    }
    
    /**
     * get full phone number
     */
    public static function getPrefix($phoneNumber)
    {
        preg_match('/^0(\d+)$/', $phoneNumber, $matches);
        if ($matches) {
            return $phoneNumber;
        }
        return '0' . $phoneNumber;
    }

    /**
     * verify real phone number
     * @param string $phoneNumber
     */
    public static function isPhoneNumber($phoneNumber)
    {
        $phoneNumber = static::sanitizePrefix($phoneNumber);
        preg_match('/^(86|96|97|98|32|33|34|35|36|37|38|39|89|90|93|70|79|77|76|78|88|91|94|81|82|83|84|85|92|56|58|99|59)\d{7,8}$/', $phoneNumber, $matches);
        if (empty($matches)) {
            // phone number invalid
            return false;
        }
        return true;
    }

    /**
     * check if vnmobile or gmobile phone number
     * @param string $phoneNumber
     */
    public static function isVnMobilePhoneNumber($phoneNumber)
    {
        $phoneNumber = static::sanitizePrefix($phoneNumber);
        preg_match('/^(92|56|58|99|59)\d{7,8}$/', $phoneNumber, $matches);
        if (empty($matches)) {
            // phone number invalid
            return false;
        }
        return true;
    }
}
