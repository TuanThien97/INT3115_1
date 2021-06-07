<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "universities".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Teachers[] $teachers
 */
class Universities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'universities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'address', 'phone', 'email'], 'required'],
            [['description', 'address'], 'string'],
            [['status'], 'default', 'value' => null],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 512],
            [['phone'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 256],
            [['email'], 'unique'],
            [['name'], 'unique'],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeachers()
    {
        return $this->hasMany(Teachers::className(), ['university_id' => 'id']);
    }
}
