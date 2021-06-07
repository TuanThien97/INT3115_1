<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teacher_checkins".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $class_id
 * @property string $checkin_time
 * @property string $note
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Classes $class
 * @property Teachers $teacher
 */
class TeacherCheckins extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher_checkins';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'class_id', 'checkin_time'], 'required'],
            [['teacher_id', 'class_id', 'status'], 'default', 'value' => null],
            [['teacher_id', 'class_id', 'status'], 'integer'],
            [['checkin_time', 'created_at', 'updated_at'], 'safe'],
            [['note'], 'string'],
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
            'class_id' => 'Class ID',
            'checkin_time' => 'Checkin Time',
            'note' => 'Note',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
