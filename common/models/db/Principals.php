<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principals".
 *
 * @property int $id
 * @property string $phone_number
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address
 * @property int $gender
 * @property string $dob
 * @property int $photo_id
 * @property string $code
 * @property string $qrcode
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Albums[] $albums
 * @property Photos[] $photos
 * @property PrincipalNotifications[] $principalNotifications
 * @property PrincipalSchools[] $principalSchools
 * @property PrincipalSettings[] $principalSettings
 * @property Photos $photo
 */
class Principals extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'principals';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_number', 'username', 'password', 'first_name', 'last_name', 'email'], 'required'],
            [['address', 'qrcode'], 'string'],
            [['gender', 'photo_id', 'status'], 'default', 'value' => null],
            [['gender', 'photo_id', 'status'], 'integer'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['phone_number'], 'string', 'max' => 32],
            [['username', 'password'], 'string', 'max' => 256],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['email'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 16],
            [['phone_number'], 'unique'],
            [['username'], 'unique'],
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
            'phone_number' => 'Phone Number',
            'username' => 'Username',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'address' => 'Address',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'photo_id' => 'Photo ID',
            'code' => 'Code',
            'qrcode' => 'Qrcode',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(Albums::className(), ['principal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhotos()
    {
        return $this->hasMany(Photos::className(), ['principal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipalNotifications()
    {
        return $this->hasMany(PrincipalNotifications::className(), ['principal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipalSchools()
    {
        return $this->hasMany(PrincipalSchools::className(), ['principal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipalSettings()
    {
        return $this->hasMany(PrincipalSettings::className(), ['principal_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photos::className(), ['id' => 'photo_id']);
    }
}
