<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "teacher_school_private_course_classes".
 *
 * @property integer $id
 * @property integer $teacher_id
 * @property integer $school_private_course_class_id
 * @property string $entered_date
 * @property string $exited_date
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 */
class TeacherSchoolPrivateCourseClassesDB extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teacher_school_private_course_classes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['teacher_id', 'school_private_course_class_id', 'entered_date', 'exited_date'], 'required'],
            [['teacher_id', 'school_private_course_class_id', 'status'], 'default', 'value' => null],
            [['teacher_id', 'school_private_course_class_id', 'status'], 'integer'],
            [['entered_date', 'exited_date', 'created_at', 'updated_at'], 'safe'],
            [['school_private_course_class_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolPrivateCourseClassesDB::className(), 'targetAttribute' => ['school_private_course_class_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeachersDB::className(), 'targetAttribute' => ['teacher_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher ID',
            'school_private_course_class_id' => 'School Private Course Class ID',
            'entered_date' => 'Entered Date',
            'exited_date' => 'Exited Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
