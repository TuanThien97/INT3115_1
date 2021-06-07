<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $phone_number
 * @property string $username
 * @property string $password_hash
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property integer $gender
 * @property integer $yob
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class UsersDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phone_number', 'username', 'password_hash', 'email'], 'required'],
            [['address'], 'string'],
            [['gender', 'yob', 'status'], 'default', 'value' => null],
            [['gender', 'yob', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['phone_number'], 'string', 'max' => 16],
            [['username', 'password_hash', 'email'], 'string', 'max' => 256],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['email'], 'unique'],
            [['phone_number'], 'unique'],
            [['username'], 'unique']
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
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'gender' => 'Gender',
            'yob' => 'Yob',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
