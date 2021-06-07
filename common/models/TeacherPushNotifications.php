<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TeacherPushNotifications extends \common\models\db\TeacherPushNotifications {
	const PAGE_SIZE = 10;
	const READ = 2;
	const UNREAD = 1;
	const PUSH_TYPE_TEACHERABSENCE='teacherabsence';
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