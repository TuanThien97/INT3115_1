<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\behavior\QrcodeBehavior;
use common\models\behavior\PhonenumberBehavior;
use common\models\behavior\PasswordBehavior;

class Parents extends \common\models\db\Parents
{
	const IS_TEMP_PASSWORD=1;
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
			QrcodeBehavior::className(),
			PhonenumberBehavior::className(),
			// PasswordBehavior::className(),
		];
	}
	/**
	 * set temporary password
	 * @param string $password
	 */
	public function setTempPassword($password)
	{
		$this->is_temporary_password = static::IS_TEMP_PASSWORD;
		$this->temp_password = \common\models\behavior\PasswordBehavior::encryptPassword($password);
	}
}
