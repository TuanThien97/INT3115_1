<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teachers".
 *
 * @property integer $id
 * @property string $phone_number
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address
 * @property integer $gender
 * @property integer $yob
 * @property integer $university_id
 * @property string $code
 * @property string $qrcode
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $photo_url
 */
class TeachersDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teachers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number', 'first_name', 'last_name', 'email', 'address', 'gender', 'yob', 'code', 'qrcode'], 'required'],
            [['address', 'qrcode', 'photo_url'], 'string'],
            [['gender', 'yob', 'university_id', 'status'], 'default', 'value' => null],
            [['gender', 'yob', 'university_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['phone_number'], 'string', 'max' => 32],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 256],
            [['code'], 'string', 'max' => 16],
            [['code'], 'unique'],
            [['email'], 'unique'],
            [['phone_number'], 'unique'],
            [['qrcode'], 'unique'],
            [['university_id'], 'exist', 'skipOnError' => true, 'targetClass' => UniversitiesDB::className(), 'targetAttribute' => ['university_id' => 'id']]
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
            'university_id' => 'University ID',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'photo_url' => 'Photo Url',
        ];
    }
}
