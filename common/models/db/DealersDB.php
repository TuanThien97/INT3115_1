<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "dealers".
 *
 * @property integer $id
 * @property string $phone_number
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address
 * @property integer $gender
 * @property integer $yob
 * @property string $code
 * @property string $qrcode
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class DealersDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dealers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number', 'first_name', 'last_name', 'gender'], 'required'],
            [['address', 'qrcode'], 'string'],
            [['gender', 'yob', 'status'], 'default', 'value' => null],
            [['gender', 'yob', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['phone_number'], 'string', 'max' => 32],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 256],
            [['code'], 'string', 'max' => 16]
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'address' => 'Address',
            'gender' => 'Gender',
            'yob' => 'Yob',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
