<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class KidCheckins extends \common\models\db\KidCheckins {
	const PAGE_SIZE = 10;
	const PRESENCE = 1;
	const ABSENCE_WITH_REASON = 2;
	const ABSENCE_WITHOUT_REASON = 3;
	const TYPE_CREATE='create';
	const TYPE_EDIT='edit';
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