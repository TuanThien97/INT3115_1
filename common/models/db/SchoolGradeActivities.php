<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "school_grade_activities".
 *
 * @property int $id
 * @property int $school_grade_id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ClassTimetableDetails[] $classTimetableDetails
 * @property SchoolGrades $schoolGrade
 */
class SchoolGradeActivities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'school_grade_activities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['school_grade_id', 'name', 'description'], 'required'],
            [['school_grade_id', 'status'], 'default', 'value' => null],
            [['school_grade_id', 'status'], 'integer'],
            [['name', 'description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['school_grade_id'], 'exist', 'skipOnError' => true, 'targetClass' => SchoolGrades::className(), 'targetAttribute' => ['school_grade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_grade_id' => 'School Grade ID',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassTimetableDetails()
    {
        return $this->hasMany(ClassTimetableDetails::className(), ['school_grade_activity_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSchoolGrade()
    {
        return $this->hasOne(SchoolGrades::className(), ['id' => 'school_grade_id']);
    }
}
