<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetable_details".
 *
 * @property int $id
 * @property int $class_timetable_id
 * @property int $school_grade_activity_id
 * @property string $from_time
 * @property string $to_time
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassTimetables $classTimetable
 * @property SchoolGradeActivities $schoolGradeActivity
 */
class ClassTimetableDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_timetable_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_timetable_id', 'school_grade_activity_id', 'from_time', 'to_time', 'sequence'], 'required'],
            [['class_timetable_id', 'school_grade_activity_id', 'sequence', 'status'], 'default', 'value' => null],
            [['class_timetable_id', 'school_grade_activity_id', 'sequence', 'status'], 'integer'],
            [['from_time', 'to_time', 'created_at', 'updated_at'], 'safe'],
            [['class_timetable_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassTimetables::className(), 'targetAttribute' => ['class_timetable_id' => 'id']],
            [['school_grade_activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGradeActivities::className(), 'targetAttribute' => ['school_grade_activity_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_timetable_id' => 'Class Timetable ID',
            'school_grade_activity_id' => 'School Grade Activity ID',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetable()
    {
        return $this->hasOne(ClassTimetables::className(), ['id' => 'class_timetable_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolGradeActivity()
    {
        return $this->hasOne(SchoolGradeActivities::className(), ['id' => 'school_grade_activity_id']);
    }
}
