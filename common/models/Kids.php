<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\behavior\QrcodeBehavior;

class Kids extends \common\models\db\Kids {
	// add timestamp behavior
	const PAGE_SIZE=10;
	const GENDER_MALE = 1;
	const GENDER_FEMALE = 2;
	const MALE_DEFAULT_PHOTO_ID = 1;
	const FEMALE_DEFAULT_PHOTO_ID = 3;
	const MAX_PROTECTORS = 5;
	public function behaviors()
	{
	    return [
	        [
	            'class' => TimestampBehavior::className(),
	            'createdAtAttribute' => 'created_at',
	            'updatedAtAttribute' => 'updated_at',
	            'value' => new Expression('NOW()'),
			],
			QrcodeBehavior::className(),
	    ];
	}
}