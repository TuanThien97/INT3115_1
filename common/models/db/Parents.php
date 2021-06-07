<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "parents".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property string $email
 * @property string $address
 * @property string $dob
 * @property int $gender
 * @property string $professional_id
 * @property int $incoming_level_id
 * @property int $photo_id
 * @property string $code
 * @property string $qrcode
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $note
 * @property string $password
 * @property int $is_temporary_password
 * @property string $temp_password
 *
 * @property ParentKids[] $parentKids
 * @property ParentPushNotifications[] $parentPushNotifications
 * @property ParentSettings[] $parentSettings
 * @property IncomingLevels $incomingLevel
 * @property Photos $photo
 */
class Parents extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'phone_number'], 'required'],
            [['address', 'qrcode', 'note'], 'string'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['gender', 'incoming_level_id', 'photo_id', 'status', 'is_temporary_password'], 'default', 'value' => null],
            [['gender', 'incoming_level_id', 'photo_id', 'status', 'is_temporary_password'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['phone_number'], 'string', 'max' => 32],
            [['email', 'professional_id', 'password'], 'string', 'max' => 256],
            [['code'], 'string', 'max' => 16],
            [['temp_password'], 'string', 'max' => 512],
            [['phone_number'], 'unique'],
            [['incoming_level_id'], 'exist', 'skipOnError' => true, 'targetClass' => IncomingLevels::className(), 'targetAttribute' => ['incoming_level_id' => 'id']],
            [['photo_id'], 'exist', 'skipOnError' => true, 'targetClass' => Photos::className(), 'targetAttribute' => ['photo_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone_number' => 'Phone Number',
            'email' => 'Email',
            'address' => 'Address',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'professional_id' => 'Professional ID',
            'incoming_level_id' => 'Incoming Level ID',
            'photo_id' => 'Photo ID',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'note' => 'Note',
            'password' => 'Password',
            'is_temporary_password' => 'Is Temporary Password',
            'temp_password' => 'Temp Password',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentKids()
    {
        return $this->hasMany(ParentKids::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentPushNotifications()
    {
        return $this->hasMany(ParentPushNotifications::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentSettings()
    {
        return $this->hasMany(ParentSettings::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIncomingLevel()
    {
        return $this->hasOne(IncomingLevels::className(), ['id' => 'incoming_level_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
