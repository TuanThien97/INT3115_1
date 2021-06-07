<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "principal_push_notifications".
 *
 * @property int $id
 * @property int $principal_id
 * @property string $push_type
 * @property string $pushed_at
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $push_content
 * @property string $push_data
 * @property int $school_id
 * @property int $content_id
 *
 * @property Schools $school
 * @property Teachers $principal
 */
class PrincipalPushNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'principal_push_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['principal_id', 'push_type'], 'required'],
            [['principal_id', 'status', 'school_id', 'content_id'], 'default', 'value' => null],
            [['principal_id', 'status', 'school_id', 'content_id'], 'integer'],
            [['pushed_at', 'created_at', 'updated_at'], 'safe'],
            [['push_data'], 'string'],
            [['push_type'], 'string', 'max' => 32],
            [['push_content'], 'string', 'max' => 512],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
            [['principal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['principal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'principal_id' => 'Principal ID',
            'push_type' => 'Push Type',
            'pushed_at' => 'Pushed At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'push_content' => 'Push Content',
            'push_data' => 'Push Data',
            'school_id' => 'School ID',
            'content_id' => 'Content ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchool()
    {
        return $this->hasOne(Schools::className(), ['id' => 'school_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrincipal()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'principal_id']);
    }
}
