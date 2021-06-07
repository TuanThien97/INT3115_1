<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class PrincipalPushNotifications extends \common\models\db\PrincipalPushNotifications {
	// add timestamp behavior
	const PUSH_TYPE_TEACHER_ABSENCE='teacherabsence';
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