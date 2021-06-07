<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class KidDailyReports extends \common\models\db\KidDailyReports {
	const PAGE_SIZE = 10;
	
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

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['kid_id', 'class_id', 'teacher_id', 'day', 'content'], 'required'],
			[['kid_id', 'class_id', 'teacher_id', 'kid_stamp_id', 'status'], 'default', 'value' => null],
			[['kid_id', 'class_id', 'teacher_id', 'kid_stamp_id', 'status'], 'integer'],
			[['day', 'created_at', 'updated_at'], 'safe'],
			[['content'], 'string'],
			[['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
			[['kid_stamp_id'], 'exist', 'skipOnError' => true, 'targetClass' => KidStamps::className(), 'targetAttribute' => ['kid_stamp_id' => 'id']],
			[['kid_id'], 'exist', 'skipOnError' => true, 'targetClass' => Kids::className(), 'targetAttribute' => ['kid_id' => 'id']],
			[['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
			[['day'], 'date', 'format' => 'php:Y-m-d'],
		];
	}
}