<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class KidMessages extends \common\models\db\KidMessages {
	// add timestamp behavior
	const PAGE_SIZE=10;
	const WAIT = 1;
	const FROM_KID=1;
	const APPROVAL = 2;
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