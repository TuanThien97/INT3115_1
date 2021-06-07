<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class TeacherCheckins extends \common\models\db\TeacherCheckins {
	// add timestamp behavior
	const PRESENCE=1;
	const ABSENCE_WITH_REASON=2;
	const ABSENCE_NO_REASON=3;
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