<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ClassTimetables extends \common\models\db\ClassTimetables {
	// add timestamp behavior
	const PAGE_SIZE = 10;
	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
	        ],
	    ];
	}
	public function convertVietnamese($day){
		switch ($day) {
            case "Mon":
                $day = 'Thứ Hai';
                break;
            case "Tue":
                $day = "Thứ Ba";
                break;
            case "Wed":
                $day = "Thứ Tư";
                break;
            case "Thu":
                $day = "Thứ Năm";
                break;
            case "Friday":
                $day = "Thứ Sáu";
                break;
            case "Saturday":
                $day = "Thứ Bảy";
                break;
            case "Sunday":
                $day = "Chủ Nhật";
                break;
            default:
                $day = "Ngày";
                break;
		}
		return $day;
	}
}