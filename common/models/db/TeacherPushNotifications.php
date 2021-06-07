<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teacher_push_notifications".
 *
 * @property int $id
 * @property int $teacher_id
 * @property string $push_type
 * @property string $pushed_at
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $push_content
 * @property string $push_data
 * @property int $class_id
 * @property int $content_id
 *
 * @property Classes $class
 * @property Teachers $teacher
 */
class TeacherPushNotifications extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_push_notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'push_type'], 'required'],
            [['teacher_id', 'status', 'class_id', 'content_id'], 'default', 'value' => null],
            [['teacher_id', 'status', 'class_id', 'content_id'], 'integer'],
            [['pushed_at', 'created_at', 'updated_at'], 'safe'],
            [['push_data'], 'string'],
            [['push_type'], 'string', 'max' => 32],
            [['push_content'], 'string', 'max' => 512],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classes::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teachers::className(), 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher ID',
            'push_type' => 'Push Type',
            'pushed_at' => 'Pushed At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'push_content' => 'Push Content',
            'push_data' => 'Push Data',
            'class_id' => 'Class ID',
            'content_id' => 'Content ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(Classes::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
