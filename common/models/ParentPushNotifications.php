<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ParentPushNotifications extends \common\models\db\ParentPushNotifications {
	const PUSH_TYPE_PHOTO = 'photo';
	const PUSH_TYPE_CHECKIN = 'checkin';
	const PUSH_TYPE_CHECKOUT = 'checkout';
	const PUSH_TYPE_DAILYREPORT = 'dailyreport';
	const PUSH_TYPE_WEEKLYREPORT = 'weeklyreport';
	const PUSH_TYPE_KIDMESSAGE ='directions';
	const PUSH_TYPE_ABSENCE='absence';
	const PUSH_TYPE_PRESCRIPTION='prescription';
	
    // add timestamp behavior
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
}