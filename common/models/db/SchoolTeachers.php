<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_teachers".
 *
 * @property int $id
 * @property int $school_id
 * @property int $teacher_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Schools $school
 * @property Teachers $teacher
 */
class SchoolTeachers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_teachers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_id', 'teacher_id'], 'required'],
            [['school_id', 'teacher_id', 'status'], 'default', 'value' => null],
            [['school_id', 'teacher_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_id'], 'exist', 'skipOnError' => true, 'targetClass' => Schools::className(), 'targetAttribute' => ['school_id' => 'id']],
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
            'school_id' => 'School ID',
            'teacher_id' => 'Teacher ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
    public function getTeacher()
    {
        return $this->hasOne(Teachers::className(), ['id' => 'teacher_id']);
    }
}
