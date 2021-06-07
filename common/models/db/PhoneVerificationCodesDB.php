<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "phone_verification_codes".
 *
 * @property integer $id
 * @property string $phone_number
 * @property string $code
 * @property string $expired_at
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class PhoneVerificationCodesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'phone_verification_codes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number', 'code', 'expired_at'], 'required'],
            [['expired_at', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['phone_number'], 'string', 'max' => 16],
            [['code'], 'string', 'max' => 8]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone_number' => 'Phone Number',
            'code' => 'Code',
            'expired_at' => 'Expired At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
