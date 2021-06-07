<?php
namespace common\helpers;

use Yii;

class Utilities {
	/** 
	 * gen 4/5/6 digits verification code
	 * @param int $length
	 * @return string
	 */ 
	public static function generateVerificationCode($length=4){
		switch ($length) {
			case 4:
				$pin = mt_rand(1000, 9999);
				break;
			case 5:
				$pin = mt_rand(10000, 99999);
				break;
			case 6:
				$pin = mt_rand(100000, 999999);
				break;
			default:
				$pin = mt_rand(1000, 9999);
				break;
		}		
		return $pin;
	}

	/**
	 * generate random code
	 * @param int $length
	 * @return string
	 */
	public static function generateCodeWithOnlyNumber($length=8){
		$random_number=''; // set up a blank string
		$count=0;

		while ( $count < $length ) {
			$random_digit = mt_rand(0, 9);
			$random_number .= $random_digit;
			$count++;
		}

		return $random_number;
	}

	/**
	 * generate random string
	 * @param int $length
	 * @return string
	 */
	public static function generateRandomString($length = 6){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}