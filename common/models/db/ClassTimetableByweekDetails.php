<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "class_timetable_byweek_details".
 *
 * @property int $id
 * @property int $class_timetable_byweek_id
 * @property int $school_grade_activity_id
 * @property int $dow
 * @property string $from_time
 * @property string $to_time
 * @property int $icon_id
 * @property int $sequence
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassTimetableByweeks $classTimetableByweek
 * @property SchoolGradeActivities $schoolGradeActivity
 */
class ClassTimetableByweekDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'class_timetable_byweek_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['class_timetable_byweek_id', 'school_grade_activity_id', 'dow', 'sequence'], 'required'],
            [['class_timetable_byweek_id', 'school_grade_activity_id', 'dow', 'icon_id', 'sequence', 'status'], 'default', 'value' => null],
            [['class_timetable_byweek_id', 'school_grade_activity_id', 'dow', 'icon_id', 'sequence', 'status'], 'integer'],
            [['from_time', 'to_time', 'created_at', 'updated_at'], 'safe'],
            [['class_timetable_byweek_id'], 'exist', 'skipOnError' => true, 'targetClass' => ClassTimetableByweeks::className(), 'targetAttribute' => ['class_timetable_byweek_id' => 'id']],
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
            'class_timetable_byweek_id' => 'Class Timetable Byweek ID',
            'school_grade_activity_id' => 'School Grade Activity ID',
            'dow' => 'Dow',
            'from_time' => 'From Time',
            'to_time' => 'To Time',
            'icon_id' => 'Icon ID',
            'sequence' => 'Sequence',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetableByweek()
    {
        return $this->hasOne(ClassTimetableByweeks::className(), ['id' => 'class_timetable_byweek_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolGradeActivity()
    {
        return $this->hasOne(SchoolGradeActivities::className(), ['id' => 'school_grade_activity_id']);
    }
}
